<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PatientPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function makePatient(): Patient
    {
        return Patient::create([
            'name' => 'Budi Pasien',
            'email' => 'budi@portal.test',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
    }

    public function test_login_page_renders(): void
    {
        $this->get('/portal/login')->assertOk()->assertSee('Masuk');
    }

    public function test_patient_can_login(): void
    {
        $this->makePatient();

        $this->post('/portal/login', ['email' => 'budi@portal.test', 'password' => 'password'])
            ->assertRedirect('/portal');

        $this->assertAuthenticatedAs(Patient::first(), 'patient');
    }

    public function test_patient_can_register(): void
    {
        $this->post('/portal/register', [
            'name' => 'Siti',
            'email' => 'siti@portal.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/portal');

        $this->assertDatabaseHas('patients', ['email' => 'siti@portal.test']);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get('/portal')->assertRedirect('/portal/login');
    }

    public function test_authenticated_patient_sees_dashboard(): void
    {
        $patient = $this->makePatient();

        $this->actingAs($patient, 'patient')
            ->get('/portal')
            ->assertOk()
            ->assertSee('Budi Pasien');
    }

    public function test_patient_cannot_view_others_appointment(): void
    {
        $patient = $this->makePatient();
        $other = Patient::create(['name' => 'Lain', 'email' => 'lain@portal.test', 'password' => Hash::make('x'), 'is_active' => true]);

        $appointment = Appointment::factory()->create(['patient_id' => $other->id]);

        $this->actingAs($patient, 'patient')
            ->get('/portal/appointments/'.$appointment->id)
            ->assertForbidden();
    }
}
