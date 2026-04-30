<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female'];
        $gender = fake()->randomElement($genders);
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        return [
            'user_id' => null,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'nik' => fake()->unique()->numerify(str_repeat('#', 16)),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
            'gender' => $gender,
            'address' => fake()->address(),
            'blood_type' => fake()->randomElement($bloodTypes),
            'allergies' => fake()->optional(0.4)->sentence(),
            'medical_history' => fake()->optional(0.3)->paragraph(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'notes' => fake()->optional(0.3)->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the patient should have an associated user account.
     */
    public function withUser(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory()->create([
                    'role' => 'staff',
                    'name' => $attributes['name'],
                    'email' => $attributes['email'],
                ])->id,
            ];
        });
    }
}
