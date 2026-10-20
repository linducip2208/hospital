<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function staffToken(string $role, string $email): string
    {
        $user = User::factory()->create(['role' => $role, 'email' => $email, 'password' => Hash::make('secret-pass')]);
        $res = $this->postJson('/api/auth/tokens', [
            'email' => $user->email, 'password' => 'secret-pass', 'name' => 'sec-test',
        ]);
        $res->assertCreated();

        return $res->json('token');
    }

    private function as(string $token)
    {
        $this->app['auth']->forgetGuards();

        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_modul_sensitif_dibatasi_per_peran(): void
    {
        $cashier = $this->staffToken('cashier', 'kasir@hospital.test');
        $finance = $this->staffToken('finance', 'finance@hospital.test');
        $admin = $this->staffToken('admin', 'admin@hospital.test');

        // Kasir tidak boleh baca/tulis gaji & jurnal, tidak boleh tulis jurnal
        $this->as($cashier)->getJson('/api/v1/salaries')->assertForbidden();
        $this->as($cashier)->getJson('/api/v1/journal-entries')->assertForbidden();
        $this->as($cashier)->postJson('/api/v1/salaries', [])->assertForbidden();
        $this->as($cashier)->postJson('/api/v1/journal-entries', [])->assertForbidden();

        // Finance boleh
        $this->as($finance)->getJson('/api/v1/salaries')->assertOk();
        $this->as($finance)->getJson('/api/v1/journal-entries')->assertOk();

        // Admin lolos semua
        $this->as($admin)->getJson('/api/v1/salaries')->assertOk();
        $this->as($admin)->getJson('/api/v1/employees')->assertOk();

        // Modul umum tetap terbuka untuk staf
        $this->as($cashier)->getJson('/api/v1/drugs')->assertOk();
    }

    public function test_pasien_bisa_mencabut_token_sendiri(): void
    {
        $patient = Patient::factory()->create(['password' => Hash::make('pasien123')]);
        $res = $this->postJson('/api/auth/patients/tokens', [
            'email' => $patient->email, 'password' => 'pasien123',
        ]);
        $token = $res->json('token');

        $this->as($token)->getJson('/api/v1/me')->assertOk();
        $this->as($token)->deleteJson('/api/v1/auth/patients/tokens/current')->assertNoContent();
        $this->as($token)->getJson('/api/v1/me')->assertUnauthorized();
    }

    public function test_respons_tidak_membocorkan_secret(): void
    {
        $admin = $this->staffToken('admin', 'admin2@hospital.test');
        $res = $this->as($admin)->getJson('/api/v1/employees');
        $res->assertOk();
        foreach ($res->json('data') as $row) {
            foreach (array_keys($row) as $key) {
                $this->assertDoesNotMatchRegularExpression(
                    '/password|token|secret|api_key|remember/i',
                    (string) $key,
                    "Field sensitif bocor: {$key}"
                );
            }
        }
    }
}
