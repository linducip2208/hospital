<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'appointment_id' => fake()->optional(0.6)->passthrough(Appointment::factory()),
            'diagnosis' => fake()->sentence(),
            'action' => fake()->paragraph(),
            'medicine' => fake()->sentence(),
            'vital_signs' => [
                'blood_pressure' => fake()->randomElement(['110/70', '120/80', '130/85', '140/90']),
                'heart_rate' => fake()->numberBetween(60, 100),
                'temperature' => fake()->randomFloat(1, 36.0, 38.5),
                'weight' => fake()->randomFloat(1, 45, 100),
            ],
            'lab_results' => fake()->optional(0.5)->paragraph(),
            'notes' => fake()->optional(0.4)->sentence(),
        ];
    }
}
