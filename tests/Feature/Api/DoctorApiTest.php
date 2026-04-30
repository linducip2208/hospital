<?php

namespace Tests\Feature\Api;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_doctors(): void
    {
        Doctor::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1/doctors');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_doctor(): void
    {
        $data = [
            'name' => 'Dr. Andi',
            'email' => 'dr.andi@example.com',
            'phone' => '081234567891',
            'specialization' => 'Jantung',
            'str_number' => 'STR-1234567890',
            'address' => 'Jl. Sehat No. 1',
            'consultation_fee' => 150000,
            'status' => 'active',
            'notes' => 'Dokter spesialis jantung',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/doctors', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['str_number' => 'STR-1234567890']);
    }

    public function test_cannot_create_doctor_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/doctors', [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_doctor(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/doctors/{$doctor->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $doctor->id]);
    }

    public function test_can_update_doctor(): void
    {
        $doctor = Doctor::factory()->create(['name' => 'Dr. Lama']);

        $response = $this->actingAs($this->user)->putJson("/api/v1/doctors/{$doctor->id}", [
            'name' => 'Dr. Baru',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('doctors', ['id' => $doctor->id, 'name' => 'Dr. Baru']);
    }

    public function test_can_delete_doctor(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/doctors/{$doctor->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($doctor);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/doctors');

        $response->assertStatus(401);
    }
}
