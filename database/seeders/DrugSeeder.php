<?php

namespace Database\Seeders;

use App\Models\Drug;
use Illuminate\Database\Seeder;

class DrugSeeder extends Seeder
{
    public function run(): void
    {
        $drugs = [
            ['name' => 'Paracetamol', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 300, 'price' => 5000, 'is_active' => true],
            ['name' => 'Amoxicillin', 'category' => 'Kapsul', 'unit' => 'Strip', 'stock' => 250, 'price' => 15000, 'is_active' => true],
            ['name' => 'Omeprazole', 'category' => 'Kapsul', 'unit' => 'Strip', 'stock' => 200, 'price' => 12000, 'is_active' => true],
            ['name' => 'Ibuprofen', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 280, 'price' => 8000, 'is_active' => true],
            ['name' => 'Cetirizine', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 150, 'price' => 10000, 'is_active' => true],
            ['name' => 'Ranitidine', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 180, 'price' => 9000, 'is_active' => true],
            ['name' => 'Dexamethasone', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 120, 'price' => 7000, 'is_active' => true],
            ['name' => 'Metronidazole', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 220, 'price' => 11000, 'is_active' => true],
            ['name' => 'Ciprofloxacin', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 190, 'price' => 18000, 'is_active' => true],
            ['name' => 'Azithromycin', 'category' => 'Kapsul', 'unit' => 'Strip', 'stock' => 160, 'price' => 25000, 'is_active' => true],
            ['name' => 'Loratadine', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 140, 'price' => 13000, 'is_active' => true],
            ['name' => 'Antasida', 'category' => 'Sirup', 'unit' => 'Botol', 'stock' => 100, 'price' => 15000, 'is_active' => true],
            ['name' => 'Betadine', 'category' => 'Salep', 'unit' => 'Tube', 'stock' => 200, 'price' => 20000, 'is_active' => true],
            ['name' => 'Vitamin C', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 400, 'price' => 6000, 'is_active' => true],
            ['name' => 'Simvastatin', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 130, 'price' => 35000, 'is_active' => true],
            ['name' => 'Amlodipine', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 170, 'price' => 22000, 'is_active' => true],
            ['name' => 'Captopril', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 210, 'price' => 10000, 'is_active' => true],
            ['name' => 'Metformin', 'category' => 'Tablet', 'unit' => 'Strip', 'stock' => 260, 'price' => 14000, 'is_active' => true],
            ['name' => 'Furosemide', 'category' => 'Injeksi', 'unit' => 'Ampul', 'stock' => 90, 'price' => 16000, 'is_active' => true],
            ['name' => 'Diazepam', 'category' => 'Injeksi', 'unit' => 'Ampul', 'stock' => 80, 'price' => 20000, 'is_active' => true],
        ];

        foreach ($drugs as $drug) {
            Drug::create($drug);
        }
    }
}
