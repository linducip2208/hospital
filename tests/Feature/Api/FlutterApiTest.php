<?php

namespace Tests\Feature\Api;

use App\Models\ApiToken;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FlutterApiTest extends TestCase
{
    use RefreshDatabase;

    private function staffLogin(string $email = 'staff@hospital.test'): array
    {
        $user = User::factory()->create(['role' => 'admin', 'email' => $email, 'password' => Hash::make('secret-pass')]);
        $res = $this->postJson('/api/auth/tokens', [
            'email' => $user->email, 'password' => 'secret-pass', 'name' => 'flutter-test',
        ]);
        $res->assertCreated()->assertJsonStructure(['token', 'token_type', 'expires_at', 'user']);

        return [$user, $res->json('token')];
    }

    /** Request dengan Bearer token; guard di-reset agar tidak bocor antar-request dalam satu tes. */
    private function as(string $token)
    {
        $this->app['auth']->forgetGuards();

        return $this->withHeader('Authorization', 'Bearer '.$token);
    }

    private function patientToken(Patient $patient, string $password): string
    {
        $res = $this->postJson('/api/auth/patients/tokens', [
            'email' => $patient->email, 'password' => $password, 'name' => 'flutter-test',
        ]);
        $res->assertCreated();

        return $res->json('token');
    }

    public function test_staff_token_expiry_and_refresh(): void
    {
        [$user, $oldToken] = $this->staffLogin();

        // Token lama bisa direfresh menjadi token baru
        $refresh = $this->as($oldToken)->postJson('/api/auth/tokens/refresh');
        $refresh->assertCreated()->assertJsonStructure(['token', 'expires_at']);
        $newToken = $refresh->json('token');

        // Token lama sudah tidak berlaku, token baru berlaku
        $this->as($oldToken)->getJson('/api/v1/patients')->assertUnauthorized();
        $this->as($newToken)->getJson('/api/v1/patients')->assertOk();

        // Token kedaluwarsa ditolak (akses & refresh)
        ApiToken::create([
            'user_id' => $user->id, 'name' => 'old',
            'token_hash' => hash('sha256', 'expired-token-plain'),
            'expires_at' => now()->subDay(),
        ]);
        $this->as('expired-token-plain')->getJson('/api/v1/patients')->assertUnauthorized();
        $this->as('expired-token-plain')->postJson('/api/auth/tokens/refresh')->assertUnauthorized();
    }

    public function test_generic_staff_crud_drugs_and_unknown_resource_404(): void
    {
        [, $token] = $this->staffLogin('gudang@hospital.test');
        Drug::factory()->create(['name' => 'Paracetamol']);

        $this->as($token)->getJson('/api/v1/drugs?search=Paracet')
            ->assertOk()->assertJsonFragment(['name' => 'Paracetamol']);

        $this->as($token)->getJson('/api/v1/tidak-ada-modul')->assertNotFound();

        // Token pasien ditolak di endpoint staf
        $patient = Patient::factory()->create(['password' => Hash::make('pasien123')]);
        $pt = $this->patientToken($patient, 'pasien123');
        $this->as($pt)->getJson('/api/v1/drugs')->assertForbidden();
    }

    public function test_patient_register_login_and_self_service_scoping(): void
    {
        // Registrasi akun pasien baru
        $reg = $this->postJson('/api/auth/patients/register', [
            'name' => 'Pasien Flutter', 'email' => 'flutter@pasien.test',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ]);
        $reg->assertCreated()->assertJsonStructure(['token', 'patient']);
        $token = $reg->json('token');

        // Profil milik sendiri (tanpa password)
        $this->as($token)->getJson('/api/v1/me')
            ->assertOk()->assertJsonFragment(['email' => 'flutter@pasien.test'])
            ->assertJsonMissing(['password' => 'rahasia123']);

        // Booking appointment untuk diri sendiri
        $doctor = Doctor::factory()->create();
        $book = $this->as($token)->postJson('/api/v1/me/appointments', [
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'complaint' => 'Demam',
        ]);
        $book->assertCreated()->assertJsonFragment(['status' => 'scheduled']);
        $appointmentId = $book->json('id');

        // Pasien lain tidak boleh melihat appointment ini
        $other = Patient::factory()->create(['password' => Hash::make('lain1234')]);
        $otherToken = $this->patientToken($other, 'lain1234');
        $this->as($otherToken)->getJson("/api/v1/me/appointments/{$appointmentId}")->assertForbidden();

        // Pemilik boleh melihat
        $this->as($token)->getJson("/api/v1/me/appointments/{$appointmentId}")->assertOk();
    }
}
