<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Developer (CMS)', 'username' => 'developer', 'email' => 'developer@hospital.test', 'role' => 'developer'],
            ['name' => 'Admin Rumah Sakit', 'username' => 'admin', 'email' => 'admin@hospital.test', 'role' => 'admin'],
            ['name' => 'Dr. Andi', 'username' => 'dr.andi', 'email' => 'dr.andi@hospital.test', 'role' => 'doctor'],
            ['name' => 'Staff Administrasi', 'username' => 'staff', 'email' => 'staff@hospital.test', 'role' => 'staff'],
            ['name' => 'Perawat Rina', 'username' => 'nurse.rina', 'email' => 'nurse.rina@hospital.test', 'role' => 'nurse'],
            ['name' => 'Apoteker Dian', 'username' => 'apt.dian', 'email' => 'apt.dian@hospital.test', 'role' => 'pharmacist'],
            ['name' => 'Kasir Budi', 'username' => 'kasir.budi', 'email' => 'kasir.budi@hospital.test', 'role' => 'cashier'],
            ['name' => 'Lab Teknisi Eko', 'username' => 'lab.eko', 'email' => 'lab.eko@hospital.test', 'role' => 'lab_technician'],
            ['name' => 'Bidan Sari', 'username' => 'bidan.sari', 'email' => 'bidan.sari@hospital.test', 'role' => 'midwife'],
            ['name' => 'Perawat Wati', 'username' => 'perawat.wati', 'email' => 'perawat.wati@hospital.test', 'role' => 'nurse'],
            ['name' => 'IT Support', 'username' => 'it.support', 'email' => 'it.support@hospital.test', 'role' => 'IT'],
            ['name' => 'Finance Staff', 'username' => 'finance.staff', 'email' => 'finance@hospital.test', 'role' => 'finance'],
            ['name' => 'HR Manager', 'username' => 'hr.manager', 'email' => 'hr@hospital.test', 'role' => 'HR'],
            ['name' => 'Direktur', 'username' => 'director', 'email' => 'director@hospital.test', 'role' => 'director'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'username' => $user['username'], 'password' => Hash::make('password'), 'role' => $user['role']]
            );
        }
    }
}
