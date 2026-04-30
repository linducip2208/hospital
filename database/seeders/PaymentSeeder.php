<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::where('status', 'completed')->get();

        if ($appointments->isEmpty()) {
            $this->command->warn('No completed appointments found. Skipping PaymentSeeder.');
            return;
        }

        $count = min(20, $appointments->count());
        $paymentMethods = ['cash', 'transfer', 'debit', 'credit', 'qris', 'insurance'];

        foreach ($appointments->random($count) as $index => $appointment) {
            $subtotal = fake()->randomElement([150000, 200000, 350000, 500000, 750000, 1000000, 1500000, 2000000]);
            $discount = fake()->optional(0.3)->randomElement([0, 10000, 25000, 50000, 100000]) ?? 0;
            $tax = (int) round(($subtotal - $discount) * 0.11);
            $amount = $subtotal - $discount + $tax;
            $paidAmount = $amount;
            $changeAmount = 0;

            Payment::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'invoice_number' => 'INV/' . now()->format('Y/m') . '/' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'amount' => $amount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => fake()->randomElement($paymentMethods),
                'status' => 'completed',
                'notes' => fake()->optional(0.3)->sentence(),
            ]);
        }
    }
}
