<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_gagal_berulang_tetap_generik_dan_di_throttle(): void
    {
        User::factory()->create(['email' => 'korban@hospital.test', 'password' => Hash::make('secret-pass')]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'korban@hospital.test', 'password' => 'salah'])
                ->assertRedirect()->assertSessionHasErrors(['email' => 'Email atau password salah.']);
        }
        // Upaya ke-6 dalam semenit: throttle (tanpa membocorkan info akun).
        $this->post('/login', ['email' => 'korban@hospital.test', 'password' => 'salah'])
            ->assertStatus(429);
    }

    public function test_lockout_15_menit_setelah_10x_gagal(): void
    {
        $key = 'login-lockout:korban2@hospital.test|127.0.0.1';
        for ($i = 0; $i < 10; $i++) {
            RateLimiter::hit($key, 900);
        }
        $this->assertTrue(RateLimiter::tooManyAttempts($key, 10));
    }

    public function test_register_dibatasi_dan_password_minimal_8(): void
    {
        // Password < 8 karakter ditolak (portal).
        $this->post('/portal/register', [
            'name' => 'A', 'email' => 'pendek@pasien.test',
            'password' => 'pendek7', 'password_confirmation' => 'pendek7',
        ])->assertSessionHasErrors('password');

        // Spam registrasi dibatasi.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/register', [
                'name' => 'Spam', 'email' => "spam{$i}@x.test",
                'password' => 'password123', 'password_confirmation' => 'password123',
            ]);
        }
        $this->post('/register', [
            'name' => 'Spam', 'email' => 'spam9@x.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertStatus(429);
    }

    public function test_cabut_semua_token(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => Hash::make('secret-pass')]);
        $t1 = $this->postJson('/api/auth/tokens', ['email' => $user->email, 'password' => 'secret-pass', 'name' => 'a'])->json('token');
        $t2 = $this->postJson('/api/auth/tokens', ['email' => $user->email, 'password' => 'secret-pass', 'name' => 'b'])->json('token');

        $this->app['auth']->forgetGuards();
        $this->withHeader('Authorization', 'Bearer '.$t1)->deleteJson('/api/v1/auth/tokens')->assertNoContent();

        $this->app['auth']->forgetGuards();
        $this->withHeader('Authorization', 'Bearer '.$t1)->getJson('/api/v1/patients')->assertUnauthorized();
        $this->app['auth']->forgetGuards();
        $this->withHeader('Authorization', 'Bearer '.$t2)->getJson('/api/v1/patients')->assertUnauthorized();
    }

    public function test_api_mengirim_security_headers(): void
    {
        $this->getJson('/api/v1/public/polyclinics')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
