<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Rumah Sakit',
            'username' => 'admin',
            'email' => 'admin@hospital.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Doctor user
        User::create([
            'name' => 'Dr. Andi',
            'username' => 'dr.andi',
            'email' => 'dr.andi@hospital.test',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // Staff user
        User::create([
            'name' => 'Staff Administrasi',
            'username' => 'staff',
            'email' => 'staff@hospital.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
