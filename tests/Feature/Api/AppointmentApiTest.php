<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_appointments(): void
    {
        Appointment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_appointment(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-05-15',
            'start_time' => '09:00',
            'complaint' => 'Sakit kepala',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/appointments', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['complaint' => 'Sakit kepala']);
    }

    public function test_cannot_create_appointment_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/appointments', [
            'patient_id' => '',
            'doctor_id' => '',
            'appointment_date' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['patient_id', 'doctor_id', 'appointment_date']);
    }

    public function test_can_show_appointment(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $appointment->id]);
    }

    public function test_can_update_appointment(): void
    {
        $appointment = Appointment::factory()->create(['complaint' => 'Keluhan lama']);
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->putJson("/api/v1/appointments/{$appointment->id}", [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-06-01',
            'complaint' => 'Keluhan baru',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'complaint' => 'Keluhan baru']);
    }

    public function test_can_delete_appointment(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($appointment);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/appointments');

        $response->assertStatus(401);
    }
}
