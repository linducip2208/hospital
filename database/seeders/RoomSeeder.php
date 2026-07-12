<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // VIP (2 rooms)
            ['room_number' => 'VIP-101', 'room_type' => 'VIP', 'floor' => 1, 'bed_count' => 1, 'price_per_day' => 1500000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Sofa', 'Kulkas', 'Wifi', 'Balkon', 'Dispenser'], 'status' => 'available'],
            ['room_number' => 'VIP-201', 'room_type' => 'VIP', 'floor' => 2, 'bed_count' => 1, 'price_per_day' => 1500000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Sofa', 'Kulkas', 'Wifi', 'Balkon', 'Dispenser'], 'status' => 'available'],

            // Kelas 1 (4 rooms)
            ['room_number' => 'K1-101', 'room_type' => 'Kelas 1', 'floor' => 1, 'bed_count' => 2, 'price_per_day' => 750000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Wifi', 'Sofa'], 'status' => 'available'],
            ['room_number' => 'K1-102', 'room_type' => 'Kelas 1', 'floor' => 1, 'bed_count' => 2, 'price_per_day' => 750000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Wifi', 'Sofa'], 'status' => 'available'],
            ['room_number' => 'K1-201', 'room_type' => 'Kelas 1', 'floor' => 2, 'bed_count' => 2, 'price_per_day' => 750000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Wifi', 'Sofa'], 'status' => 'available'],
            ['room_number' => 'K1-202', 'room_type' => 'Kelas 1', 'floor' => 2, 'bed_count' => 2, 'price_per_day' => 750000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Wifi', 'Sofa'], 'status' => 'available'],

            // Kelas 2 (5 rooms)
            ['room_number' => 'K2-201', 'room_type' => 'Kelas 2', 'floor' => 2, 'bed_count' => 3, 'price_per_day' => 400000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K2-202', 'room_type' => 'Kelas 2', 'floor' => 2, 'bed_count' => 3, 'price_per_day' => 400000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K2-301', 'room_type' => 'Kelas 2', 'floor' => 3, 'bed_count' => 4, 'price_per_day' => 400000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K2-302', 'room_type' => 'Kelas 2', 'floor' => 3, 'bed_count' => 4, 'price_per_day' => 400000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K2-303', 'room_type' => 'Kelas 2', 'floor' => 3, 'bed_count' => 3, 'price_per_day' => 400000, 'facilities' => ['AC', 'TV', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],

            // Kelas 3 (4 rooms)
            ['room_number' => 'K3-301', 'room_type' => 'Kelas 3', 'floor' => 3, 'bed_count' => 4, 'price_per_day' => 200000, 'facilities' => ['Kipas Angin', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K3-302', 'room_type' => 'Kelas 3', 'floor' => 3, 'bed_count' => 4, 'price_per_day' => 200000, 'facilities' => ['Kipas Angin', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K3-401', 'room_type' => 'Kelas 3', 'floor' => 4, 'bed_count' => 4, 'price_per_day' => 200000, 'facilities' => ['Kipas Angin', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
            ['room_number' => 'K3-402', 'room_type' => 'Kelas 3', 'floor' => 4, 'bed_count' => 4, 'price_per_day' => 200000, 'facilities' => ['Kipas Angin', 'Kamar Mandi', 'Lemari'], 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
