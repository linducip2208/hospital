<?php

namespace Tests\Feature\Http;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
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
        Payment::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/payments');

        $response->assertStatus(200);
        $response->assertViewIs('payments.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/payments/create');

        $response->assertStatus(200);
        $response->assertViewIs('payments.create');
    }

    public function test_store(): void
    {
        $appointment = Appointment::factory()->create();

        $data = [
            'appointment_id' => $appointment->id,
            'amount' => 250000,
            'payment_method' => 'cash',
            'status' => 'completed',
            'notes' => 'Lunas',
        ];

        $response = $this->actingAs($this->user)->post('/payments', $data);

        $response->assertRedirect('/payments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', ['appointment_id' => $appointment->id, 'amount' => 250000]);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/payments', [
            'appointment_id' => '',
            'amount' => '',
            'status' => '',
        ]);

        $response->assertSessionHasErrors(['appointment_id', 'amount', 'status']);
    }

    public function test_show(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($this->user)->get("/payments/{$payment->id}");

        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
        $response->assertSee(number_format($payment->amount, 0, ',', '.'));
    }

    public function test_edit(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($this->user)->get("/payments/{$payment->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('payments.edit');
    }

    public function test_update(): void
    {
        $payment = Payment::factory()->create(['amount' => 100000]);
        $newAppointment = Appointment::factory()->create();

        $response = $this->actingAs($this->user)->put("/payments/{$payment->id}", [
            'appointment_id' => $newAppointment->id,
            'amount' => 350000,
            'payment_method' => 'transfer',
            'status' => 'completed',
            'notes' => 'Updated payment',
        ]);

        $response->assertRedirect('/payments');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'amount' => 350000]);
    }

    public function test_destroy(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($this->user)->delete("/payments/{$payment->id}");

        $response->assertRedirect('/payments');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($payment);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/payments');

        $response->assertRedirect('/login');
    }
}
