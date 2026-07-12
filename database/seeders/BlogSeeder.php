<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Manajemen Rumah Sakit' => 'Tips dan strategi mengelola operasional fasilitas kesehatan.',
            'Rekam Medis Elektronik' => 'Seputar digitalisasi rekam medis dan standar Kemenkes.',
            'BPJS & SatuSehat' => 'Panduan integrasi dan regulasi layanan kesehatan nasional.',
            'Teknologi Kesehatan' => 'Inovasi teknologi di dunia layanan kesehatan.',
        ];

        $catModels = [];
        foreach ($categories as $name => $desc) {
            $catModels[$name] = BlogCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $desc]
            );
        }

        $author = User::where('role', 'admin')->first() ?? User::first();

        $articles = [
            ['Manajemen Rumah Sakit', 'Panduan Lengkap Digitalisasi Rumah Sakit di Tahun 2026', 'Transformasi digital bukan lagi pilihan, melainkan keharusan bagi rumah sakit modern.'],
            ['Manajemen Rumah Sakit', '7 Indikator Kinerja Utama (KPI) yang Wajib Dipantau Rumah Sakit', 'Mengukur kinerja rumah sakit membutuhkan indikator yang tepat dan terukur.'],
            ['Manajemen Rumah Sakit', 'Cara Mengurangi Waktu Tunggu Pasien di Poliklinik', 'Antrian panjang adalah keluhan nomor satu pasien di banyak fasilitas kesehatan.'],
            ['Rekam Medis Elektronik', 'Mengenal Rekam Medis Elektronik (RME) dan Manfaatnya', 'RME mengubah cara fasilitas kesehatan mengelola data pasien secara fundamental.'],
            ['Rekam Medis Elektronik', 'Regulasi RME Terbaru: Apa yang Harus Disiapkan Faskes?', 'Kementerian Kesehatan mewajibkan penerapan rekam medis elektronik bagi seluruh faskes.'],
            ['Rekam Medis Elektronik', 'Tips Migrasi Rekam Medis Kertas ke Digital Tanpa Kehilangan Data', 'Proses migrasi data rekam medis membutuhkan perencanaan yang matang.'],
            ['BPJS & SatuSehat', 'Integrasi SatuSehat: Langkah demi Langkah untuk Faskes', 'SatuSehat menjadi platform interoperabilitas data kesehatan nasional.'],
            ['BPJS & SatuSehat', 'Memahami VClaim dan Antrean BPJS untuk Rumah Sakit', 'Integrasi BPJS mempercepat proses klaim dan pelayanan pasien peserta JKN.'],
            ['BPJS & SatuSehat', 'Kesalahan Umum saat Klaim BPJS dan Cara Menghindarinya', 'Klaim yang ditolak dapat mengganggu arus kas rumah sakit secara signifikan.'],
            ['Teknologi Kesehatan', 'Telemedicine: Masa Depan Layanan Kesehatan Jarak Jauh', 'Layanan konsultasi jarak jauh semakin diminati pasca pandemi.'],
            ['Teknologi Kesehatan', 'Peran AI dalam Diagnosis dan Manajemen Rumah Sakit', 'Kecerdasan buatan mulai diterapkan untuk mendukung keputusan klinis.'],
            ['Teknologi Kesehatan', 'Keamanan Data Pasien: Standar yang Harus Dipenuhi Faskes', 'Data kesehatan adalah aset sensitif yang wajib dilindungi.'],
        ];

        $images = [
            'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=1200&q=70',
            'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=70',
            'https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?auto=format&fit=crop&w=1200&q=70',
            'https://images.unsplash.com/photo-1504813184591-01572f98c85f?auto=format&fit=crop&w=1200&q=70',
        ];

        foreach ($articles as $i => [$cat, $title, $excerpt]) {
            BlogPost::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $this->body($title, $excerpt),
                    'featured_image' => $images[$i % count($images)],
                    'category_id' => $catModels[$cat]->id,
                    'author_id' => $author?->id,
                    'is_published' => true,
                    'published_at' => now()->subDays(count($articles) - $i),
                    'meta_title' => $title,
                    'meta_description' => Str::limit($excerpt, 155),
                    'views' => rand(50, 2500),
                ]
            );
        }
    }

    protected function body(string $title, string $excerpt): string
    {
        return <<<HTML
<p><strong>{$excerpt}</strong></p>
<p>{$title} menjadi topik yang semakin relevan seiring meningkatnya tuntutan masyarakat terhadap kualitas layanan kesehatan. Fasilitas kesehatan yang mampu beradaptasi dengan teknologi akan memiliki keunggulan kompetitif yang signifikan, baik dari sisi efisiensi operasional maupun kepuasan pasien.</p>
<h2>Mengapa Ini Penting?</h2>
<p>Pengelolaan fasilitas kesehatan modern menuntut sistem yang terintegrasi. Mulai dari pendaftaran pasien, antrian poliklinik, rekam medis elektronik, farmasi, hingga penagihan dan pelaporan ke platform nasional seperti BPJS dan SatuSehat. Tanpa sistem yang tepat, proses ini rentan terhadap kesalahan, duplikasi data, dan pemborosan waktu.</p>
<h2>Langkah Praktis</h2>
<ul>
<li>Evaluasi kebutuhan dan alur kerja fasilitas kesehatan Anda.</li>
<li>Pilih sistem informasi yang mendukung integrasi BPJS dan SatuSehat.</li>
<li>Latih staf medis dan administrasi secara bertahap.</li>
<li>Pantau indikator kinerja secara berkala melalui dashboard.</li>
</ul>
<h2>Kesimpulan</h2>
<p>Investasi pada sistem informasi rumah sakit yang tepat akan menekan biaya operasional jangka panjang sekaligus meningkatkan mutu pelayanan. Dengan solusi yang dapat di-whitelabel dan di-host mandiri, fasilitas kesehatan memiliki kendali penuh atas data dan proses bisnisnya.</p>
HTML;
    }
}
