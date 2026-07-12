<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Administrasi', 'code' => 'DEP-ADM'],
            ['name' => 'Pelayanan Medis', 'code' => 'DEP-MED'],
            ['name' => 'Keperawatan', 'code' => 'DEP-NRS'],
            ['name' => 'Farmasi', 'code' => 'DEP-FAR'],
            ['name' => 'Keuangan', 'code' => 'DEP-FIN'],
            ['name' => 'SDM & Umum', 'code' => 'DEP-HR'],
            ['name' => 'Teknologi Informasi', 'code' => 'DEP-IT'],
            ['name' => 'Manajemen', 'code' => 'DEP-MGT'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
