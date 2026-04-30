<?php

namespace Tests\Feature\Http;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentControllerTest extends TestCase
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
        Treatment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/treatments');

        $response->assertStatus(200);
        $response->assertViewIs('treatments.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/treatments/create');

        $response->assertStatus(200);
        $response->assertViewIs('treatments.create');
    }

    public function test_store(): void
    {
        $data = [
            'name' => 'Konsultasi Jantung',
            'description' => 'Pemeriksaan jantung lengkap',
            'category' => 'Konsultasi',
            'price' => 250000,
            'duration_minutes' => 60,
            'notes' => 'Pasien harus puasa',
            'requirements' => "Puasa 8 jam\nMembawa rujukan",
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)->post('/treatments', $data);

        $response->assertRedirect('/treatments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('treatments', ['name' => 'Konsultasi Jantung']);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/treatments', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_show(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->actingAs($this->user)->get("/treatments/{$treatment->id}");

        $response->assertStatus(200);
        $response->assertViewIs('treatments.show');
        $response->assertSee($treatment->name);
    }

    public function test_edit(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->actingAs($this->user)->get("/treatments/{$treatment->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('treatments.edit');
    }

    public function test_update(): void
    {
        $treatment = Treatment::factory()->create(['name' => 'Old Treatment']);

        $response = $this->actingAs($this->user)->put("/treatments/{$treatment->id}", [
            'name' => 'Updated Treatment',
            'description' => $treatment->description,
            'category' => $treatment->category,
            'price' => $treatment->price,
            'duration_minutes' => $treatment->duration_minutes,
            'notes' => $treatment->notes,
            'requirements' => $treatment->requirements ? implode("\n", $treatment->requirements) : null,
            'is_active' => true,
        ]);

        $response->assertRedirect('/treatments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('treatments', ['id' => $treatment->id, 'name' => 'Updated Treatment']);
    }

    public function test_destroy(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->actingAs($this->user)->delete("/treatments/{$treatment->id}");

        $response->assertRedirect('/treatments');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($treatment);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/treatments');

        $response->assertRedirect('/login');
    }
}
