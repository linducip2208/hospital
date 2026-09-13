<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Admission;
use App\Models\ChartOfAccount;
use App\Models\DischargeSummary;
use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\Encounter;
use App\Models\Emergency;
use App\Models\HospitalBed;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Room;
use App\Models\Surgery;
use App\Services\BillingService;
use App\Services\EncounterService;
use App\Services\JournalService;
use App\Services\PharmacyDispensingService;
use App\Services\RefundService;
use App\Services\AdmissionService;
use App\Services\LabWorkflowService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CriticalLabResultNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach ([['1-001', 'Kas', 'asset', 'debit'], ['1-002', 'Piutang', 'asset', 'debit'], ['1-006', 'Persediaan Obat', 'asset', 'debit'], ['4-001', 'Pendapatan Rawat Jalan', 'revenue', 'credit'], ['4-002', 'Pendapatan Rawat Inap', 'revenue', 'credit'], ['5-002', 'Beban Obat', 'expense', 'debit']] as [$code, $name, $type, $normal]) ChartOfAccount::create(['account_code' => $code, 'account_name' => $name, 'account_type' => $type, 'normal_balance' => $normal]);
    }

    public function test_outpatient_journey_links_clinical_pharmacy_billing_and_accounting(): void
    {
        $patient = Patient::factory()->create(); $treatment = Treatment::factory()->create(['price' => 100000]); $drug = Drug::factory()->create(['stock' => 10, 'price' => 5000]);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id, 'treatment_id' => $treatment->id, 'status' => 'confirmed']);
        $encounter = app(EncounterService::class)->fromAppointment($appointment);
        $this->assertDatabaseHas('charges', ['encounter_id' => $encounter->id, 'source_type' => 'consultation']);
        $rx = Prescription::create(['rx_no' => 'RX-TEST-1', 'patient_id' => $patient->id, 'appointment_id' => $appointment->id, 'encounter_id' => $encounter->id, 'prescribed_at' => today(), 'status' => 'issued']);
        PrescriptionItem::create(['prescription_id' => $rx->id, 'drug_id' => $drug->id, 'drug_name' => $drug->name, 'quantity' => 3]);
        DrugBatch::create(['drug_id' => $drug->id, 'batch_no' => 'EARLY', 'expiry_date' => now()->addMonths(2), 'quantity_received' => 3, 'quantity_available' => 3, 'purchase_price' => 3000, 'selling_price' => 5000]);
        DrugBatch::create(['drug_id' => $drug->id, 'batch_no' => 'LATE', 'expiry_date' => now()->addYear(), 'quantity_received' => 7, 'quantity_available' => 7, 'purchase_price' => 3000, 'selling_price' => 5000]);
        app(PharmacyDispensingService::class)->dispense($rx);
        $this->assertDatabaseHas('stock_movements', ['movement_type' => 'dispense', 'quantity' => -3]);
        $bill = app(BillingService::class)->generateBill($encounter);
        $this->assertSame(115000.0, (float) $bill->subtotal);
        app(BillingService::class)->receivePayment($bill, ['amount' => 50000, 'payment_method' => 'cash']);
        $bill->refresh(); $this->assertSame('partial', $bill->status); $this->assertSame(3, \App\Models\JournalEntry::whereIn('source_type', ['bill', 'payment', 'dispensing'])->count());
        app(BillingService::class)->receivePayment($bill, ['amount' => $bill->balance, 'payment_method' => 'transfer']);
        $this->assertDatabaseHas('bills', ['id' => $bill->id, 'status' => 'paid', 'balance' => 0]);
        $journal = app(JournalService::class)->post('payment', 999999, 'Idempotency test', [['account_code' => '1-001', 'debit' => 10], ['account_code' => '1-002', 'credit' => 10]]);
        $same = app(JournalService::class)->post('payment', 999999, 'Idempotency test', [['account_code' => '1-001', 'debit' => 10], ['account_code' => '1-002', 'credit' => 10]]);
        $this->assertSame($journal->id, $same->id);
    }

    public function test_non_authorized_role_cannot_view_patient(): void
    {
        $patient = Patient::factory()->create(); $user = User::factory()->create(['role' => 'finance']); $this->actingAs($user);
        $this->get('/patients/'.$patient->id)->assertForbidden();
    }

    public function test_refund_reverses_bill_balance_and_posts_reversal(): void
    {
        $patient = Patient::factory()->create(); $encounter = app(EncounterService::class)->forPatient($patient);
        app(BillingService::class)->addCharge(['patient_id' => $patient->id, 'encounter_id' => $encounter->id, 'source_type' => 'consultation', 'source_id' => 88001, 'description' => 'Konsultasi', 'quantity' => 1, 'unit_price' => 100000, 'amount' => 100000]);
        $bill = app(BillingService::class)->generateBill($encounter); $payment = app(BillingService::class)->receivePayment($bill, ['amount' => 100000, 'payment_method' => 'cash']);
        app(RefundService::class)->process($payment, 25000, 'Koreksi pembayaran');
        $this->assertDatabaseHas('refunds', ['payment_id' => $payment->id, 'amount' => 25000, 'status' => 'processed']);
        $this->assertDatabaseHas('bills', ['id' => $bill->id, 'status' => 'partial', 'balance' => 25000]);
        $this->assertDatabaseHas('journal_entries', ['source_type' => 'refund']);
    }

    public function test_inpatient_admission_requires_finalized_summary_and_preserves_bed_history(): void
    {
        $patient = Patient::factory()->create();
        $encounter = app(EncounterService::class)->forPatient($patient, null, auth()->id(), 'inpatient');
        $room = Room::factory()->create();
        $bed = HospitalBed::create(['room_id' => $room->id, 'bed_code' => 'BED-TEST-1', 'label' => 'Bed 1', 'status' => 'available']);

        $admission = app(AdmissionService::class)->admit($encounter, $bed);
        $this->assertDatabaseHas('bed_movements', ['admission_id' => $admission->id, 'hospital_bed_id' => $bed->id]);
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(AdmissionService::class)->discharge($admission);
    }

    public function test_discharge_finishes_after_summary_and_bill_are_ready(): void
    {
        $patient = Patient::factory()->create();
        $encounter = app(EncounterService::class)->forPatient($patient, null, auth()->id(), 'inpatient');
        $room = Room::factory()->create(['price_per_day' => 250000]);
        $bed = HospitalBed::create(['room_id' => $room->id, 'bed_code' => 'BED-TEST-2', 'label' => 'Bed 2', 'status' => 'available']);
        $admission = app(AdmissionService::class)->admit($encounter, $bed);
        app(BillingService::class)->generateBill($encounter);
        DischargeSummary::create(['summary_no' => 'RP-TEST-1', 'patient_id' => $patient->id, 'encounter_id' => $encounter->id, 'admission_date' => now()->subDay(), 'discharge_date' => now(), 'admission_diagnosis' => 'Demam', 'discharge_diagnosis' => 'Membaik', 'discharge_condition' => 'improved', 'status' => 'finalized', 'finalized_by' => auth()->id(), 'finalized_at' => now()]);

        app(AdmissionService::class)->discharge($admission);
        $this->assertDatabaseHas('admissions', ['id' => $admission->id, 'status' => 'discharged']);
        $this->assertDatabaseHas('hospital_beds', ['id' => $bed->id, 'status' => 'available', 'current_patient_id' => null]);
        $this->assertDatabaseHas('encounters', ['id' => $encounter->id, 'status' => 'discharged']);
    }

    public function test_lab_result_transition_verifies_and_notifies_critical_result(): void
    {
        Notification::fake();
        $patient = Patient::factory()->create();
        $doctor = \App\Models\Doctor::factory()->create();
        $lab = LabTest::create(['patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'test_name' => 'Troponin', 'status' => 'requested']);
        app(LabWorkflowService::class)->collect($lab, 'Serum');
        app(LabWorkflowService::class)->start($lab);
        app(LabWorkflowService::class)->verify($lab, ['results' => 'Positif', 'critical_flag' => true, 'abnormal_flag' => 'critical']);
        $this->assertDatabaseHas('lab_tests', ['id' => $lab->id, 'status' => 'completed', 'result_status' => 'verified', 'critical_flag' => 1]);
        Notification::assertSentTo(User::where('role', 'admin')->firstOrFail(), CriticalLabResultNotification::class);
    }

    public function test_emergency_registration_creates_emergency_encounter(): void
    {
        $patient = Patient::factory()->create();
        $this->post(route('emergencies.store'), ['patient_id' => $patient->id, 'triage' => 'red', 'complaint' => 'Sesak napas'])
            ->assertRedirect(route('emergencies.index'));
        $emergency = Emergency::latest('id')->first();
        $this->assertNotNull($emergency?->encounter_id);
        $this->assertDatabaseHas('encounters', ['id' => $emergency->encounter_id, 'encounter_type' => 'emergency']);
    }

    public function test_surgery_registration_creates_surgical_encounter(): void
    {
        $patient = Patient::factory()->create();
        $doctor = \App\Models\Doctor::factory()->create();
        $this->post(route('surgeries.store'), ['patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'name' => 'Appendektomi', 'status' => 'scheduled', 'charge_amount' => 2500000])
            ->assertRedirect();
        $surgery = Surgery::latest('id')->firstOrFail();
        $this->assertDatabaseHas('encounters', ['id' => $surgery->encounter_id, 'patient_id' => $patient->id, 'encounter_type' => 'surgery']);
        $this->assertDatabaseHas('surgeries', ['id' => $surgery->id, 'surgeon_id' => $doctor->id]);
    }
}
