<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorNames = [
            ['name' => 'Dr. Budi Santoso', 'specialization' => 'Umum'],
            ['name' => 'Dr. Siti Rahmawati', 'specialization' => 'Jantung'],
            ['name' => 'Dr. Hendra Wijaya', 'specialization' => 'Saraf'],
            ['name' => 'Dr. Dewi Lestari', 'specialization' => 'Mata'],
            ['name' => 'Dr. Agus Pratama', 'specialization' => 'THT'],
            ['name' => 'Dr. Maya Anggraini', 'specialization' => 'Kulit'],
            ['name' => 'Dr. Rudi Hartono', 'specialization' => 'Anak'],
            ['name' => 'Dr. Fitriani Putri', 'specialization' => 'Obgyn'],
            ['name' => 'Dr. Bambang Susilo', 'specialization' => 'Bedah'],
            ['name' => 'Dr. Lisa Kusuma', 'specialization' => 'Orthopedi'],
        ];

        foreach ($doctorNames as $index => $doctorData) {
            $user = User::factory()->create([
                'name' => $doctorData['name'],
                'username' => 'dr.' . strtolower(str_replace(' ', '', explode('. ', $doctorData['name'])[1] ?? $doctorData['name'])),
                'email' => strtolower(str_replace(' ', '.', $doctorData['name'])) . '@hospital.test',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'name' => $doctorData['name'],
                'email' => $user->email,
                'phone' => '0812' . fake()->unique()->numerify('########'),
                'specialization' => $doctorData['specialization'],
                'str_number' => 'STR-' . fake()->unique()->numerify('######') . '/' . fake()->year(),
                'address' => fake()->address(),
                'consultation_fee' => match ($doctorData['specialization']) {
                    'Umum' => 150000,
                    'Jantung' => 350000,
                    'Saraf' => 350000,
                    'Mata' => 250000,
                    'THT' => 200000,
                    'Kulit' => 200000,
                    'Anak' => 200000,
                    'Obgyn' => 300000,
                    'Bedah' => 400000,
                    'Orthopedi' => 300000,
                    default => 150000,
                },
                'status' => 'active',
            ]);
        }
    }
}
