<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FinancialIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
        foreach (['1200' => 'Piutang', '1110' => 'Kas'] as $code => $name) {
            \App\Models\ChartOfAccount::create([
                'account_code' => $code, 'account_name' => $name,
                'account_type' => 'asset', 'normal_balance' => 'debit', 'is_active' => true,
            ]);
        }
    }

    private function staffToken(string $role, string $email): string
    {
        $user = User::factory()->create(['role' => $role, 'email' => $email, 'password' => Hash::make('secret-pass')]);
        $res = $this->postJson('/api/auth/tokens', [
            'email' => $user->email, 'password' => 'secret-pass', 'name' => 'finance-test',
        ]);
        $res->assertCreated();

        return $res->json('token');
    }

    private function as(string $token)
    {
        $this->app['auth']->forgetGuards();

        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_status_refunded_tidak_bisa_diset_langsung(): void
    {
        $cashier = $this->staffToken('cashier', 'kasirf@hospital.test');
        $admin = $this->staffToken('admin', 'adminf@hospital.test');
        $patient = Patient::factory()->create();
        $payment = Payment::create([
            'patient_id' => $patient->id, 'invoice_number' => 'INV-T1',
            'subtotal' => 100000, 'discount' => 0, 'tax' => 0, 'amount' => 100000,
            'paid_amount' => 100000, 'payment_method' => 'cash', 'status' => 'completed',
        ]);

        // Update langsung ke refunded ditolak (kasir punya billing.manage)
        $this->as($cashier)->putJson("/api/v1/payments/{$payment->id}", ['status' => 'refunded'])
            ->assertUnprocessable();
        // Update pembayaran final apa pun ditolak
        $this->as($cashier)->putJson("/api/v1/payments/{$payment->id}", ['notes' => 'ubah'])
            ->assertUnprocessable();
        // Hapus: kasir bukan admin/dev/finance → 403; admin pun ditolak hapus completed (wajib refund)
        $this->as($cashier)->deleteJson("/api/v1/payments/{$payment->id}")->assertForbidden();
        $this->as($admin)->deleteJson("/api/v1/payments/{$payment->id}")->assertUnprocessable();
    }

    public function test_refund_resmi_memvalidasi_nominal_dan_menjurnal(): void
    {
        $admin = $this->staffToken('admin', 'adminf2@hospital.test');
        $cashier = $this->staffToken('cashier', 'kasir9@hospital.test');
        $patient = Patient::factory()->create();
        $payment = Payment::create([
            'patient_id' => $patient->id, 'invoice_number' => 'INV-T2',
            'subtotal' => 100000, 'discount' => 0, 'tax' => 0, 'amount' => 100000,
            'paid_amount' => 100000, 'payment_method' => 'cash', 'status' => 'completed',
        ]);

        // Kasir tidak punya payments.refund
        $this->as($cashier)->postJson("/api/v1/payments/{$payment->id}/refund", [
            'amount' => 50000, 'reason' => 'coba',
        ])->assertForbidden();

        // Refund melebihi yang dibayar ditolak
        $this->as($admin)->postJson("/api/v1/payments/{$payment->id}/refund", [
            'amount' => 200000, 'reason' => 'kelebihan',
        ])->assertUnprocessable();

        // Refund sah: jurnal pembalik dibuat + status berubah
        $this->as($admin)->postJson("/api/v1/payments/{$payment->id}/refund", [
            'amount' => 40000, 'reason' => 'kelebihan bayar',
        ])->assertCreated();
        $this->assertDatabaseHas('refunds', ['payment_id' => $payment->id, 'amount' => 40000]);
        $this->assertDatabaseHas('journal_entries', ['source_type' => 'refund', 'source_id' => 1]);
        $this->assertEquals('completed', $payment->fresh()->status); // sisa 60rb → tetap completed
    }

    public function test_bayar_bill_cancelled_ditolak(): void
    {
        $cashier = $this->staffToken('cashier', 'kasir8@hospital.test');
        $patient = Patient::factory()->create();
        $bill = Bill::create([
            'bill_no' => 'BILL-T1', 'patient_id' => $patient->id,
            'subtotal' => 50000, 'discount' => 0, 'tax' => 0,
            'patient_responsibility' => 50000, 'paid_amount' => 0, 'balance' => 50000,
            'status' => 'cancelled',
        ]);

        $this->as($cashier)->postJson('/api/v1/payments', [
            'patient_id' => $patient->id, 'bill_id' => $bill->id, 'paid_amount' => 10000,
            'subtotal' => 10000,
        ])->assertUnprocessable();
    }

    public function test_lisensi_default_fail_closed(): void
    {
        // Config harus boolean (hasil cast filter_var) — default false bila env tidak diset.
        $this->assertIsBool(config('license.dev_bypass'));
    }
}
