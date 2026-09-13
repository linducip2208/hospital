<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicalOrder;
use App\Models\Diagnosis;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Treatment;
use App\Services\BillingService;
use App\Services\EncounterService;
use App\Services\PharmacyDispensingService;
use Illuminate\Database\Seeder;

class HospitalWorkflowDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (\App\Models\Encounter::count() > 0) return;
        $patient = Patient::first(); $doctor = Doctor::first(); $poli = Polyclinic::first();
        $treatment = Treatment::first() ?: Treatment::create(['name' => 'Konsultasi Demo', 'slug' => 'konsultasi-demo', 'category' => 'Konsultasi', 'price' => 100000, 'duration_minutes' => 30, 'is_active' => true]);
        $drug = Drug::where('stock', '>', 0)->first();
        if (! $patient || ! $doctor || ! $poli || ! $treatment || ! $drug) return;

        $appointment = Appointment::create([
            'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'polyclinic_id' => $poli->id, 'treatment_id' => $treatment->id,
            'appointment_date' => now(), 'start_time' => now(), 'end_time' => now()->addMinutes(30), 'status' => 'confirmed', 'complaint' => 'Kontrol terjadwal',
        ]);
        $encounter = app(EncounterService::class)->fromAppointment($appointment);
        Diagnosis::create(['encounter_id' => $encounter->id, 'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'icd10_code' => 'J06.9', 'diagnosis_name' => 'Infeksi saluran napas atas, tidak spesifik', 'diagnosis_type' => 'primary', 'is_confirmed' => true]);
        $record = MedicalRecord::create(['patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'appointment_id' => $appointment->id, 'encounter_id' => $encounter->id, 'diagnosis' => 'Infeksi saluran napas atas', 'icd10_code' => 'J06.9', 'icd10_name' => 'ISPA', 'action' => 'Edukasi dan terapi simptomatik', 'status' => 'finalized', 'finalized_at' => now()]);
        ClinicalOrder::create(['order_no' => app(\App\Services\DocumentNumberService::class)->next('demo_order', 'ORD'), 'patient_id' => $patient->id, 'encounter_id' => $encounter->id, 'ordering_doctor_id' => $doctor->id, 'order_type' => 'laboratory', 'priority' => 'routine', 'status' => 'completed', 'ordered_at' => now()->subHour(), 'completed_at' => now()])->items()->create(['item_type' => 'lab', 'description' => 'Darah lengkap', 'quantity' => 1, 'result_text' => 'Dalam batas normal']);
        $batch = DrugBatch::create(['drug_id' => $drug->id, 'batch_no' => 'DEMO-'.now()->format('Ymd'), 'lot_no' => 'LOT-DEMO', 'expiry_date' => now()->addYear(), 'quantity_received' => $drug->stock, 'quantity_available' => $drug->stock, 'purchase_price' => $drug->price, 'selling_price' => $drug->price]);
        $prescription = Prescription::create(['rx_no' => app(\App\Services\DocumentNumberService::class)->next('demo_prescription', 'RX'), 'patient_id' => $patient->id, 'doctor_id' => $doctor->id, 'medical_record_id' => $record->id, 'appointment_id' => $appointment->id, 'encounter_id' => $encounter->id, 'prescribed_at' => today(), 'status' => 'issued']);
        PrescriptionItem::create(['prescription_id' => $prescription->id, 'drug_id' => $drug->id, 'drug_name' => $drug->name, 'quantity' => 2, 'unit' => $drug->unit]);
        app(PharmacyDispensingService::class)->dispense($prescription);
        $bill = app(BillingService::class)->generateBill($encounter);
        app(BillingService::class)->receivePayment($bill, ['amount' => $bill->balance, 'payment_method' => 'cash', 'notes' => 'Demo workflow']);
        app(EncounterService::class)->complete($encounter);
        $this->command?->info('Workflow demo terhubung: encounter → diagnosis → order → resep → dispensing → bill → payment → journal.');
    }
}
