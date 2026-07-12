<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [

            // 1. BRANDING
            [
                'section' => 'branding',
                'title' => 'SIMRS Hospital',
                'subtitle' => 'Sistem Informasi Manajemen Rumah Sakit',
                'content' => null,
                'image_url' => null,
                'meta' => [
                    'whatsapp_number' => '6281296052010',
                    'whatsapp_text' => 'Halo, saya tertarik dengan SIMRS',
                    'primary_color' => '#2563eb',
                    'accent_color' => '#06b6d4',
                ],
                'order' => 1,
            ],

            // 2. HERO
            [
                'section' => 'hero',
                'title' => 'Modernisasi Operasional Rumah Sakit Anda',
                'subtitle' => 'Sistem Manajemen Rumah Sakit Terintegrasi',
                'content' => 'Platform all-in-one yang mengintegrasikan rekam medis elektronik, farmasi, IGD, rawat inap, kebidanan, keuangan, hingga SDM — siap pakai dengan integrasi BPJS Kesehatan & SatuSehat Kemkes.',
                'image_url' => 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=1200&q=80',
                'button_text' => 'Lihat Demo Video',
                'button_url' => '#video',
                'meta' => [
                    'highlight_words' => 'Rumah Sakit Anda',
                    'cta_secondary_text' => 'Coba Gratis',
                    'cta_secondary_url' => '/register',
                    'mini_stats' => [
                        ['label' => 'Modul Terintegrasi', 'value' => '13+'],
                        ['label' => 'Pasien Terdaftar', 'value' => 'auto'],
                        ['label' => 'Support IGD', 'value' => '24/7'],
                    ],
                    'floating_card_1' => 'Antrian Live',
                    'floating_card_2' => 'BPJS & SatuSehat Ready',
                ],
                'order' => 2,
            ],

            // 3. TRUST STRIP
            [
                'section' => 'trust',
                'title' => 'Kompatibel dengan Sistem Nasional',
                'subtitle' => null,
                'content' => null,
                'meta' => [
                    'badges' => [
                        ['icon' => 'bi-credit-card-2-front-fill', 'text' => 'BPJS Kesehatan'],
                        ['icon' => 'bi-link-45deg', 'text' => 'SatuSehat Kemkes'],
                        ['icon' => 'bi-gov', 'text' => 'PSE Kominfo'],
                        ['icon' => 'bi-diagram-3-fill', 'text' => 'HL7 FHIR'],
                        ['icon' => 'bi-shield-lock-fill', 'text' => 'Enkripsi End-to-End'],
                        ['icon' => 'bi-cloud-check-fill', 'text' => 'Cloud Backup Harian'],
                    ],
                ],
                'order' => 3,
            ],

            // 4. VIDEO DEMO
            [
                'section' => 'video',
                'title' => 'Tour Singkat Platform Kami',
                'subtitle' => 'Lihat dalam Aksi',
                'content' => 'Dari pendaftaran pasien sampai laporan keuangan — semua alur kerja rumah sakit dalam satu video singkat.',
                'image_url' => 'https://images.unsplash.com/photo-1666214280391-8ff5bd3c0bf0?auto=format&fit=crop&w=1400&q=80',
                'video_url' => 'https://www.youtube.com/watch?v=JlpgG4qfa8k',
                'meta' => [
                    'caption' => 'Durasi 2 menit',
                    'highlights' => [
                        'Setup Cepat 1 Hari',
                        '100% Cloud-Based',
                        'Multi-Cabang Ready',
                        'Backup Otomatis',
                        'Pelatihan Gratis',
                    ],
                ],
                'order' => 4,
            ],

            // 5. MODULES (13 modul)
            [
                'section' => 'modules',
                'title' => '13 Modul Terintegrasi dalam Satu Platform',
                'subtitle' => 'Modul Lengkap',
                'content' => 'Setiap unit rumah sakit punya kebutuhan unik. Sistem kami menyediakan modul khusus untuk setiap alur — saling terhubung, real-time, dan tanpa perlu integrasi pihak ketiga.',
                'meta' => [
                    'items' => [
                        ['icon' => 'bi-people-fill', 'color' => 'grad-blue', 'title' => 'Manajemen Pasien', 'desc' => 'Pendaftaran, riwayat kunjungan, BPJS, dan database pasien terpusat.'],
                        ['icon' => 'bi-calendar-check-fill', 'color' => 'grad-teal', 'title' => 'Appointment & Antrian', 'desc' => 'Booking online, antrian real-time, panggilan otomatis, notifikasi.'],
                        ['icon' => 'bi-journal-medical', 'color' => 'grad-indigo', 'title' => 'Rekam Medis Elektronik', 'desc' => 'EMR lengkap, riwayat diagnosa, ICD-10, sesuai standar SatuSehat.'],
                        ['icon' => 'bi-lightning-charge-fill', 'color' => 'grad-rose', 'title' => 'IGD & Emergency', 'desc' => 'Triase digital, panggilan ambulans, koordinasi tim 24/7.'],
                        ['icon' => 'bi-building-fill', 'color' => 'grad-amber', 'title' => 'Rawat Inap', 'desc' => 'Manajemen kamar, okupansi live, admission & discharge.'],
                        ['icon' => 'bi-capsule-pill', 'color' => 'grad-emerald', 'title' => 'Farmasi', 'desc' => 'Stok obat real-time, resep digital, alert stok minimum.'],
                        ['icon' => 'bi-droplet-fill', 'color' => 'grad-blue', 'title' => 'Laboratorium', 'desc' => 'Order test, hasil lab digital, integrasi dengan rekam medis.'],
                        ['icon' => 'bi-radioactive', 'color' => 'grad-cyan', 'title' => 'Radiologi (RIS-PACS)', 'desc' => 'Pemeriksaan, hasil radiologi, viewer DICOM terintegrasi.'],
                        ['icon' => 'bi-heart-pulse-fill', 'color' => 'grad-rose', 'title' => 'Kebidanan & Maternity', 'desc' => 'ANC, partograf, postnatal, imunisasi bayi dalam satu modul.'],
                        ['icon' => 'bi-clipboard2-pulse-fill', 'color' => 'grad-indigo', 'title' => 'Operasi (OK)', 'desc' => 'Penjadwalan operasi, tim bedah, laporan tindakan lengkap.'],
                        ['icon' => 'bi-cash-stack', 'color' => 'grad-emerald', 'title' => 'Keuangan & Akuntansi', 'desc' => 'Pembayaran, invoice, jurnal umum, chart of accounts, laporan.'],
                        ['icon' => 'bi-clipboard-data-fill', 'color' => 'grad-amber', 'title' => 'HR & Payroll', 'desc' => 'Karyawan, absensi, penggajian, cuti, jadwal staff.'],
                        ['icon' => 'bi-box-seam-fill', 'color' => 'grad-slate', 'title' => 'Aset & Logistik', 'desc' => 'Inventaris, purchase order, manajemen aset rumah sakit.'],
                    ],
                ],
                'order' => 5,
            ],

            // 6. FEATURES (8 keunggulan)
            [
                'section' => 'features',
                'title' => 'Dirancang untuk Tenaga Medis Indonesia',
                'subtitle' => 'Fitur Unggulan',
                'content' => 'Bukan sekadar software generik — tiap fitur dibangun memahami konteks alur kerja rumah sakit nasional, regulasi Kemkes, dan integrasi BPJS.',
                'meta' => [
                    'items' => [
                        ['icon' => 'bi-shield-fill-check', 'color' => '#2563eb', 'title' => 'Integrasi BPJS & SatuSehat', 'desc' => 'Klaim BPJS otomatis, sinkronisasi data Kemkes, kompatibel HL7 FHIR.'],
                        ['icon' => 'bi-graph-up', 'color' => '#06b6d4', 'title' => 'Dashboard Real-Time', 'desc' => 'KPI live, okupansi kamar, antrian, pendapatan — update detik per detik.'],
                        ['icon' => 'bi-cloud-arrow-up-fill', 'color' => '#10b981', 'title' => 'Cloud-Native', 'desc' => 'Akses dari mana saja, backup otomatis, uptime 99.9%, scalable.'],
                        ['icon' => 'bi-people-fill', 'color' => '#6366f1', 'title' => 'Multi-Role & Multi-Cabang', 'desc' => 'Admin, dokter, perawat, kasir, bidan — hak akses granular per peran.'],
                        ['icon' => 'bi-phone-fill', 'color' => '#f59e0b', 'title' => 'Mobile Friendly', 'desc' => 'Bekerja sempurna di tablet ronde & smartphone perawat.'],
                        ['icon' => 'bi-bell-fill', 'color' => '#f43f5e', 'title' => 'Notifikasi Cerdas', 'desc' => 'Alert stok obat kritis, kamar penuh, antrian panjang, pasien IGD.'],
                        ['icon' => 'bi-file-earmark-bar-graph-fill', 'color' => '#0891b2', 'title' => 'Laporan Otomatis', 'desc' => 'Laporan harian/bulanan/akreditasi siap export PDF & Excel.'],
                        ['icon' => 'bi-key-fill', 'color' => '#4f46e5', 'title' => 'Keamanan Berlapis', 'desc' => 'Enkripsi end-to-end, audit log, role-based access, 2FA opsional.'],
                    ],
                ],
                'order' => 6,
            ],

            // 7. SHOWCASE
            [
                'section' => 'showcase',
                'title' => 'Antarmuka yang Dirancang untuk Kecepatan Kerja',
                'subtitle' => 'Lihat Tampilannya',
                'content' => 'Setiap layar dioptimalkan agar staff medis bisa bekerja lebih cepat — bukan harus belajar software.',
                'meta' => [
                    'items' => [
                        ['img' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=900&q=80', 'title' => 'Dashboard Eksekutif', 'desc' => 'KPI lengkap, grafik kunjungan, okupansi, dan pendapatan dalam satu layar.'],
                        ['img' => 'https://images.unsplash.com/photo-1551601651-2a8555f1a136?auto=format&fit=crop&w=900&q=80', 'title' => 'Rekam Medis Elektronik', 'desc' => 'EMR cepat dengan template SOAP, ICD-10 lookup, riwayat lengkap pasien.'],
                        ['img' => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=900&q=80', 'title' => 'Modul Laboratorium', 'desc' => 'Order test, hasil lab digital, integrasi langsung ke EMR pasien.'],
                    ],
                ],
                'order' => 7,
            ],

            // 8. STATS
            [
                'section' => 'stats',
                'title' => 'Angka Kami',
                'subtitle' => 'Dipercaya oleh institusi kesehatan',
                'content' => null,
                'meta' => [
                    'items' => [
                        ['icon' => 'bi-people-fill', 'value' => 'auto:patients', 'label' => 'Pasien Terdaftar'],
                        ['icon' => 'bi-person-badge-fill', 'value' => 'auto:doctors', 'label' => 'Dokter Profesional'],
                        ['icon' => 'bi-clipboard2-pulse-fill', 'value' => 'auto:polys', 'label' => 'Poli & Klinik'],
                        ['icon' => 'bi-clock-history', 'value' => '24/7', 'label' => 'Layanan IGD'],
                    ],
                ],
                'order' => 8,
            ],

            // 9. TESTIMONIALS
            [
                'section' => 'testimonials',
                'title' => 'Dipercaya oleh Tenaga Medis Indonesia',
                'subtitle' => 'Testimoni',
                'content' => 'Dengarkan pengalaman dokter, perawat, dan administrator yang telah menggunakan sistem kami.',
                'meta' => [
                    'items' => [
                        ['quote' => 'Sistem ini sangat membantu mempercepat alur kerja di rumah sakit kami. Dari pendaftaran hingga pembayaran semuanya terintegrasi. Tim implementasinya juga sangat responsif.', 'avatar' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=120&h=120&q=80', 'name' => 'dr. Andi Pratama, Sp.PD', 'role' => 'Direktur RS Sehat Sentosa'],
                        ['quote' => 'Modul keperawatan dan rekam medis elektroniknya sangat lengkap. Dokumentasi asuhan keperawatan jadi jauh lebih rapi, bisa diakses dari tablet ronde langsung.', 'avatar' => 'https://images.unsplash.com/photo-1638202993928-7267aad84c31?auto=format&fit=crop&w=120&h=120&q=80', 'name' => 'Ns. Siti Rahayu, S.Kep', 'role' => 'Kepala Perawat — RS Mitra Bunda'],
                        ['quote' => 'Dashboard analytics-nya membantu saya lihat performa rumah sakit real-time — okupansi kamar, pendapatan, sampai stok obat kritis. Pengambilan keputusan jauh lebih cepat.', 'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=120&h=120&q=80', 'name' => 'Ahmad Kusuma, MARS', 'role' => 'Manajer Operasional — RSU Harapan'],
                    ],
                ],
                'order' => 9,
            ],

            // 10. INSIGHTS / BLOG
            [
                'section' => 'insights',
                'title' => 'Wawasan Industri Kesehatan Indonesia',
                'subtitle' => 'Insights',
                'content' => 'Panduan implementasi, regulasi terbaru, dan praktik terbaik manajemen rumah sakit modern.',
                'meta' => [
                    'items' => [
                        ['tag' => 'Regulasi', 'title' => 'Panduan Implementasi Rekam Medis Elektronik 2026', 'excerpt' => 'Permenkes mengamanatkan seluruh faskes wajib menggunakan EMR. Berikut langkah-langkah migrasi yang aman.', 'img' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=900&q=80', 'duration' => '5 menit baca', 'url' => '#'],
                        ['tag' => 'Best Practice', 'title' => '5 Indikator Kunci Performa Rumah Sakit Modern', 'excerpt' => 'BOR, ALOS, TOI, BTO, NDR — pahami metrik yang harus dipantau direktur RS setiap minggu.', 'img' => 'https://images.unsplash.com/photo-1581595220892-b0739db3ba8c?auto=format&fit=crop&w=900&q=80', 'duration' => '7 menit baca', 'url' => '#'],
                        ['tag' => 'Integrasi', 'title' => 'SatuSehat: Cara Hubungkan SIMRS dengan Platform Kemkes', 'excerpt' => 'Tutorial lengkap onboarding ke SatuSehat, lengkap dengan checklist teknis dan administratif.', 'img' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=900&q=80', 'duration' => '6 menit baca', 'url' => '#'],
                    ],
                ],
                'order' => 10,
            ],

            // 11. PARTNERS
            [
                'section' => 'partners',
                'title' => 'Kompatibel & Terhubung dengan',
                'subtitle' => null,
                'content' => null,
                'meta' => [
                    'items' => ['BPJS Kesehatan', 'SATUSEHAT', 'Kemenkes RI', 'HL7 FHIR', 'DICOM', 'Midtrans', 'Xendit'],
                ],
                'order' => 11,
            ],

            // 12. CTA FINAL
            [
                'section' => 'cta',
                'title' => 'Siap Modernisasi Rumah Sakit Anda?',
                'subtitle' => null,
                'content' => 'Mulai digitalisasi operasional rumah sakit hari ini. Demo gratis — tanpa komitmen.',
                'button_text' => 'Mulai Trial Gratis',
                'button_url' => '/register',
                'meta' => [
                    'cta_secondary_text' => 'Request Demo',
                    'cta_secondary_url' => 'https://wa.me/6281296052010',
                ],
                'order' => 12,
            ],

            // 13. FOOTER
            [
                'section' => 'footer',
                'title' => 'SIMRS Hospital',
                'subtitle' => 'Sistem Manajemen Rumah Sakit terintegrasi untuk modernisasi operasional fasilitas kesehatan di Indonesia.',
                'content' => null,
                'meta' => [
                    'address' => 'Jakarta, Indonesia',
                    'phone' => '+62 812-9605-2010',
                    'email' => 'info@simrs.id',
                    'cert_badges' => [
                        ['icon' => 'bi-credit-card-2-front-fill', 'text' => 'BPJS Kesehatan'],
                        ['icon' => 'bi-link-45deg', 'text' => 'SatuSehat Ready'],
                        ['icon' => 'bi-gov', 'text' => 'PSE Kominfo'],
                        ['icon' => 'bi-shield-lock-fill', 'text' => 'Data Terenkripsi'],
                    ],
                    'social' => [
                        ['icon' => 'bi-instagram', 'url' => '#', 'label' => 'Instagram'],
                        ['icon' => 'bi-linkedin', 'url' => '#', 'label' => 'LinkedIn'],
                        ['icon' => 'bi-facebook', 'url' => '#', 'label' => 'Facebook'],
                        ['icon' => 'bi-whatsapp', 'url' => 'https://wa.me/6281296052010', 'label' => 'WhatsApp'],
                        ['icon' => 'bi-youtube', 'url' => '#', 'label' => 'YouTube'],
                    ],
                ],
                'order' => 13,
            ],

            // 14. ABOUT (legacy)
            [
                'section' => 'about',
                'title' => 'Tentang Kami',
                'subtitle' => 'Sistem informasi rumah sakit terpercaya',
                'content' => 'Kami adalah penyedia solusi teknologi informasi untuk sektor kesehatan di Indonesia. Berpengalaman dalam mengembangkan sistem manajemen rumah sakit yang handal, aman, dan mudah digunakan.',
                'order' => 14,
            ],
        ];

        foreach ($sections as $data) {
            $data['is_active'] = $data['is_active'] ?? true;
            PageContent::updateOrCreate(['section' => $data['section']], $data);
        }
    }
}
