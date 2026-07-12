<?php

namespace Database\Factories;

use App\Models\Drug;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Drug>
 */
class DrugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $drugs = [
            'Paracetamol', 'Amoxicillin', 'Omeprazole', 'Ibuprofen',
            'Cetirizine', 'Ranitidine', 'Dexamethasone', 'Lansoprazole',
            'Metronidazole', 'Ciprofloxacin', 'Azithromycin', 'Loratadine',
            'Aspirin', 'Simvastatin', 'Amlodipine', 'Captopril',
            'Metformin', 'Glibenclamide', 'Furosemide', 'Diazepam',
            'Vitamin C', 'Vitamin B Complex', 'Antasida', 'Betadine',
        ];

        return [
            'name' => fake()->randomElement($drugs),
            'category' => fake()->randomElement([
                'Tablet', 'Sirup', 'Salep', 'Injeksi', 'Kapsul',
            ]),
            'unit' => fake()->randomElement([
                'Strip', 'Botol', 'Ampul', 'Tube',
            ]),
            'stock' => fake()->numberBetween(10, 500),
            'price' => fake()->numberBetween(5000, 500000),
            'is_active' => true,
        ];
    }
}
