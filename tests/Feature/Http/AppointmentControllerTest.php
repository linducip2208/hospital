<?php

namespace Tests\Feature\Http;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_index(): void
    {
        Appointment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/appointments');

        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/appointments/create');

        $response->assertStatus(200);
        $response->assertViewIs('appointments.create');
    }

    public function test_store(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '09:00',
            'complaint' => 'Sakit kepala',
            'notes' => 'Harap datang tepat waktu',
        ];

        $response = $this->actingAs($this->user)->post('/appointments', $data);

        $response->assertRedirect('/appointments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('appointments', ['patient_id' => $patient->id, 'doctor_id' => $doctor->id]);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/appointments', [
            'patient_id' => '',
            'doctor_id' => '',
            'appointment_date' => '',
            'start_time' => '',
        ]);

        $response->assertSessionHasErrors(['patient_id', 'doctor_id', 'appointment_date', 'start_time']);
    }

    public function test_show(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->get("/appointments/{$appointment->id}");

        $response->assertStatus(200);
        $response->assertViewIs('appointments.show');
        $response->assertSee($appointment->complaint);
    }

    public function test_edit(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->get("/appointments/{$appointment->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('appointments.edit');
    }

    public function test_update(): void
    {
        $appointment = Appointment::factory()->create();
        $newPatient = Patient::factory()->create();
        $newDoctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->put("/appointments/{$appointment->id}", [
            'patient_id' => $newPatient->id,
            'doctor_id' => $newDoctor->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00',
            'status' => 'scheduled',
            'complaint' => 'Updated complaint',
            'notes' => 'Updated notes',
        ]);

        $response->assertRedirect('/appointments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'patient_id' => $newPatient->id]);
    }

    public function test_destroy(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->delete("/appointments/{$appointment->id}");

        $response->assertRedirect('/appointments');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($appointment);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/appointments');

        $response->assertRedirect('/login');
    }
}
