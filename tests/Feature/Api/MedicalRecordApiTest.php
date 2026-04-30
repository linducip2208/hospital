<?php

namespace Tests\Feature\Api;

use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_medical_records(): void
    {
        MedicalRecord::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1/medical-records');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_medical_record(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'diagnosis' => 'Hipertensi',
            'action' => 'Resep obat',
            'medicine' => 'Amlodipin 5mg',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/medical-records', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['diagnosis' => 'Hipertensi']);
    }

    public function test_cannot_create_medical_record_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/medical-records', [
            'patient_id' => '',
            'doctor_id' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['patient_id', 'doctor_id']);
    }

    public function test_can_show_medical_record(): void
    {
        $medicalRecord = MedicalRecord::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/medical-records/{$medicalRecord->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $medicalRecord->id]);
    }

    public function test_can_update_medical_record(): void
    {
        $medicalRecord = MedicalRecord::factory()->create(['diagnosis' => 'Diagnosis lama']);
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->putJson("/api/v1/medical-records/{$medicalRecord->id}", [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'diagnosis' => 'Diagnosis baru',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('medical_records', ['id' => $medicalRecord->id, 'diagnosis' => 'Diagnosis baru']);
    }

    public function test_can_delete_medical_record(): void
    {
        $medicalRecord = MedicalRecord::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/medical-records/{$medicalRecord->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($medicalRecord);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/medical-records');

        $response->assertStatus(401);
    }
}
