<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $treatments = [
            [
                'name' => 'Pemeriksaan Umum',
                'description' => 'Pemeriksaan kesehatan umum meliputi pemeriksaan fisik dasar, tekanan darah, denyut nadi, suhu tubuh, dan konsultasi awal dengan dokter.',
                'category' => 'Pemeriksaan',
                'price' => 150000,
                'duration_minutes' => 30,
                'requirements' => [],
            ],
            [
                'name' => 'Konsultasi Spesialis Jantung',
                'description' => 'Konsultasi dengan dokter spesialis jantung untuk diagnosis dan penanganan masalah kardiovaskular.',
                'category' => 'Konsultasi',
                'price' => 350000,
                'duration_minutes' => 45,
                'requirements' => ['Bawa hasil rekam medis sebelumnya', 'Bawa rujukan dari dokter umum'],
            ],
            [
                'name' => 'Konsultasi Spesialis Saraf',
                'description' => 'Konsultasi dengan dokter spesialis saraf untuk gangguan neurologis seperti sakit kepala kronis, stroke, dan gangguan saraf lainnya.',
                'category' => 'Konsultasi',
                'price' => 350000,
                'duration_minutes' => 45,
                'requirements' => ['Bawa hasil CT Scan/MRI jika ada', 'Bawa rujukan'],
            ],
            [
                'name' => 'Cek Darah Lengkap',
                'description' => 'Pemeriksaan laboratorium darah lengkap meliputi hemoglobin, leukosit, trombosit, hematokrit, dan hitung jenis sel darah.',
                'category' => 'Laboratorium',
                'price' => 120000,
                'duration_minutes' => 15,
                'requirements' => ['Puasa 8-10 jam sebelum pengambilan darah'],
            ],
            [
                'name' => 'Cek Urine',
                'description' => 'Pemeriksaan laboratorium urine untuk mendeteksi infeksi saluran kemih, gangguan ginjal, dan kondisi metabolik.',
                'category' => 'Laboratorium',
                'price' => 75000,
                'duration_minutes' => 15,
                'requirements' => ['Tampung urine pagi hari', 'Jangan buang urine pertama'],
            ],
            [
                'name' => 'Rontgen Dada',
                'description' => 'Pemeriksaan radiologi menggunakan sinar-X untuk melihat kondisi paru-paru, jantung, dan tulang dada.',
                'category' => 'Radiologi',
                'price' => 200000,
                'duration_minutes' => 20,
                'requirements' => ['Lepaskan aksesoris logam', 'Gunakan baju khusus'],
            ],
            [
                'name' => 'USG Abdomen',
                'description' => 'Pemeriksaan ultrasonografi pada organ perut seperti hati, kandung empedu, pankreas, ginjal, dan limpa.',
                'category' => 'Radiologi',
                'price' => 350000,
                'duration_minutes' => 30,
                'requirements' => ['Puasa 6-8 jam sebelum pemeriksaan'],
            ],
            [
                'name' => 'Operasi Katarak',
                'description' => 'Tindakan operasi pengangkatan lensa mata yang keruh (katarak) dan penggantian dengan lensa intraokular buatan.',
                'category' => 'Operasi',
                'price' => 5000000,
                'duration_minutes' => 60,
                'requirements' => ['Konsultasi pra-operasi', 'Cek kesehatan lengkap', 'Puasa 8 jam sebelum operasi'],
            ],
            [
                'name' => 'Operasi Apendisitis',
                'description' => 'Tindakan operasi pengangkatan usus buntu (apendiks) yang meradang, dilakukan secara laparoskopi atau terbuka.',
                'category' => 'Operasi',
                'price' => 8000000,
                'duration_minutes' => 90,
                'requirements' => ['Puasa total 8 jam', 'Konsultasi dokter bedah', 'Cek darah lengkap', 'EKG'],
            ],
            [
                'name' => 'Cuci Darah (Hemodialisis)',
                'description' => 'Proses terapi pengganti ginjal untuk membersihkan darah dari zat-zat sisa metabolisme menggunakan mesin dialisis.',
                'category' => 'Terapi',
                'price' => 2000000,
                'duration_minutes' => 240,
                'requirements' => ['Datang 30 menit lebih awal', 'Timbang berat badan', 'Cek tekanan darah'],
            ],
            [
                'name' => 'Fisioterapi',
                'description' => 'Terapi fisik untuk memulihkan fungsi gerak, mengurangi nyeri, dan memperkuat otot melalui latihan dan modalitas fisioterapi.',
                'category' => 'Terapi',
                'price' => 150000,
                'duration_minutes' => 45,
                'requirements' => ['Bawa pakaian olahraga', 'Bawa rujukan/rekam medis'],
            ],
            [
                'name' => 'Vaksinasi',
                'description' => 'Pemberian vaksin untuk mencegah penyakit infeksi. Tersedia berbagai jenis vaksin sesuai kebutuhan.',
                'category' => 'Tindakan',
                'price' => 250000,
                'duration_minutes' => 15,
                'requirements' => ['Dalam kondisi sehat', 'Bawa buku vaksinasi jika ada'],
            ],
            [
                'name' => 'Perawatan Luka',
                'description' => 'Tindakan perawatan luka meliputi pembersihan luka, penggantian balutan, dan pemberian obat topikal untuk mempercepat penyembuhan.',
                'category' => 'Tindakan',
                'price' => 100000,
                'duration_minutes' => 20,
                'requirements' => [],
            ],
            [
                'name' => 'Rawat Inap Kelas 1',
                'description' => 'Layanan rawat inap dengan fasilitas kamar kelas 1 (kapasitas 2 tempat tidur), termasuk perawatan medis, makan 3 kali, dan obat-obatan dasar.',
                'category' => 'Rawat Inap',
                'price' => 500000,
                'duration_minutes' => 1440,
                'requirements' => ['Rujukan rawat inap', 'Identitas diri', 'Deposit awal'],
            ],
            [
                'name' => 'Rawat Inap Kelas 3',
                'description' => 'Layanan rawat inap dengan fasilitas kamar kelas 3 (kapasitas 6-8 tempat tidur), termasuk perawatan medis dan obat-obatan dasar.',
                'category' => 'Rawat Inap',
                'price' => 150000,
                'duration_minutes' => 1440,
                'requirements' => ['Rujukan rawat inap', 'Identitas diri', 'Deposit awal'],
            ],
        ];

        foreach ($treatments as $treatment) {
            $treatment['slug'] = Str::slug($treatment['name']);
            Treatment::create($treatment);
        }
    }
}
