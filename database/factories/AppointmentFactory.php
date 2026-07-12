<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appointmentDate = fake()->dateTimeBetween('now', '+30 days');
        $startHour = fake()->numberBetween(8, 16);
        $startMinute = fake()->randomElement([0, 15, 30, 45]);
        $startTime = sprintf('%02d:%02d', $startHour, $startMinute);
        $startMinutes = $startHour * 60 + $startMinute;
        $duration = fake()->numberBetween(15, 120);
        $endMinutes = $startMinutes + $duration;
        $endTime = sprintf('%02d:%02d', intdiv($endMinutes, 60), $endMinutes % 60);

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'treatment_id' => fake()->optional(0.7)->passthrough(Treatment::factory()),
            'appointment_date' => $appointmentDate->format('Y-m-d H:i:s'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'scheduled',
            'complaint' => fake()->sentence(),
            'notes' => fake()->optional(0.4)->sentence(),
            'reminders' => fake()->randomElements([
                'H-1 via WhatsApp',
                'H-2 via SMS',
                'H-7 via Email',
            ], fake()->numberBetween(0, 2)),
        ];
    }
}
