<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specializations = [
            'Umum', 'Jantung', 'Saraf', 'Anak', 'Kandungan', 'Mata', 'THT', 'Kulit',
        ];

        return [
            'user_id' => null,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'specialization' => fake()->randomElement($specializations),
            'str_number' => fake()->unique()->numerify('STR-##########'),
            'address' => fake()->address(),
            'consultation_fee' => fake()->numberBetween(50000, 500000),
            'status' => 'active',
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the doctor should have an associated user account.
     */
    public function withUser(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory()->create([
                    'role' => 'doctor',
                    'name' => $attributes['name'],
                    'email' => $attributes['email'],
                ])->id,
            ];
        });
    }
}
