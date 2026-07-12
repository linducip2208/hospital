<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            ['asset_code' => 'AST-001', 'name' => 'USG Machine', 'category' => 'medical', 'purchase_price' => 500000000],
            ['asset_code' => 'AST-002', 'name' => 'CT Scanner', 'category' => 'medical', 'purchase_price' => 2000000000],
            ['asset_code' => 'AST-003', 'name' => 'X-Ray Machine', 'category' => 'medical', 'purchase_price' => 1500000000],
            ['asset_code' => 'AST-004', 'name' => 'Server Dell', 'category' => 'IT', 'purchase_price' => 150000000],
            ['asset_code' => 'AST-005', 'name' => 'Desktop PC x5', 'category' => 'IT', 'purchase_price' => 50000000],
            ['asset_code' => 'AST-006', 'name' => 'Ambulance Toyota', 'category' => 'vehicle', 'purchase_price' => 800000000],
            ['asset_code' => 'AST-007', 'name' => 'Patient Bed x10', 'category' => 'furniture', 'purchase_price' => 200000000],
            ['asset_code' => 'AST-008', 'name' => 'ECG Machine', 'category' => 'medical', 'purchase_price' => 300000000],
            ['asset_code' => 'AST-009', 'name' => 'Ventilator', 'category' => 'medical', 'purchase_price' => 400000000],
            ['asset_code' => 'AST-010', 'name' => 'AC Split x5', 'category' => 'non_medical', 'purchase_price' => 50000000],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}
