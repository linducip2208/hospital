<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            ChartOfAccountSeeder::class,
            UserSeeder::class,
            PageContentSeeder::class,
            MassiveDataSeeder::class,
            BlogSeeder::class,
            DashboardDemoSeeder::class,
            HospitalWorkflowDemoSeeder::class,
        ]);
    }
}
