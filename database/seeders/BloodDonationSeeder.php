<?php

namespace Database\Seeders;

use App\Models\BloodDonation;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class BloodDonationSeeder extends Seeder
{
    public function run(): void
    {
        $patientIds = Patient::pluck('id')->all();

        $donations = [
            [
                'donor_name' => 'Budi Santoso',
                'blood_type' => 'O+',
                'donation_date' => now()->subDays(5),
                'expiry_date' => now()->subDays(5)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => 'Donor rutin, tekanan darah normal.',
            ],
            [
                'donor_name' => 'Siti Aminah',
                'blood_type' => 'A+',
                'donation_date' => now()->subDays(10),
                'expiry_date' => now()->subDays(10)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => null,
            ],
            [
                'donor_name' => 'Ahmad Fauzi',
                'blood_type' => 'B+',
                'donation_date' => now()->subDays(15),
                'expiry_date' => now()->subDays(15)->addDays(42),
                'quantity_ml' => 450,
                'status' => 'available',
                'notes' => 'Berat badan donor 75 kg.',
            ],
            [
                'donor_name' => 'Dewi Kusuma',
                'blood_type' => 'AB+',
                'donation_date' => now()->subDays(20),
                'expiry_date' => now()->subDays(20)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'used',
                'notes' => 'Digunakan untuk pasien operasi Caesar.',
            ],
            [
                'donor_name' => 'Rudi Hartono',
                'blood_type' => 'O-',
                'donation_date' => now()->subDays(50),
                'expiry_date' => now()->subDays(50)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'expired',
                'notes' => 'Kadaluarsa, harus dibuang.',
            ],
            [
                'donor_name' => 'Linda Wijaya',
                'blood_type' => 'A-',
                'donation_date' => now()->subDays(3),
                'expiry_date' => now()->subDays(3)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => 'Golongan darah langka.',
            ],
            [
                'donor_name' => 'Hendra Gunawan',
                'blood_type' => 'B-',
                'donation_date' => now()->subDays(7),
                'expiry_date' => now()->subDays(7)->addDays(42),
                'quantity_ml' => 450,
                'status' => 'available',
                'notes' => null,
            ],
            [
                'donor_name' => 'Rina Marlina',
                'blood_type' => 'O+',
                'donation_date' => now()->subDays(30),
                'expiry_date' => now()->subDays(30)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'used',
                'notes' => 'Digunakan untuk pasien kecelakaan.',
            ],
            [
                'donor_name' => 'Dodi Prasetyo',
                'blood_type' => 'AB-',
                'donation_date' => now()->subDays(12),
                'expiry_date' => now()->subDays(12)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => 'Donor pertama, golongan langka.',
            ],
            [
                'donor_name' => 'Fitri Handayani',
                'blood_type' => 'O+',
                'donation_date' => now()->subDays(25),
                'expiry_date' => now()->subDays(25)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => null,
            ],
            [
                'donor_name' => 'Agus Salim',
                'blood_type' => 'A+',
                'donation_date' => now()->subDays(55),
                'expiry_date' => now()->subDays(55)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'discarded',
                'notes' => 'Kantong darah rusak saat penyimpanan.',
            ],
            [
                'donor_name' => 'Nina Susanti',
                'blood_type' => 'B+',
                'donation_date' => now()->subDays(1),
                'expiry_date' => now()->subDays(1)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => 'Donor baru terdaftar.',
            ],
            [
                'donor_name' => 'Bayu Wicaksono',
                'blood_type' => 'O+',
                'donation_date' => now()->subDays(40),
                'expiry_date' => now()->subDays(40)->addDays(42),
                'quantity_ml' => 450,
                'status' => 'available',
                'notes' => 'Mendekati kadaluarsa (2 hari lagi).',
            ],
            [
                'donor_name' => 'Sari Puspita',
                'blood_type' => 'A+',
                'donation_date' => now()->subDays(8),
                'expiry_date' => now()->subDays(8)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'available',
                'notes' => null,
            ],
            [
                'donor_name' => 'Rizki Pratama',
                'blood_type' => 'O+',
                'donation_date' => now()->subDays(18),
                'expiry_date' => now()->subDays(18)->addDays(42),
                'quantity_ml' => 350,
                'status' => 'used',
                'notes' => 'Digunakan untuk pasien thalassemia.',
            ],
        ];

        foreach ($donations as $donation) {
            if (!empty($patientIds) && fake()->boolean(50)) {
                $donation['patient_id'] = fake()->randomElement($patientIds);
            }
            BloodDonation::create($donation);
        }
    }
}
