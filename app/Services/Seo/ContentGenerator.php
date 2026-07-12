<?php

namespace App\Services\Seo;

use App\Support\SeoData;

class ContentGenerator
{
    /** Generate FAQ items (question/answer) for a given context. */
    public function faqs(string $context, array $vars = []): array
    {
        $name = $vars['name'] ?? $context;
        $city = $vars['city'] ?? null;

        $faqs = [
            [
                'q' => "Apa itu {$name}?",
                'a' => "{$name} adalah solusi sistem informasi manajemen rumah sakit (SIMRS) terintegrasi yang mencakup rekam medis elektronik, pendaftaran & antrian, farmasi, laboratorium, radiologi, kasir, keuangan, hingga integrasi BPJS dan SatuSehat Kemenkes.",
            ],
            [
                'q' => 'Apakah aplikasi ini sudah terintegrasi BPJS dan SatuSehat?',
                'a' => 'Ya. Sistem mendukung integrasi BPJS (VClaim/Antrean) serta SatuSehat sehingga pelaporan data pasien ke platform nasional dapat berjalan otomatis dan sesuai regulasi Kementerian Kesehatan.',
            ],
            [
                'q' => 'Apakah source code disertakan dan bisa di-whitelabel?',
                'a' => 'Source code lengkap disertakan. Anda dapat melakukan rebranding (logo, nama, warna), self-host di server sendiri, dan memodifikasi modul sesuai kebutuhan fasilitas kesehatan Anda.',
            ],
            [
                'q' => $city
                    ? "Apakah aplikasi bisa dipakai fasilitas kesehatan di {$city}?"
                    : 'Fasilitas kesehatan apa saja yang cocok memakai aplikasi ini?',
                'a' => $city
                    ? "Tentu. Aplikasi ini dapat digunakan oleh rumah sakit, klinik, dan puskesmas di {$city} maupun kota lain di seluruh Indonesia, dengan dukungan multi-cabang."
                    : 'Aplikasi cocok untuk rumah sakit tipe A–D, klinik pratama/utama, puskesmas, rumah sakit ibu & anak, hingga laboratorium klinik.',
            ],
            [
                'q' => 'Bagaimana dukungan dan pemeliharaan setelah pembelian?',
                'a' => 'Tersedia dokumentasi lengkap, panduan instalasi, serta dukungan teknis. Anda juga mendapat pembaruan fitur dan perbaikan sesuai paket yang dipilih.',
            ],
        ];

        return $faqs;
    }

    /** Generate multi-paragraph intro (300+ words) for a pSEO page. */
    public function intro(string $headline, array $bullets = [], array $vars = []): array
    {
        $name = $vars['name'] ?? $headline;
        $city = $vars['city'] ?? null;
        $cityPhrase = $city ? " di {$city}" : '';

        $paragraphs = [
            "{$headline} hadir sebagai jawaban atas kebutuhan digitalisasi layanan kesehatan{$cityPhrase} yang semakin mendesak. Pengelolaan fasilitas kesehatan modern tidak lagi bisa mengandalkan pencatatan manual atau spreadsheet yang rentan kesalahan. Dengan sistem terintegrasi, seluruh alur mulai dari pendaftaran pasien, antrian poliklinik, pemeriksaan dokter, penunjang diagnostik, farmasi, hingga pembayaran dapat berjalan dalam satu platform yang saling terhubung.",

            'Salah satu keunggulan utama adalah rekam medis elektronik (RME) yang memenuhi standar Kementerian Kesehatan. Setiap riwayat kunjungan, diagnosis, tindakan, resep, dan hasil laboratorium pasien tersimpan rapi dan dapat diakses kembali oleh tenaga medis yang berwenang. Hal ini mempercepat pengambilan keputusan klinis sekaligus meningkatkan keselamatan pasien. Integrasi BPJS dan SatuSehat memastikan pelaporan data berjalan otomatis tanpa entri ganda.',

            "Dari sisi operasional{$cityPhrase}, manajemen dapat memantau kinerja fasilitas secara real-time melalui dashboard dan laporan analitik: jumlah kunjungan, pendapatan, okupansi bed, stok obat, hingga kinerja tiap poliklinik. Modul keuangan dengan pembukuan double-entry, HR & payroll, serta manajemen aset melengkapi kebutuhan back-office sehingga pengelolaan menjadi transparan dan auditable.",
        ];

        if (! empty($bullets)) {
            $paragraphs[] = 'Beberapa kemampuan inti yang tersedia antara lain: '.implode(', ', $bullets).'. Seluruh modul dirancang modular sehingga dapat diaktifkan sesuai skala dan kebutuhan fasilitas kesehatan Anda.';
        }

        $paragraphs[] = "Dengan arsitektur yang dapat di-whitelabel dan di-host mandiri, {$name} menjadi pilihan tepat bagi pemilik fasilitas kesehatan, pengembang, maupun vendor yang ingin menawarkan solusi SIMRS siap pakai. Investasi pada sistem yang tepat akan menekan biaya operasional jangka panjang sekaligus meningkatkan kualitas pelayanan kepada masyarakat.";

        return $paragraphs;
    }

    /** Default feature bullets used across pages. */
    public function defaultFeatures(): array
    {
        return array_values(SeoData::FEATURES);
    }
}
