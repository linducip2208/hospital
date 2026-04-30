<?php

namespace Tests\Feature\Api;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_treatments(): void
    {
        Treatment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1/treatments');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_treatment(): void
    {
        $data = [
            'name' => 'Konsultasi Jantung',
            'description' => 'Konsultasi dengan spesialis jantung',
            'category' => 'Konsultasi',
            'price' => 200000,
            'duration_minutes' => 60,
            'notes' => 'Harus membawa hasil rekam jantung',
            'requirements' => ['Membawa rujukan'],
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/treatments', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Konsultasi Jantung']);
    }

    public function test_cannot_create_treatment_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/treatments', [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_treatment(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/treatments/{$treatment->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $treatment->id]);
    }

    public function test_can_update_treatment(): void
    {
        $treatment = Treatment::factory()->create(['name' => 'Treatment Lama']);

        $response = $this->actingAs($this->user)->putJson("/api/v1/treatments/{$treatment->id}", [
            'name' => 'Treatment Baru',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('treatments', ['id' => $treatment->id, 'name' => 'Treatment Baru']);
    }

    public function test_can_delete_treatment(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/treatments/{$treatment->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($treatment);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/treatments');

        $response->assertStatus(401);
    }
}
