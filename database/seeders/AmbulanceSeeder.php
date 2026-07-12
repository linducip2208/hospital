<?php

namespace Database\Seeders;

use App\Models\Ambulance;
use Illuminate\Database\Seeder;

class AmbulanceSeeder extends Seeder
{
    public function run(): void
    {
        $ambulances = [
            [
                'vehicle_number' => 'B 1234 AMB',
                'model' => 'Toyota Hiace',
                'type' => 'Advanced',
                'status' => 'available',
                'driver_name' => 'Herman',
                'driver_phone' => '081234567890',
                'notes' => 'Ambulans utama IGD.',
            ],
            [
                'vehicle_number' => 'B 5678 AMB',
                'model' => 'Suzuki APV',
                'type' => 'Basic',
                'status' => 'on_duty',
                'driver_name' => 'Suryadi',
                'driver_phone' => '081234567891',
                'notes' => 'Sedang antar pasien rujukan.',
            ],
            [
                'vehicle_number' => 'B 9012 AMB',
                'model' => 'Mercedes Sprinter',
                'type' => 'Mobile ICU',
                'status' => 'available',
                'driver_name' => 'Wahyudi',
                'driver_phone' => '081234567892',
                'notes' => 'Dilengkapi ventilator dan monitor.',
            ],
            [
                'vehicle_number' => 'B 3456 AMB',
                'model' => 'Isuzu Elf',
                'type' => 'Basic',
                'status' => 'maintenance',
                'driver_name' => 'Santoso',
                'driver_phone' => '081234567893',
                'notes' => 'Servis berkala di bengkel.',
            ],
            [
                'vehicle_number' => 'B 7890 AMB',
                'model' => 'Mitsubishi L300',
                'type' => 'Basic',
                'status' => 'out_of_service',
                'driver_name' => null,
                'driver_phone' => null,
                'notes' => 'Rusak mesin, menunggu perbaikan.',
            ],
        ];

        foreach ($ambulances as $ambulance) {
            Ambulance::create($ambulance);
        }
    }
}
