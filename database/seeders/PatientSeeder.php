<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 patients with related user accounts
        Patient::factory()
            ->count(5)
            ->withUser()
            ->create();

        // Create 15 patients without user accounts
        Patient::factory()
            ->count(15)
            ->create();
    }
}
