<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_patients(): void
    {
        Patient::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1/patients');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_patient(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'birth_date' => '1990-01-15',
            'gender' => 'male',
            'address' => 'Jl. Contoh No. 123',
            'blood_type' => 'A',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/patients', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['nik' => '1234567890123456']);
    }

    public function test_cannot_create_patient_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/patients', [
            'name' => '',
            'gender' => 'invalid',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'nik', 'gender']);
    }

    public function test_can_show_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $patient->id]);
    }

    public function test_can_update_patient(): void
    {
        $patient = Patient::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user)->putJson("/api/v1/patients/{$patient->id}", [
            'name' => 'Updated Name',
            'email' => $patient->email,
            'phone' => $patient->phone,
            'nik' => $patient->nik,
            'birth_date' => $patient->birth_date->format('Y-m-d'),
            'gender' => $patient->gender,
            'address' => $patient->address,
            'blood_type' => $patient->blood_type,
            'is_active' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('patients', ['id' => $patient->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/patients/{$patient->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($patient);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/patients');

        $response->assertStatus(401);
    }
}
