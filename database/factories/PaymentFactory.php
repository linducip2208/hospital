<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    private static int $invoiceCounter = 1000;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100000, 5000000);
        $discount = fake()->optional(0.3)->numberBetween(10000, 500000) ?? 0;
        $tax = (int) round($subtotal * 0.11); // 11% PPN
        $amount = $subtotal - $discount + $tax;
        $paidAmount = fake()->optional(0.2)->numberBetween($amount, $amount + 100000) ?? $amount;
        $changeAmount = max(0, $paidAmount - $amount);

        return [
            'patient_id' => Patient::factory(),
            'appointment_id' => fake()->optional(0.7)->passthrough(Appointment::factory()),
            'invoice_number' => 'INV/' . now()->format('Y/m') . '/' . str_pad(static::$invoiceCounter++, 5, '0', STR_PAD_LEFT),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'amount' => $amount,
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'card', 'insurance', 'other']),
            'status' => 'pending',
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
