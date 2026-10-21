<?php

namespace Tests\Feature;

use App\Models\ChartOfAccount;
use App\Models\Doctor;
use App\Models\Encounter;
use App\Models\JournalEntry;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\User;
use App\Services\BillingService;
use App\Services\RefundService;
use Database\Seeders\ChartOfAccountSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ClinicalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(ChartOfAccountSeeder::class);
        foreach (['1200', '1110', '4100'] as $code) {
            ChartOfAccount::updateOrCreate(['account_code' => $code], [
                'account_name' => 'COA '.$code, 'account_type' => 'asset',
                'normal_balance' => 'debit', 'is_active' => true,
            ]);
        }
    }

    private function staffToken(string $role, string $email): string
    {
        $user = User::factory()->create(['role' => $role, 'email' => $email, 'password' => Hash::make('secret-pass')]);
        $res = $this->postJson('/api/auth/tokens', [
            'email' => $user->email, 'password' => 'secret-pass', 'name' => 'workflow',
        ]);
        $res->assertCreated();

        return $res->json('token');
    }

    public function test_alur_pendaftaran_sampai_refund_terjurnal(): void
    {
        $billing = app(BillingService::class);
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        // 1. Encounter + charge
        $encounter = Encounter::create([
            'encounter_no' => 'ENC-TEST-1',
            'patient_id' => $patient->id, 'doctor_id' => $doctor->id,
            'encounter_type' => 'outpatient', 'payer_type' => 'general', 'status' => 'in_progress',
        ]);
        $billing->addCharge([
            'patient_id' => $patient->id, 'encounter_id' => $encounter->id,
            'source_type' => 'treatment', 'description' => 'Konsultasi',
            'quantity' => 1, 'unit_price' => 150000, 'amount' => 150000,
        ]);

        // 2. Generate bill → pendapatan dijurnal
        $bill = $billing->generateBill($encounter);
        $this->assertEquals(150000, (float) $bill->patient_responsibility);
        $this->assertDatabaseHas('journal_entries', ['source_type' => 'bill', 'source_id' => $bill->id]);

        // 3. Bayar parsial → kurang → tolak; bayar pas → lunas
        try {
            $billing->receivePayment($bill, ['amount' => 200000, 'payment_method' => 'cash']);
            $this->fail('Overpayment harus ditolak.');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }
        $billing->receivePayment($bill, ['amount' => 50000, 'payment_method' => 'cash']);
        $this->assertEquals('partial', $bill->fresh()->status);
        $billing->receivePayment($bill, ['amount' => 100000, 'payment_method' => 'cash']);
        $this->assertEquals('paid', $bill->fresh()->status);
        $this->assertDatabaseCount('journal_entries', 3); // bill + 2 payment

        // 4. Refund parsial → jurnal pembalik + saldo terkoreksi
        $payment = Payment::whereHas('allocations', fn ($q) => $q->where('bill_id', $bill->id))->orderByDesc('id')->first();
        $this->assertNotNull($payment);
        $refundService = app(RefundService::class);
        $refundService->process($payment, 20000, 'kelebihan');
        $this->assertDatabaseHas('journal_entries', ['source_type' => 'refund']);
        $this->assertEquals('partial', $bill->fresh()->status);

        // 5. Double refund melebihi sisa → ditolak (rollback, tidak ada jurnal ganda)
        $journals = JournalEntry::count();
        try {
            $refundService->process($payment, 200000, 'nakal');
            $this->fail('Refund berlebih harus ditolak.');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }
        $this->assertEquals($journals, JournalEntry::count());
    }

    public function test_idempotency_dan_diskon_dibatasi(): void
    {
        $cashier = $this->staffToken('cashier', 'kasirw@hospital.test');
        $this->app['auth']->forgetGuards();
        $patient = Patient::factory()->create();
        $headers = ['Authorization' => 'Bearer '.$cashier];

        // Diskon > subtotal ditolak
        $this->withHeaders($headers)->postJson('/api/v1/payments', [
            'patient_id' => $patient->id, 'subtotal' => 100000, 'discount' => 150000,
            'paid_amount' => 0,
        ])->assertUnprocessable();

        // Request sama dengan idempotency key → tidak duplikat
        $payload = [
            'patient_id' => $patient->id, 'subtotal' => 100000,
            'paid_amount' => 100000, 'idempotency_key' => 'key-unik-123',
        ];
        $r1 = $this->withHeaders($headers)->postJson('/api/v1/payments', $payload);
        $r1->assertCreated();
        $this->app['auth']->forgetGuards();
        $r2 = $this->withHeaders($headers)->postJson('/api/v1/payments', $payload);
        $r2->assertOk()->assertJsonFragment(['replayed' => true]);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertEquals(1, Payment::where('idempotency_key', 'key-unik-123')->count());
    }
}
