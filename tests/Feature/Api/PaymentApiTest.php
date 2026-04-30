<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_list_payments(): void
    {
        Payment::factory()->count(3)->create([
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/payments');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_create_payment(): void
    {
        $patient = Patient::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'subtotal' => 200000,
            'paid_amount' => 200000,
            'payment_method' => 'cash',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/payments', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['payment_method' => 'cash']);
    }

    public function test_cannot_create_payment_with_invalid_data(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/payments', [
            'patient_id' => '',
            'subtotal' => '',
            'paid_amount' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['patient_id', 'subtotal', 'paid_amount']);
    }

    public function test_can_show_payment(): void
    {
        $payment = Payment::factory()->create([
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $payment->id]);
    }

    public function test_can_update_payment(): void
    {
        $payment = Payment::factory()->create([
            'subtotal' => 100000,
            'payment_method' => 'cash',
        ]);
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->user)->putJson("/api/v1/payments/{$payment->id}", [
            'patient_id' => $patient->id,
            'subtotal' => 300000,
            'paid_amount' => 300000,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'subtotal' => 300000]);
    }

    public function test_can_delete_payment(): void
    {
        $payment = Payment::factory()->create([
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/payments/{$payment->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($payment);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->getJson('/api/v1/payments');

        $response->assertStatus(401);
    }
}
