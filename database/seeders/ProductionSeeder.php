<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Department;
use App\Models\Drug;
use App\Models\Polyclinic;
use App\Models\Room;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk PRODUKSI — hanya master data essential.
 * TIDAK mengisi data demo (pasien, appointment, dst.).
 *
 * Jalankan: php artisan db:seed --class=ProductionSeeder
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedDepartments();
        $this->seedPolyclinics();
        $this->seedRooms();
        $this->seedTreatments();
        $this->seedDrugs();
        $this->seedChartOfAccounts();

        $this->command->info('✅ Production master data berhasil di-seed.');
        $this->command->warn('⚠️  Segera ganti password admin: admin@hospital.test / password');
    }

    private function seedUsers(): void
    {
        $users = [
            ['name' => 'Admin Rumah Sakit', 'username' => 'admin',     'email' => 'admin@hospital.test',      'role' => 'admin'],
            ['name' => 'Direktur',          'username' => 'director',  'email' => 'director@hospital.test',   'role' => 'director'],
            ['name' => 'IT Support',        'username' => 'it.support','email' => 'it.support@hospital.test', 'role' => 'IT'],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(
                ['username' => $u['username']],
                ['name' => $u['name'], 'email' => $u['email'], 'password' => Hash::make('password'), 'role' => $u['role']]
            );
        }
    }

    private function seedDepartments(): void
    {
        $list = [
            ['code' => 'ADM', 'name' => 'Administrasi'],
            ['code' => 'MED', 'name' => 'Medis'],
            ['code' => 'NUR', 'name' => 'Keperawatan'],
            ['code' => 'FAR', 'name' => 'Farmasi'],
            ['code' => 'FIN', 'name' => 'Keuangan'],
            ['code' => 'HRD', 'name' => 'SDM'],
            ['code' => 'IT',  'name' => 'IT'],
            ['code' => 'MNG', 'name' => 'Manajemen'],
        ];
        foreach ($list as $d) {
            Department::updateOrCreate(['code' => $d['code']], $d);
        }
    }

    private function seedPolyclinics(): void
    {
        $list = [
            ['code' => 'UMUM',    'name' => 'Poli Umum',          'is_active' => true],
            ['code' => 'GIGI',    'name' => 'Poli Gigi',          'is_active' => true],
            ['code' => 'ANAK',    'name' => 'Poli Anak',          'is_active' => true],
            ['code' => 'JANTUNG', 'name' => 'Poli Jantung',       'is_active' => true],
            ['code' => 'BEDAH',   'name' => 'Poli Bedah',         'is_active' => true],
            ['code' => 'OBGYN',   'name' => 'Poli Obgyn',         'is_active' => true],
            ['code' => 'MATA',    'name' => 'Poli Mata',          'is_active' => true],
            ['code' => 'THT',     'name' => 'Poli THT',           'is_active' => true],
            ['code' => 'KULIT',   'name' => 'Poli Kulit Kelamin', 'is_active' => true],
            ['code' => 'SARAF',   'name' => 'Poli Saraf',         'is_active' => true],
            ['code' => 'JIWA',    'name' => 'Poli Psikiatri',     'is_active' => true],
            ['code' => 'FISIO',   'name' => 'Poli Fisioterapi',   'is_active' => true],
        ];
        foreach ($list as $p) {
            Polyclinic::updateOrCreate(['code' => $p['code']], $p);
        }
    }

    private function seedRooms(): void
    {
        $rooms = [
            ['room_number' => 'VIP-01',  'room_type' => 'VIP',     'bed_count' => 1, 'price_per_day' => 1000000, 'status' => 'available'],
            ['room_number' => 'K1-01',   'room_type' => 'Kelas 1', 'bed_count' => 2, 'price_per_day' => 500000,  'status' => 'available'],
            ['room_number' => 'K2-01',   'room_type' => 'Kelas 2', 'bed_count' => 4, 'price_per_day' => 300000,  'status' => 'available'],
            ['room_number' => 'K3-01',   'room_type' => 'Kelas 3', 'bed_count' => 6, 'price_per_day' => 150000,  'status' => 'available'],
            ['room_number' => 'ICU-01',  'room_type' => 'ICU',     'bed_count' => 1, 'price_per_day' => 1500000, 'status' => 'available'],
            ['room_number' => 'NICU-01', 'room_type' => 'NICU',    'bed_count' => 1, 'price_per_day' => 1800000, 'status' => 'available'],
            ['room_number' => 'OK-01',   'room_type' => 'OK',      'bed_count' => 1, 'price_per_day' => 0,       'status' => 'available'],
        ];
        foreach ($rooms as $r) {
            Room::updateOrCreate(['room_number' => $r['room_number']], $r);
        }
    }

    private function seedTreatments(): void
    {
        $list = [
            ['name' => 'Konsultasi Dokter Umum',     'category' => 'konsultasi', 'price' => 50000,  'duration_minutes' => 15, 'is_active' => true],
            ['name' => 'Konsultasi Dokter Spesialis','category' => 'konsultasi', 'price' => 200000, 'duration_minutes' => 20, 'is_active' => true],
            ['name' => 'Cek Tekanan Darah',          'category' => 'pemeriksaan','price' => 20000,  'duration_minutes' => 5,  'is_active' => true],
            ['name' => 'EKG',                        'category' => 'pemeriksaan','price' => 150000, 'duration_minutes' => 15, 'is_active' => true],
            ['name' => 'Imunisasi BCG',              'category' => 'imunisasi',  'price' => 100000, 'duration_minutes' => 10, 'is_active' => true],
            ['name' => 'Suntik Vitamin',             'category' => 'tindakan',   'price' => 75000,  'duration_minutes' => 10, 'is_active' => true],
            ['name' => 'Cabut Gigi',                 'category' => 'tindakan',   'price' => 250000, 'duration_minutes' => 30, 'is_active' => true],
            ['name' => 'Tambal Gigi',                'category' => 'tindakan',   'price' => 200000, 'duration_minutes' => 45, 'is_active' => true],
        ];
        foreach ($list as $t) {
            Treatment::updateOrCreate(['name' => $t['name']], $t);
        }
    }

    private function seedDrugs(): void
    {
        // Drug tabel tidak punya 'code', pakai 'name' sebagai unique key.
        $list = [
            ['name' => 'Paracetamol 500mg',  'category' => 'analgesik',   'unit' => 'tablet',  'stock' => 1000, 'price' => 500],
            ['name' => 'Amoxicillin 500mg',  'category' => 'antibiotik',  'unit' => 'kapsul',  'stock' => 500,  'price' => 1500],
            ['name' => 'Ibuprofen 400mg',    'category' => 'analgesik',   'unit' => 'tablet',  'stock' => 800,  'price' => 1000],
            ['name' => 'Antasida',           'category' => 'lambung',     'unit' => 'tablet',  'stock' => 600,  'price' => 800],
            ['name' => 'CTM',                'category' => 'antihistamin','unit' => 'tablet',  'stock' => 500,  'price' => 300],
            ['name' => 'Vitamin B Complex',  'category' => 'vitamin',     'unit' => 'tablet',  'stock' => 1000, 'price' => 600],
            ['name' => 'OBH Sirup 100ml',    'category' => 'batuk',       'unit' => 'botol',   'stock' => 200,  'price' => 12000],
            ['name' => 'Salbutamol Inhaler', 'category' => 'asma',        'unit' => 'inhaler', 'stock' => 100,  'price' => 85000],
        ];
        foreach ($list as $d) {
            Drug::updateOrCreate(['name' => $d['name']], $d);
        }
    }

    private function seedChartOfAccounts(): void
    {
        // Schema kolom: account_code, account_name, account_type, normal_balance
        $accounts = [
            // Aset (1xxx)
            ['account_code' => '1000', 'account_name' => 'ASET',                          'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1100', 'account_name' => 'Kas',                           'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1110', 'account_name' => 'Kas di Tangan',                 'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1120', 'account_name' => 'Bank',                          'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1200', 'account_name' => 'Piutang Pasien',                'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1210', 'account_name' => 'Piutang BPJS',                  'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1220', 'account_name' => 'Piutang Asuransi Swasta',       'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1300', 'account_name' => 'Persediaan Obat',               'account_type' => 'asset',     'normal_balance' => 'debit'],
            ['account_code' => '1400', 'account_name' => 'Aset Tetap',                    'account_type' => 'asset',     'normal_balance' => 'debit'],
            // Kewajiban (2xxx)
            ['account_code' => '2000', 'account_name' => 'KEWAJIBAN',                     'account_type' => 'liability', 'normal_balance' => 'credit'],
            ['account_code' => '2100', 'account_name' => 'Utang Usaha',                   'account_type' => 'liability', 'normal_balance' => 'credit'],
            ['account_code' => '2200', 'account_name' => 'Utang Gaji',                    'account_type' => 'liability', 'normal_balance' => 'credit'],
            // Ekuitas (3xxx)
            ['account_code' => '3000', 'account_name' => 'EKUITAS',                       'account_type' => 'equity',    'normal_balance' => 'credit'],
            ['account_code' => '3100', 'account_name' => 'Modal Pemilik',                 'account_type' => 'equity',    'normal_balance' => 'credit'],
            ['account_code' => '3200', 'account_name' => 'Laba Ditahan',                  'account_type' => 'equity',    'normal_balance' => 'credit'],
            // Pendapatan (4xxx)
            ['account_code' => '4000', 'account_name' => 'PENDAPATAN',                    'account_type' => 'revenue',   'normal_balance' => 'credit'],
            ['account_code' => '4100', 'account_name' => 'Pendapatan Konsultasi',         'account_type' => 'revenue',   'normal_balance' => 'credit'],
            ['account_code' => '4200', 'account_name' => 'Pendapatan Tindakan Medis',     'account_type' => 'revenue',   'normal_balance' => 'credit'],
            ['account_code' => '4300', 'account_name' => 'Pendapatan Penjualan Obat',     'account_type' => 'revenue',   'normal_balance' => 'credit'],
            ['account_code' => '4400', 'account_name' => 'Pendapatan Rawat Inap',         'account_type' => 'revenue',   'normal_balance' => 'credit'],
            // Beban (5xxx)
            ['account_code' => '5000', 'account_name' => 'BEBAN',                         'account_type' => 'expense',   'normal_balance' => 'debit'],
            ['account_code' => '5100', 'account_name' => 'Beban Gaji Karyawan',           'account_type' => 'expense',   'normal_balance' => 'debit'],
            ['account_code' => '5200', 'account_name' => 'Beban Listrik & Air',           'account_type' => 'expense',   'normal_balance' => 'debit'],
            ['account_code' => '5300', 'account_name' => 'Beban Pembelian Obat',          'account_type' => 'expense',   'normal_balance' => 'debit'],
            ['account_code' => '5400', 'account_name' => 'Beban Peralatan Medis',         'account_type' => 'expense',   'normal_balance' => 'debit'],
        ];
        foreach ($accounts as $a) {
            ChartOfAccount::updateOrCreate(['account_code' => $a['account_code']], $a);
        }
    }
}
