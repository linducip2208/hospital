<?php

namespace Database\Seeders;

use App\Models\Surgery;
use Illuminate\Database\Seeder;

class SurgerySeeder extends Seeder
{
    public function run(): void
    {
        $surgeries = [
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'name' => 'Appendektomi (Operasi Usus Buntu)',
                'description' => 'Pengangkatan apendiks yang meradang secara laparoskopi.',
                'scheduled_date' => now()->addDays(2),
                'status' => 'scheduled',
                'notes' => 'Pasien puasa 8 jam sebelum operasi.',
            ],
            [
                'patient_id' => 2,
                'doctor_id' => 2,
                'name' => 'Sectio Caesarea (Operasi Caesar)',
                'description' => 'Persalinan melalui pembedahan pada dinding abdomen dan uterus.',
                'scheduled_date' => now()->addDays(1),
                'status' => 'scheduled',
                'notes' => 'Kehamilan 38 minggu, posisi bayi sungsang.',
            ],
            [
                'patient_id' => 3,
                'doctor_id' => 3,
                'name' => 'Herniorrhaphy (Perbaikan Hernia)',
                'description' => 'Perbaikan hernia inguinalis dengan pemasangan mesh.',
                'scheduled_date' => now()->addDays(5),
                'status' => 'scheduled',
                'notes' => 'Hernia inguinalis lateralis kanan.',
            ],
            [
                'patient_id' => 4,
                'doctor_id' => 4,
                'name' => 'Cholecystectomy (Operasi Batu Empedu)',
                'description' => 'Pengangkatan kandung empedu secara laparoskopi karena batu empedu simptomatik.',
                'scheduled_date' => now()->addDays(3),
                'status' => 'scheduled',
                'notes' => 'Hasil USG: multiple gallstones.',
            ],
            [
                'patient_id' => 5,
                'doctor_id' => 1,
                'name' => 'Cataract Surgery (Operasi Katarak)',
                'description' => 'Fakoemulsifikasi dan implantasi lensa intraokular.',
                'scheduled_date' => now()->subDays(1),
                'status' => 'completed',
                'notes' => 'Operasi berjalan lancar, pasien rawat jalan.',
            ],
            [
                'patient_id' => 6,
                'doctor_id' => 2,
                'name' => 'Tonsillectomy (Operasi Amandel)',
                'description' => 'Pengangkatan tonsil karena tonsilitis kronis berulang.',
                'scheduled_date' => now()->addDays(4),
                'status' => 'scheduled',
                'notes' => 'Pasien anak usia 8 tahun.',
            ],
            [
                'patient_id' => 7,
                'doctor_id' => 3,
                'name' => 'ORIF Fraktur Tibia',
                'description' => 'Open Reduction Internal Fixation untuk fraktur tibia akibat kecelakaan.',
                'scheduled_date' => now(),
                'status' => 'in_progress',
                'notes' => 'CITO - segera.',
            ],
            [
                'patient_id' => 8,
                'doctor_id' => 4,
                'name' => 'Thyroidectomy',
                'description' => 'Pengangkatan kelenjar tiroid sebagian karena nodul mencurigakan.',
                'scheduled_date' => now()->addDays(7),
                'status' => 'scheduled',
                'notes' => 'Hasil FNAB: Bethesda IV.',
            ],
            [
                'patient_id' => 9,
                'doctor_id' => 1,
                'name' => 'Hysterectomy',
                'description' => 'Pengangkatan uterus karena mioma uteri multipel.',
                'scheduled_date' => now()->subDays(3),
                'status' => 'cancelled',
                'notes' => 'Dibatalkan karena pasien demam.',
            ],
            [
                'patient_id' => 10,
                'doctor_id' => 2,
                'name' => 'Craniectomy Decompressive',
                'description' => 'Dekompresi tengkorak untuk mengurangi tekanan intrakranial akibat cedera kepala berat.',
                'scheduled_date' => now()->addDays(1),
                'status' => 'scheduled',
                'notes' => 'Pasien di ICU pasca operasi direncanakan.',
            ],
        ];

        foreach ($surgeries as $surgery) {
            Surgery::create($surgery);
        }
    }
}
