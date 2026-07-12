<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Emergency;
use App\Models\HospitalBed;
use App\Models\InsuranceClaim;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\PatientFeedback;
use App\Models\Polyclinic;
use App\Models\Radiology;
use App\Models\Referral;
use App\Models\Room;
use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::inRandomOrder()->limit(60)->pluck('id')->all();
        $doctors = Doctor::inRandomOrder()->limit(20)->pluck('id')->all();
        $polys = Polyclinic::pluck('id')->all();

        if (empty($patients) || empty($doctors)) {
            $this->command->warn('Butuh data pasien & dokter dulu. Lewati.');

            return;
        }

        $this->seedClaims($patients);
        $this->seedReferrals($patients, $doctors, $polys);
        $this->seedLabRadiology($patients, $doctors);
        $this->seedEmergencies($patients, $doctors);
        $this->seedBeds();
        $this->seedSchedules();
        $this->seedFeedback($patients);

        $this->command->info('Dashboard demo data seeded.');
    }

    protected function seedClaims(array $patients): void
    {
        if (InsuranceClaim::count() > 0) {
            return;
        }
        $statuses = ['draft', 'submitted', 'submitted', 'approved', 'approved', 'rejected', 'paid'];
        $providers = ['BPJS Kesehatan', 'BPJS Kesehatan', 'Mandiri Inhealth', 'Prudential', 'AXA Mandiri', 'Allianz'];
        foreach (range(1, 40) as $i) {
            $claimed = rand(200, 5000) * 1000;
            $status = $statuses[array_rand($statuses)];
            InsuranceClaim::create([
                'claim_no' => 'CLM-'.now()->format('Ym').'-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'patient_id' => $patients[array_rand($patients)],
                'insurance_provider' => $providers[array_rand($providers)],
                'policy_number' => 'POL'.rand(1000000, 9999999),
                'claim_type' => ['outpatient', 'inpatient', 'emergency', 'maternity'][array_rand([0, 1, 2, 3])],
                'service_date' => now()->subDays(rand(1, 40)),
                'claim_date' => now()->subDays(rand(0, 20)),
                'diagnosis_code' => 'A'.rand(10, 99).'.'.rand(0, 9),
                'diagnosis_text' => 'Diagnosis demo',
                'claimed_amount' => $claimed,
                'approved_amount' => in_array($status, ['approved', 'paid']) ? $claimed * 0.9 : 0,
                'status' => $status,
            ]);
        }
    }

    protected function seedReferrals(array $patients, array $doctors, array $polys): void
    {
        if (Referral::count() > 0 || count($polys) < 2) {
            return;
        }
        $statuses = ['pending', 'pending', 'approved', 'completed', 'completed'];
        foreach (range(1, 25) as $i) {
            Referral::create([
                'patient_id' => $patients[array_rand($patients)],
                'from_polyclinic_id' => $polys[array_rand($polys)],
                'to_polyclinic_id' => $polys[array_rand($polys)],
                'doctor_id' => $doctors[array_rand($doctors)],
                'reason' => 'Rujukan spesialis lanjutan',
                'diagnosis' => 'Perlu penanganan lebih lanjut',
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(rand(0, 14)),
            ]);
        }
    }

    protected function seedLabRadiology(array $patients, array $doctors): void
    {
        if (LabTest::count() === 0) {
            $tests = ['Darah Lengkap', 'Gula Darah', 'Fungsi Hati', 'Fungsi Ginjal', 'Urinalisis', 'Kolesterol'];
            $statuses = ['requested', 'sample_collected', 'in_progress', 'completed', 'completed', 'completed'];
            foreach (range(1, 50) as $i) {
                $status = $statuses[array_rand($statuses)];
                $created = now()->subHours(rand(1, 72));
                LabTest::create([
                    'patient_id' => $patients[array_rand($patients)],
                    'doctor_id' => $doctors[array_rand($doctors)],
                    'test_name' => $tests[array_rand($tests)],
                    'test_type' => 'hematologi',
                    'sample_type' => 'darah',
                    'status' => $status,
                    'results' => $status === 'completed' ? 'Dalam batas normal' : null,
                    'result_date' => $status === 'completed' ? (clone $created)->addHours(rand(2, 24)) : null,
                    'created_at' => $created,
                ]);
            }
        }

        if (Radiology::count() === 0) {
            $exams = ['Rontgen Thorax', 'CT Scan Kepala', 'USG Abdomen', 'MRI Lutut', 'Rontgen Femur'];
            $statuses = ['requested', 'in_progress', 'completed', 'completed'];
            foreach (range(1, 30) as $i) {
                $status = $statuses[array_rand($statuses)];
                Radiology::create([
                    'patient_id' => $patients[array_rand($patients)],
                    'doctor_id' => $doctors[array_rand($doctors)],
                    'examination_name' => $exams[array_rand($exams)],
                    'body_part' => 'thorax',
                    'status' => $status,
                    'findings' => $status === 'completed' ? 'Tidak ada kelainan' : null,
                    'created_at' => now()->subHours(rand(1, 72)),
                ]);
            }
        }
    }

    protected function seedEmergencies(array $patients, array $doctors): void
    {
        $triages = ['red', 'yellow', 'yellow', 'green', 'green', 'green'];
        $active = ['waiting', 'in_treatment', 'observation'];
        foreach (range(1, 12) as $i) {
            Emergency::create([
                'patient_id' => $patients[array_rand($patients)],
                'doctor_id' => $doctors[array_rand($doctors)],
                'triage' => $triages[array_rand($triages)],
                'arrival_mode' => ['ambulance', 'walk_in', 'referral'][array_rand([0, 1, 2])],
                'complaint' => ['Nyeri dada', 'Sesak napas', 'Kecelakaan', 'Demam tinggi', 'Patah tulang'][array_rand([0, 1, 2, 3, 4])],
                'status' => $active[array_rand($active)],
                'created_at' => now()->subMinutes(rand(5, 300)),
            ]);
        }
    }

    protected function seedBeds(): void
    {
        if (HospitalBed::count() > 0) {
            return;
        }
        $rooms = Room::limit(15)->get();
        $statuses = ['available', 'available', 'occupied', 'occupied', 'occupied', 'cleaning', 'reserved', 'maintenance'];
        foreach ($rooms as $room) {
            foreach (range(1, rand(2, 4)) as $b) {
                $status = $statuses[array_rand($statuses)];
                HospitalBed::create([
                    'room_id' => $room->id,
                    'bed_code' => $room->id.'-'.chr(64 + $b),
                    'label' => 'Bed '.chr(64 + $b),
                    'status' => $status,
                    'occupied_since' => $status === 'occupied' ? now()->subHours(rand(2, 96)) : null,
                ]);
            }
        }
    }

    protected function seedSchedules(): void
    {
        $users = User::whereIn('role', ['doctor', 'nurse', 'midwife'])->inRandomOrder()->limit(20)->get();
        $now = now();
        foreach ($users as $u) {
            StaffSchedule::create([
                'user_id' => $u->id,
                'shift_date' => today(),
                'start_time' => '07:00',
                'end_time' => '21:00',
                'department' => ['IGD', 'Rawat Inap', 'Poliklinik', 'ICU'][array_rand([0, 1, 2, 3])],
                'shift_type' => 'morning',
            ]);
        }
    }

    protected function seedFeedback(array $patients): void
    {
        if (PatientFeedback::count() > 0) {
            return;
        }
        foreach (range(1, 40) as $i) {
            $overall = rand(3, 5);
            PatientFeedback::create([
                'patient_id' => $patients[array_rand($patients)],
                'visit_date' => now()->subDays(rand(0, 28)),
                'service_type' => ['Rawat Jalan', 'Rawat Inap', 'IGD'][array_rand([0, 1, 2])],
                'rating_overall' => $overall,
                'rating_doctor' => rand(3, 5),
                'rating_nurse' => rand(3, 5),
                'rating_facility' => rand(3, 5),
                'rating_cleanliness' => rand(3, 5),
                'rating_speed' => rand(2, 5),
                'would_recommend' => $overall >= 4,
                'positive' => 'Pelayanan baik',
                'status' => 'new',
                'created_at' => now()->subDays(rand(0, 28)),
            ]);
        }
    }
}
