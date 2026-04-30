<?php

namespace Database\Factories;

use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement([
                'Pemeriksaan', 'Konsultasi', 'Tindakan', 'Laboratorium', 'Rawat Inap',
            ]),
            'price' => fake()->numberBetween(100000, 5000000),
            'duration_minutes' => fake()->randomElement([15, 30, 45, 60, 90, 120]),
            'notes' => fake()->optional(0.4)->sentence(),
            'requirements' => fake()->randomElements([
                'Puasa 8 jam',
                'Membawa rujukan',
                'Membawa hasil lab sebelumnya',
                'Konsultasi dokter umum terlebih dahulu',
                'Persetujuan tindakan medis',
            ], fake()->numberBetween(0, 3)),
            'is_active' => true,
        ];
    }
}
