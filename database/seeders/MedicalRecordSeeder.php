<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::whereIn('status', ['completed', 'in_progress'])->get();

        if ($appointments->isEmpty()) {
            $this->command->warn('No completed or in_progress appointments found. Skipping MedicalRecordSeeder.');
            return;
        }

        $count = min(20, $appointments->count());

        foreach ($appointments->random($count) as $appointment) {
            $systolic = fake()->numberBetween(100, 160);
            $diastolic = fake()->numberBetween(60, 100);

            $diagnoses = [
                'Hipertensi derajat 1',
                'Diabetes Melitus tipe 2',
                'Infeksi Saluran Pernafasan Akut (ISPA)',
                'Gastritis akut',
                'Migrain',
                'Osteoarthritis lutut kanan',
                'Dermatitis kontak alergi',
                'Conjunctivitis',
                'Tifoid fever',
                'Anemia defisiensi besi',
                'Dispepsia fungsional',
                'Faringitis akut',
                'Low Back Pain (LBP)',
                'Vertigo perifer',
                'Cedera ankle kanan',
            ];

            $actions = [
                'Pemberian obat oral',
                'Pemasangan infus',
                'Injeksi IM/IV',
                'Pemberian resep obat',
                'Tindakan jahit luka',
                'Pembersihan luka',
                'Edukasi pasien',
                'Rujuk ke spesialis',
                'Fisioterapi ringan',
            ];

            $medicines = [
                'Paracetamol 500mg 3x1',
                'Amoxicillin 500mg 3x1',
                'Captopril 25mg 2x1',
                'Metformin 500mg 2x1',
                'Omeprazole 20mg 1x1',
                'Ciprofloxacin 500mg 2x1',
                'Ibuprofen 400mg 3x1',
                'Ranitidine 150mg 2x1',
                'Diazepam 5mg 1x1',
                'Salbutamol inhaler 2x1',
            ];

            MedicalRecord::create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'appointment_id' => $appointment->id,
                'diagnosis' => fake()->randomElement($diagnoses),
                'action' => fake()->randomElement($actions),
                'medicine' => fake()->randomElement($medicines) . ', ' . fake()->randomElement($medicines),
                'vital_signs' => [
                    'blood_pressure' => "$systolic/$diastolic",
                    'heart_rate' => fake()->numberBetween(60, 100),
                    'respiratory_rate' => fake()->numberBetween(12, 24),
                    'temperature' => fake()->randomFloat(1, 36.0, 38.5),
                    'weight' => fake()->numberBetween(45, 90),
                    'height' => fake()->numberBetween(150, 180),
                ],
                'lab_results' => fake()->optional(0.5)->sentence(),
                'notes' => 'Pasien dianjurkan kontrol kembali dalam ' . fake()->randomElement(['1 minggu', '2 minggu', '1 bulan']) . '. ' . fake()->optional(0.3)->sentence(),
            ]);
        }
    }
}
