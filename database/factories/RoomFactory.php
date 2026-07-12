<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomType = fake()->randomElement(['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3']);

        $prefixes = [
            'VIP' => 'VIP',
            'Kelas 1' => 'K1',
            'Kelas 2' => 'K2',
            'Kelas 3' => 'K3',
        ];

        $prefix = $prefixes[$roomType];

        return [
            'room_number' => $prefix . '-' . fake()->numberBetween(101, 599),
            'room_type' => $roomType,
            'floor' => fake()->numberBetween(1, 5),
            'bed_count' => fake()->numberBetween(1, 4),
            'price_per_day' => fake()->numberBetween(150000, 1500000),
            'facilities' => fake()->randomElements([
                'AC', 'TV', 'Kamar Mandi', 'Wifi', 'Sofa',
                'Meja', 'Lemari', 'Kulkas', 'Dispenser', 'Balkon',
            ], fake()->numberBetween(3, 6)),
            'status' => 'available',
        ];
    }
}
