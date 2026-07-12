<?php

namespace Database\Seeders;

use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Drug;
use App\Models\Emergency;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Polyclinic;
use App\Models\Queue;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MassiveDataSeeder extends Seeder
{
    private $polyclinics;
    private $doctors;
    private $rooms;
    private $drugs;
    private $ambulances;

    public function run(): void
    {
        // Hapus data lama (simulasi ulang dari awal)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AmbulanceCall::truncate();
        Emergency::truncate();
        Queue::truncate();
        MedicalRecord::truncate();
        Payment::truncate();
        Appointment::truncate();
        Patient::truncate();
        Doctor::truncate();
        Polyclinic::truncate();
        Room::truncate();
        Drug::truncate();
        Ambulance::truncate();
        // Bersihkan user non-admin
        User::where('role', '!=', 'admin')->update(['deleted_at' => now()]);
        DB::statement('DELETE FROM users WHERE role != ?', ['admin']);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Pastikan ada admin
        if (!User::where('email', 'admin@hospital.id')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@hospital.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Memulai seeding 10.000+ data...');

        // 1. Poli (12)
        $this->seedPolyclinics();

        // 2. Dokter (100)
        $this->seedDoctors(100);

        // 3. Kamar (80) — semua terisi
        $this->seedRooms(80);

        // 4. Obat (200)
        $this->seedDrugs(200);

        // 5. Ambulans (15)
        $this->seedAmbulances(15);

        // 6. Pasien (10.000)
        $this->seedPatients(10000);

        // 7. Appointment 2 tahun (harian, 20-60/day)
        $this->seedTwoYearsData();

        // Update kamar: semua occupied
        Room::query()->update(['status' => 'occupied']);
        $this->command->info('Semua kamar diset occupied.');

        $this->command->info('✅ Seeding selesai!');
        $this->command->info('   Patient: ' . number_format(Patient::count()));
        $this->command->info('   Doctor: ' . number_format(Doctor::count()));
        $this->command->info('   Appointment: ' . number_format(Appointment::count()));
        $this->command->info('   Payment: ' . number_format(Payment::count()));
        $this->command->info('   Medical Record: ' . number_format(MedicalRecord::count()));
        $this->command->info('   Emergency: ' . number_format(Emergency::count()));
        $this->command->info('   Room (occupied): ' . Room::where('status', 'occupied')->count() . '/' . Room::count());
    }

    private function seedPolyclinics(): void
    {
        $names = [
            'Poli Umum', 'Poli Gigi', 'Poli Anak', 'Poli Kandungan',
            'Poli Jantung', 'Poli Saraf', 'Poli Mata', 'Poli THT',
            'Poli Kulit & Kelamin', 'Poli Orthopedi', 'Poli Bedah', 'Poli Paru',
        ];
        $codes = ['UMUM', 'GIGI', 'ANAK', 'KAND', 'JANT', 'SARF', 'MATA', 'THT', 'KULT', 'ORTO', 'BEDH', 'PARU'];
        foreach ($names as $i => $name) {
            Polyclinic::create([
                'name' => $name,
                'code' => $codes[$i],
                'description' => 'Poliklinik spesialis ' . strtolower($name),
                'floor' => rand(1, 4),
                'phone' => '021' . rand(1000000, 9999999),
                'is_active' => true,
            ]);
        }
        $this->polyclinics = Polyclinic::all();
        $this->command->info(count($this->polyclinics) . ' poli dibuat.');
    }

    private function seedDoctors(int $count): void
    {
        $specializations = [
            'Dokter Umum', 'Dokter Gigi', 'Dokter Spesialis Anak', 'Dokter Spesialis Kandungan',
            'Dokter Spesialis Jantung', 'Dokter Spesialis Saraf', 'Dokter Spesialis Mata',
            'Dokter Spesialis THT', 'Dokter Spesialis Kulit', 'Dokter Spesialis Orthopedi',
            'Dokter Spesialis Bedah', 'Dokter Spesialis Paru', 'Dokter Spesialis Penyakit Dalam',
            'Dokter Spesialis Radiologi', 'Dokter Spesialis Anestesi', 'Dokter Spesialis Rehab Medik',
            'Dokter Spesialis Gizi', 'Dokter Spesialis Patologi', 'Dokter Spesialis Forensik',
            'Dokter Spesialis Onkologi',
        ];

        $names = $this->indonesianNames(($count + 100));

        for ($i = 0; $i < $count; $i++) {
            $name = $names[$i];
            $email = 'dr.' . strtolower(str_replace(' ', '.', $name)) . ($i + 1) . '@hospital.id';

            $user = User::create([
                'name' => 'dr. ' . $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'email_verified_at' => now(),
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'name' => 'dr. ' . $name,
                'email' => $email,
                'phone' => '0812' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'specialization' => $specializations[$i % count($specializations)],
                'str_number' => 'STR-' . date('Y') . '-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'address' => 'Jl. ' . $this->randomStreet() . ' No. ' . rand(1, 200) . ', Jakarta',
                'consultation_fee' => rand(150000, 800000),
                'status' => 'active',
            ]);
        }
        $this->doctors = Doctor::all();
        $this->command->info("$count dokter dibuat.");
    }

    private function seedRooms(int $count): void
    {
        $types = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'NICU', 'OK'];
        $facilityPool = ['AC', 'TV', 'Kamar Mandi Dalam', 'Wifi', 'Sofa', 'Meja Makan', 'Lemari', 'Kulkas', 'Dispenser', 'Balkon', 'Ruang Tunggu', 'Nurse Call', 'Oksigen Central'];

        for ($i = 0; $i < $count; $i++) {
            $type = $types[$i % count($types)];
            $floor = ($i % 4) + 1;
            $prefix = match ($type) {
                'VIP' => 'VIP', 'Kelas 1' => 'K1', 'Kelas 2' => 'K2',
                'Kelas 3' => 'K3', 'ICU' => 'ICU', 'NICU' => 'NIC', 'OK' => 'OK',
                default => 'RM',
            };

            $facilities = collect($facilityPool)->random(min(6, count($facilityPool)))->toArray();
            if ($type === 'VIP') $facilities = array_merge($facilities, ['Lounge', 'Smart TV']);
            if (in_array($type, ['ICU', 'NICU'])) $facilities = ['Ventilator', 'Monitor', 'Nurse Call', 'Oksigen Central'];

            Room::create([
                'room_number' => $prefix . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'room_type' => $type,
                'floor' => $floor,
                'bed_count' => in_array($type, ['Kelas 3']) ? rand(2, 4) : rand(1, 2),
                'price_per_day' => match ($type) {
                    'VIP' => rand(800000, 1500000),
                    'Kelas 1' => rand(400000, 750000),
                    'Kelas 2' => rand(200000, 350000),
                    'Kelas 3' => rand(100000, 180000),
                    'ICU', 'NICU' => rand(1500000, 3000000),
                    'OK' => rand(2000000, 5000000),
                    default => rand(200000, 500000),
                },
                'facilities' => $facilities,
                'status' => 'occupied',
            ]);
        }
        $this->rooms = Room::all();
        $this->command->info("$count kamar dibuat (semua occupied).");
    }

    private function seedDrugs(int $count): void
    {
        $categories = ['Antibiotik', 'Analgesik', 'Antihistamin', 'Vitamin', 'Antasida', 'Antihipertensi', 'Antidiabetes', 'Antipiretik', 'Antiemetik', 'Vaksin', 'Infus', 'Anestesi'];
        $units = ['tablet', 'kapsul', 'ampul', 'vial', 'botol', 'strip', 'sachet', 'tube'];
        $drugNames = [
            'Paracetamol', 'Amoxicillin', 'Omeprazole', 'Ibuprofen', 'Cetirizine',
            'Amlodipine', 'Metformin', 'Ranitidine', 'Dexamethasone', 'Ondansetron',
            'Ceftriaxone', 'Azithromycin', 'Lansoprazole', 'Diclofenac', 'Loratadine',
            'Captopril', 'Glibenclamide', 'Antasida', 'Vitamin C', 'Vitamin B Complex',
            'Salbutamol', 'Prednisone', 'Furosemide', 'Clonidine', 'Simvastatin',
            'Allopurinol', 'Methylprednisolone', 'Ciprofloxacin', 'Cotrimoxazole', 'Domperidone',
        ];

        for ($i = 0; $i < $count; $i++) {
            $name = $drugNames[$i % count($drugNames)] . ' ' . rand(100, 500) . 'mg';
            Drug::create([
                'name' => $name,
                'category' => $categories[array_rand($categories)],
                'unit' => $units[array_rand($units)],
                'stock' => rand(10, 500),
                'price' => rand(3000, 500000),
                'is_active' => true,
            ]);
        }
        $this->drugs = Drug::all();
        $this->command->info("$count obat dibuat.");
    }

    private function seedAmbulances(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            Ambulance::create([
                'vehicle_number' => 'B ' . rand(1000, 9999) . ' AMB',
                'model' => collect(['Toyota Hiace', 'Isuzu Elf', 'Mercedes Sprinter', 'Ford Transit'])->random(),
                'type' => collect(['Basic', 'Advanced', 'ICU Mobile'])->random(),
                'status' => 'available',
                'driver_name' => $this->indonesianNames(1)[0],
                'driver_phone' => '0813' . rand(10000000, 99999999),
            ]);
        }
        $this->ambulances = Ambulance::all();
        $this->command->info("$count ambulans dibuat.");
    }

    private function seedPatients(int $count): void
    {
        $genders = ['male', 'female'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $names = $this->indonesianNames($count);

        $batch = [];
        $now = Carbon::now();

        for ($i = 0; $i < $count; $i++) {
            $gender = $genders[array_rand($genders)];
            $birthDate = Carbon::create(rand(1940, 2023), rand(1, 12), rand(1, 28));
            $registeredAt = Carbon::create(2024, rand(1, 12), rand(1, 28))->addHours(rand(6, 20))->addMinutes(rand(0, 59));

            $batch[] = [
                'name' => $names[$i],
                'email' => 'pasien' . ($i + 10000) . '@email.id',
                'phone' => '08' . str_pad(($i % 90) + 10, 2, '0', STR_PAD_LEFT) . str_pad($i + 10000000, 8, '0', STR_PAD_LEFT),
                'nik' => str_pad(rand(31, 35), 2, '0', STR_PAD_LEFT) . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT) . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT) . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'birth_date' => $birthDate,
                'gender' => $gender,
                'address' => 'Jl. ' . $this->randomStreet() . ' No. ' . rand(1, 300) . ', ' . collect(['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara', 'Tangerang', 'Bekasi', 'Depok', 'Bogor'])->random(),
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'emergency_contact_name' => $names[array_rand($names)],
                'emergency_contact_phone' => '08' . rand(10, 99) . rand(10000000, 99999999),
                'is_active' => true,
                'created_at' => $registeredAt,
                'updated_at' => $registeredAt,
            ];

            if (count($batch) >= 500) {
                Patient::insert($batch);
                $batch = [];
            }
        }
        if (count($batch) > 0) Patient::insert($batch);

        $this->command->info(number_format($count) . ' pasien dibuat.');
    }

    private function seedTwoYearsData(): void
    {
        $patients = Patient::all();
        $doctors = $this->doctors;
        $rooms = $this->rooms;
        $drugs = $this->drugs;
        $ambulances = $this->ambulances;
        $polys = $this->polyclinics;

        $startDate = Carbon::now()->subYears(2)->startOfDay();
        $endDate = Carbon::now()->startOfDay();

        $batchAppt = []; $batchPay = []; $batchMR = []; $batchEm = [];
        $apptId = 1; $payId = 1;

        $this->command->info('Membuat data harian 2 tahun...');

        // Disable FK checks for bulk insert performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $totalDays = $startDate->diffInDays($endDate);
        $dayCounter = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayCounter++;
            if ($dayCounter % 100 === 0) {
                $this->command->info("  Progress: {$dayCounter}/{$totalDays} hari...");
            }

            $isWeekend = $date->isWeekend();
            $dayOfWeek = $date->dayOfWeek;

            // 40-70 appointment per hari kerja, 15-30 weekend
            $baseAppt = $isWeekend ? rand(15, 30) : rand(40, 70);
            // Hari Senin lebih ramai
            if ($dayOfWeek === 1) $baseAppt += 15;
            // Akhir bulan lebih ramai
            if ($date->day >= 25) $baseAppt += 10;

            for ($a = 0; $a < $baseAppt; $a++) {
                $patient = $patients->random();
                $doctor = $doctors->random();
                $hour = rand(7, 20);
                $minute = rand(0, 3) * 15;
                $apptDate = $date->copy()->setTime($hour, $minute);

                $statuses = ['completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'completed', 'confirmed', 'scheduled', 'cancelled'];
                $status = $statuses[array_rand($statuses)];

                $batchAppt[] = [
                    'id' => $apptId,
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'treatment_id' => null,
                    'appointment_date' => $apptDate,
                    'start_time' => $apptDate->format('H:i'),
                    'end_time' => $apptDate->copy()->addMinutes(rand(15, 90))->format('H:i'),
                    'status' => $status,
                    'complaint' => $this->randomComplaint(),
                    'created_at' => $apptDate->copy()->subDays(rand(0, 3)),
                    'updated_at' => $apptDate,
                ];

                // Payment untuk appt completed (60% chance)
                if ($status === 'completed' && rand(1, 100) <= 60) {
                    $amount = rand(150000, 2000000);
                    $batchPay[] = [
                        'id' => $payId,
                        'patient_id' => $patient->id,
                        'appointment_id' => $apptId,
                        'invoice_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad($payId, 6, '0', STR_PAD_LEFT),
                        'subtotal' => $amount,
                        'amount' => $amount,
                        'payment_method' => collect(['cash', 'transfer', 'card', 'insurance', 'other'])->random(),
                        'status' => 'completed',
                        'created_at' => $apptDate,
                        'updated_at' => $apptDate,
                    ];
                    $payId++;

                    // Medical Record (30% of completed)
                    if (rand(1, 100) <= 30) {
                        $batchMR[] = [
                            'patient_id' => $patient->id,
                            'doctor_id' => $doctor->id,
                            'appointment_id' => $apptId,
                            'diagnosis' => $this->randomDiagnosis(),
                            'action' => $this->randomTreatment(),
                            'medicine' => $this->randomPrescription(),
                            'created_at' => $apptDate,
                            'updated_at' => $apptDate,
                        ];
                    }
                }

                $apptId++;
            }

            // Emergency 2-8 per hari
            $emergencyCount = rand(2, 8);
            for ($e = 0; $e < $emergencyCount; $e++) {
                $patient = $patients->random();
                $doctor = $doctors->random();
                $emDate = $date->copy()->setTime(rand(0, 23), rand(0, 59));

                $batchEm[] = [
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'triage' => collect(['red', 'yellow', 'green'])->random(),
                    'arrival_mode' => collect(['ambulance', 'self', 'referral', 'police'])->random(),
                    'complaint' => $this->randomComplaint(),
                    'diagnosis' => $this->randomDiagnosis(),
                    'status' => collect(['discharged', 'discharged', 'discharged', 'in_treatment', 'referred', 'observation'])->random(),
                    'created_at' => $emDate,
                    'updated_at' => $emDate,
                ];
            }

            // Ambulance calls 1-4 per hari
            for ($ac = 0; $ac < rand(1, 4); $ac++) {
                $acDate = $date->copy()->setTime(rand(0, 23), rand(0, 59));
                AmbulanceCall::create([
                    'ambulance_id' => $ambulances->random()->id,
                    'patient_name' => $patients->random()->name,
                    'pickup_location' => 'Jl. ' . $this->randomStreet() . ', ' . collect(['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Tangerang'])->random(),
                    'destination' => 'Rumah Sakit ' . config('app.name', 'Hospital'),
                    'call_date' => $acDate,
                    'status' => collect(['completed', 'completed', 'completed', 'completed', 'completed', 'dispatched'])->random(),
                    'created_at' => $acDate,
                    'updated_at' => $acDate,
                ]);
            }

            // Queue 5-15 per hari per poli
            for ($q = 0; $q < rand(5, 15); $q++) {
                $qDate = $date->copy()->setTime(rand(6, 16), rand(0, 59));
                Queue::create([
                    'polyclinic_id' => $polys->random()->id,
                    'patient_id' => $patients->random()->id,
                    'doctor_id' => $doctors->random()->id,
                    'queue_number' => str_pad($q + 1, 3, '0', STR_PAD_LEFT),
                    'status' => collect(['waiting', 'waiting', 'called', 'called', 'completed'])->random(),
                    'created_at' => $qDate,
                    'updated_at' => $qDate,
                ]);
            }

            // Flush batch setiap 500
            if (count($batchAppt) >= 500) { Appointment::insert($batchAppt); $batchAppt = []; }
            if (count($batchPay) >= 500) { Payment::insert($batchPay); $batchPay = []; }
            if (count($batchMR) >= 500) { MedicalRecord::insert($batchMR); $batchMR = []; }
            if (count($batchEm) >= 500) { Emergency::insert($batchEm); $batchEm = []; }
        }

        // Insert remaining
        if (count($batchAppt) > 0) Appointment::insert($batchAppt);
        if (count($batchPay) > 0) Payment::insert($batchPay);
        if (count($batchMR) > 0) MedicalRecord::insert($batchMR);
        if (count($batchEm) > 0) Emergency::insert($batchEm);

        $this->command->info("Data 2 tahun selesai: " . number_format($apptId - 1) . " appointment.");

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function indonesianNames(int $count): array
    {
        $firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hadi', 'Indah', 'Joko',
            'Kartika', 'Lestari', 'Mega', 'Novi', 'Putra', 'Rina', 'Sari', 'Tono', 'Umar', 'Vina',
            'Wahyu', 'Yanto', 'Zahra', 'Arif', 'Bambang', 'Cindy', 'Dimas', 'Endah', 'Fajar', 'Galih',
            'Hendra', 'Intan', 'Johan', 'Kiki', 'Lia', 'Maman', 'Nina', 'Oscar', 'Puji', 'Qory',
            'Ratna', 'Slamet', 'Tina', 'Untung', 'Vera', 'Wawan', 'Yuli', 'Zainal', 'Bagus', 'Cahya',
            'Dina', 'Erwin', 'Fani', 'Gilang', 'Hasan', 'Irma', 'Jaya', 'Kiki', 'Laras', 'Mira',
            'Nanda', 'Olivia', 'Pras', 'Rama', 'Siska', 'Teguh', 'Ulfa', 'Vito', 'Winda', 'Yoga',
            'Agus', 'Bayu', 'Cici', 'Dodi', 'Ella', 'Firman', 'Gita', 'Hanif', 'Ika', 'Jamal'];
        $lastNames = ['Santoso', 'Wijaya', 'Pratama', 'Kusuma', 'Hidayat', 'Saputra', 'Purnama', 'Nugroho',
            'Wibowo', 'Hermawan', 'Gunawan', 'Setiawan', 'Hartono', 'Susanto', 'Utama', 'Mahendra',
            'Pangestu', 'Ramadhan', 'Fauzi', 'Rahman'];

        $names = [];
        for ($i = 0; $i < $count; $i++) {
            $names[] = $firstNames[$i % count($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
        }
        return $names;
    }

    private function randomStreet(): string
    {
        return collect(['Sudirman', 'Thamrin', 'Gatot Subroto', 'Rasuna Said', 'Mampang', 'Tebet', 'Pondok Indah', 'Kemang', 'Cipete', 'Fatmawati', 'Kebayoran', 'Senayan', 'Slipi', 'Grogol', 'Tomang', 'Hayam Wuruk', 'Gajah Mada', 'Mangga Besar', 'Pangeran Jayakarta', 'Boulevard'])->random();
    }

    private function randomComplaint(): string
    {
        return collect([
            'Demam tinggi sejak 3 hari', 'Batuk berdahak', 'Sakit kepala berkepanjangan',
            'Nyeri perut bagian kanan', 'Sesak napas', 'Nyeri dada', 'Mual dan muntah',
            'Diare sejak kemarin', 'Nyeri sendi', 'Gatal-gatal di kulit',
            'Pusing berputar', 'Mata merah dan bengkak', 'Sakit gigi', 'Sariawan parah',
            'Nyeri punggung bawah', 'Kesemutan di kaki', 'Lemas dan lesu',
            'Benjolan di leher', 'Batuk berdarah', 'Nyeri saat BAK',
            'Haemorrhoid / wasir', 'Susah tidur', 'Cemas berlebihan', 'Alergi',
            'Telinga berdenging', 'Hidung tersumbat', 'Sakit tenggorokan',
            'Nyeri otot setelah olahraga', 'Luka tidak sembuh', 'Pegal seluruh badan',
        ])->random();
    }

    private function randomDiagnosis(): string
    {
        return collect([
            'ISPA (Infeksi Saluran Pernapasan Akut)', 'Hipertensi esensial', 'Diabetes melitus tipe 2',
            'Dyspepsia fungsional', 'Gastritis akut', 'Migraine', 'Cervical syndrome',
            'Low back pain', 'Allergic rhinitis', 'Dermatitis kontak',
            'Bronkitis akut', 'Otitis media', 'Faringitis akut',
            'Gastroenteritis akut', 'Infeksi saluran kemih', 'Tonsilitis',
            'Anemia', 'Vertigo perifer', 'Conjunctivitis', 'Osteoarthritis',
            'Asma bronkial', 'Penyakit jantung koroner', 'Stroke iskemik',
            'Pneumonia', 'Appendisitis', 'Cholecystitis', 'Hepatitis A',
            'Tuberkulosis paru', 'HIV/AIDS', 'Leukemia',
        ])->random();
    }

    private function randomTreatment(): string
    {
        return collect([
            'Pemberian obat oral dan observasi 3 hari', 'Terapi cairan infus dan antibiotik IV',
            'Rujuk ke spesialis untuk evaluasi lanjutan', 'Fisioterapi 2x seminggu',
            'Edukasi pasien tentang pola makan', 'Kontrol tekanan darah rutin',
            'Pemberian resep dan kontrol 1 minggu', 'Irigasi luka dan balutan steril',
            'Nebulizer dan oksigenasi', 'Injeksi insulin sliding scale',
            'Konseling gizi dan diet', 'Imobilisasi dan analgesik',
        ])->random();
    }

    private function randomPrescription(): string
    {
        return collect([
            '- Paracetamol 500mg 3x1 (PC)\n- Amoxicillin 500mg 3x1',
            '- Omeprazole 20mg 1x1 (AC)\n- Antasida 3x1',
            '- Amlodipine 5mg 1x1\n- Candesartan 8mg 1x1',
            '- Metformin 500mg 2x1 (PC)\n- Glibenclamide 5mg 1x1 (AC)',
            '- Cetirizine 10mg 1x1\n- Dexamethasone 0.5mg 3x1',
            '- Ibuprofen 400mg 3x1 (PC)\n- Vitamin B Complex 1x1',
        ])->random();
    }
}
