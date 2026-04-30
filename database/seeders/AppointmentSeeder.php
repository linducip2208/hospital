<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $treatments = Treatment::all();

        if ($patients->isEmpty() || $doctors->isEmpty() || $treatments->isEmpty()) {
            $this->command->warn('Patients, doctors, or treatments not found. Skipping AppointmentSeeder.');
            return;
        }

        $statuses = ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'];

        for ($i = 0; $i < 30; $i++) {
            $patient = $patients->random();
            $doctor = $doctors->random();
            $treatment = $treatments->random();

            // Spread dates across the last 30 days and next 30 days
            $daysOffset = fake()->numberBetween(-30, 30);
            $date = now()->addDays($daysOffset);
            $startHour = fake()->numberBetween(8, 16);
            $startMinute = fake()->randomElement([0, 15, 30, 45]);

            $status = $statuses[array_rand($statuses)];

            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'treatment_id' => $treatment->id,
                'appointment_date' => $date->format('Y-m-d H:i:s'),
                'start_time' => sprintf('%02d:%02d:00', $startHour, $startMinute),
                'end_time' => sprintf('%02d:%02d:00', $startHour + 1, $startMinute),
                'status' => $status,
                'complaint' => fake()->randomElement([
                    'Demam dan batuk sejak 3 hari',
                    'Sakit kepala bagian belakang',
                    'Nyeri ulu hati',
                    'Sesak nafas',
                    'Pusing berputar',
                    'Nyeri sendi lutut kanan',
                    'Ruam merah di kulit',
                    'Sakit gigi',
                    'Mata merah dan berair',
                    'Cek kesehatan rutin',
                    'Mual dan muntah',
                    'Nyeri punggung bawah',
                    'Luka bakar ringan',
                    'Kontrol diabetes',
                    'Kontrol hipertensi',
                ]),
                'notes' => fake()->optional(0.3)->sentence(),
                'reminders' => json_encode([['type' => 'email', 'sent' => false]]),
            ]);
        }
    }
}
