<?php

namespace Tests\Feature\Http;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientControllerTest extends TestCase
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
        Patient::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/patients');

        $response->assertStatus(200);
        $response->assertViewIs('patients.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/patients/create');

        $response->assertStatus(200);
        $response->assertViewIs('patients.create');
    }

    public function test_store(): void
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

        $response = $this->actingAs($this->user)->post('/patients', $data);

        $response->assertRedirect('/patients');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('patients', ['nik' => '1234567890123456']);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/patients', [
            'name' => '',
            'gender' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['name', 'gender']);
    }

    public function test_show(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->get("/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertViewIs('patients.show');
        $response->assertSee($patient->name);
    }

    public function test_edit(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->get("/patients/{$patient->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('patients.edit');
    }

    public function test_update(): void
    {
        $patient = Patient::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user)->put("/patients/{$patient->id}", [
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

        $response->assertRedirect('/patients');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('patients', ['id' => $patient->id, 'name' => 'Updated Name']);
    }

    public function test_destroy(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->delete("/patients/{$patient->id}");

        $response->assertRedirect('/patients');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($patient);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/patients');

        $response->assertRedirect('/login');
    }
}
