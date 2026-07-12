<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $nonDoctorUsers = User::whereIn('role', ['staff', 'nurse', 'midwife', 'pharmacist', 'cashier', 'lab_technician'])
            ->whereDoesntHave('employee')
            ->get();

        $roles = [
            'staff' => [
                'position' => 'Staff Administrasi',
                'department' => 'Administrasi',
                'salary' => 4500000,
            ],
            'nurse' => [
                'position' => 'Perawat',
                'department' => 'Keperawatan',
                'salary' => 6000000,
            ],
            'midwife' => [
                'position' => 'Bidan',
                'department' => 'Kebidanan',
                'salary' => 6500000,
            ],
            'pharmacist' => [
                'position' => 'Apoteker',
                'department' => 'Farmasi',
                'salary' => 7000000,
            ],
            'cashier' => [
                'position' => 'Kasir',
                'department' => 'Keuangan',
                'salary' => 4000000,
            ],
            'lab_technician' => [
                'position' => 'Teknisi Laboratorium',
                'department' => 'Laboratorium',
                'salary' => 5500000,
            ],
        ];

        $employeeCount = Employee::count();

        foreach ($nonDoctorUsers as $user) {
            $roleConfig = $roles[$user->role] ?? [
                'position' => 'Staff',
                'department' => 'Umum',
                'salary' => 3000000,
            ];

            $employeeCount++;
            Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP-' . str_pad($employeeCount, 3, '0', STR_PAD_LEFT),
                'position' => $roleConfig['position'],
                'department' => $roleConfig['department'],
                'join_date' => now()->subMonths(rand(3, 36))->format('Y-m-d'),
                'base_salary' => $roleConfig['salary'] + rand(-500000, 500000),
                'bank_name' => fake()->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
                'bank_account' => fake()->numerify('##############'),
                'bpjs_tk' => fake()->numerify('###########'),
                'tax_number' => fake()->numerify('##.###.###.#-###.###'),
                'employment_status' => 'permanent',
                'education_level' => fake()->randomElement(['D3', 'S1', 'S2']),
                'emergency_contact' => '08' . fake()->numerify('##########'),
                'notes' => null,
            ]);
        }
    }
}
