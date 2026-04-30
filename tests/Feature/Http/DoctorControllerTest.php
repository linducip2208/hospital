<?php

namespace Tests\Feature\Http;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorControllerTest extends TestCase
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
        Doctor::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/doctors');

        $response->assertStatus(200);
        $response->assertViewIs('doctors.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/doctors/create');

        $response->assertStatus(200);
        $response->assertViewIs('doctors.create');
    }

    public function test_store(): void
    {
        $data = [
            'name' => 'Dr. John Doe',
            'email' => 'dr.john@example.com',
            'phone' => '081234567890',
            'specialization' => 'Jantung',
            'str_number' => 'STR-1234567890',
            'address' => 'Jl. Kesehatan No. 10',
            'consultation_fee' => 150000,
            'status' => 'active',
            'notes' => 'Dokter spesialis jantung',
        ];

        $response = $this->actingAs($this->user)->post('/doctors', $data);

        $response->assertRedirect('/doctors');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('doctors', ['str_number' => 'STR-1234567890']);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/doctors', [
            'name' => '',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['name', 'status']);
    }

    public function test_show(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->get("/doctors/{$doctor->id}");

        $response->assertStatus(200);
        $response->assertViewIs('doctors.show');
        $response->assertSee($doctor->name);
    }

    public function test_edit(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->get("/doctors/{$doctor->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('doctors.edit');
    }

    public function test_update(): void
    {
        $doctor = Doctor::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user)->put("/doctors/{$doctor->id}", [
            'name' => 'Updated Name',
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'specialization' => $doctor->specialization,
            'str_number' => $doctor->str_number,
            'address' => $doctor->address,
            'consultation_fee' => $doctor->consultation_fee,
            'status' => $doctor->status,
            'notes' => $doctor->notes,
        ]);

        $response->assertRedirect('/doctors');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('doctors', ['id' => $doctor->id, 'name' => 'Updated Name']);
    }

    public function test_destroy(): void
    {
        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->delete("/doctors/{$doctor->id}");

        $response->assertRedirect('/doctors');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($doctor);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/doctors');

        $response->assertRedirect('/login');
    }
}
