<?php

namespace App\Support;

class SeoData
{
    /** Kota-kota besar Indonesia untuk pSEO lokal. */
    public const CITIES = [
        'jakarta' => 'Jakarta',
        'surabaya' => 'Surabaya',
        'bandung' => 'Bandung',
        'medan' => 'Medan',
        'semarang' => 'Semarang',
        'makassar' => 'Makassar',
        'palembang' => 'Palembang',
        'tangerang' => 'Tangerang',
        'depok' => 'Depok',
        'bekasi' => 'Bekasi',
        'yogyakarta' => 'Yogyakarta',
        'malang' => 'Malang',
        'denpasar' => 'Denpasar',
        'balikpapan' => 'Balikpapan',
        'samarinda' => 'Samarinda',
        'pekanbaru' => 'Pekanbaru',
        'padang' => 'Padang',
        'bandar-lampung' => 'Bandar Lampung',
        'bogor' => 'Bogor',
        'batam' => 'Batam',
        'pontianak' => 'Pontianak',
        'manado' => 'Manado',
        'banjarmasin' => 'Banjarmasin',
        'solo' => 'Solo',
        'cirebon' => 'Cirebon',
        'serang' => 'Serang',
        'jambi' => 'Jambi',
        'mataram' => 'Mataram',
        'kupang' => 'Kupang',
        'ambon' => 'Ambon',
    ];

    /** Jenis fasilitas kesehatan. */
    public const FACILITY_TYPES = [
        'rumah-sakit' => 'Rumah Sakit',
        'klinik' => 'Klinik',
        'puskesmas' => 'Puskesmas',
        'rumah-sakit-ibu-anak' => 'Rumah Sakit Ibu & Anak',
        'rumah-sakit-gigi-mulut' => 'Rumah Sakit Gigi & Mulut',
        'klinik-kecantikan' => 'Klinik Kecantikan',
        'laboratorium-klinik' => 'Laboratorium Klinik',
        'apotek' => 'Apotek',
    ];

    /** Fitur/modul aplikasi SIMRS untuk pSEO fitur. */
    public const FEATURES = [
        'rekam-medis-elektronik' => 'Rekam Medis Elektronik',
        'antrian-online' => 'Antrian Online',
        'integrasi-bpjs' => 'Integrasi BPJS',
        'integrasi-satusehat' => 'Integrasi SatuSehat',
        'farmasi-apotek' => 'Manajemen Farmasi & Apotek',
        'kasir-billing' => 'Kasir & Billing',
        'laboratorium' => 'Manajemen Laboratorium',
        'radiologi' => 'Manajemen Radiologi',
        'rawat-inap' => 'Manajemen Rawat Inap',
        'igd' => 'Manajemen IGD',
        'keuangan-akuntansi' => 'Keuangan & Akuntansi',
        'hr-payroll' => 'HR & Payroll',
        'telemedicine' => 'Telemedicine',
        'klaim-asuransi' => 'Klaim Asuransi',
        'manajemen-bed' => 'Manajemen Bed & Kamar',
        'kebidanan' => 'Modul Kebidanan',
        'laporan-analitik' => 'Laporan & Analitik',
        'portal-pasien' => 'Portal Pasien',
    ];

    /** Spesialisasi / poliklinik. */
    public const SPECIALTIES = [
        'umum' => 'Poli Umum',
        'gigi' => 'Poli Gigi',
        'anak' => 'Poli Anak',
        'kandungan' => 'Poli Kandungan',
        'jantung' => 'Poli Jantung',
        'saraf' => 'Poli Saraf',
        'mata' => 'Poli Mata',
        'tht' => 'Poli THT',
        'kulit' => 'Poli Kulit & Kelamin',
        'orthopedi' => 'Poli Orthopedi',
        'bedah' => 'Poli Bedah',
        'paru' => 'Poli Paru',
        'psikiatri' => 'Poli Psikiatri',
        'fisioterapi' => 'Poli Fisioterapi',
    ];

    /** Skala/ukuran fasilitas untuk segmentasi harga. */
    public const SCALES = [
        'kecil' => 'Skala Kecil (Klinik Pratama)',
        'menengah' => 'Skala Menengah (RS Tipe C/D)',
        'besar' => 'Skala Besar (RS Tipe A/B)',
        'jaringan' => 'Jaringan Multi-Cabang',
    ];

    /** Kata kunci penjualan source code. */
    public const SOURCE_CODE_KEYWORDS = [
        'source-code-aplikasi-rumah-sakit' => 'Source Code Aplikasi Rumah Sakit',
        'source-code-simrs' => 'Source Code SIMRS',
        'aplikasi-rumah-sakit' => 'Aplikasi Rumah Sakit',
        'software-rumah-sakit' => 'Software Rumah Sakit',
        'aplikasi-klinik' => 'Aplikasi Klinik',
        'software-klinik' => 'Software Klinik',
        'aplikasi-rekam-medis' => 'Aplikasi Rekam Medis Elektronik',
        'aplikasi-puskesmas' => 'Aplikasi Puskesmas',
        'jasa-pembuatan-simrs' => 'Jasa Pembuatan SIMRS',
        'sistem-informasi-rumah-sakit' => 'Sistem Informasi Rumah Sakit',
    ];

    /** Kompetitor/alternatif generik (untuk halaman alternatif). */
    public const ALTERNATIVES = [
        'khanza' => 'Khanza SIMRS',
        'simrs-gos' => 'SIMRS GOS Kemenkes',
        'trustmedis' => 'TrustMedis',
        'medifirst' => 'Medifirst',
        'aido-health' => 'Aido Health',
        'assist-io' => 'Assist.io',
        'medofa' => 'Medofa',
        'sisrute' => 'Sistem Manual / Excel',
    ];

    public const YEARS_AHEAD = 2;

    // ─────────────────────────────────────────────
    // URL generators
    // ─────────────────────────────────────────────

    /** /best-{facility}-{city} */
    public static function allBestUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::FACILITY_TYPES) as $f) {
            foreach (array_keys(self::CITIES) as $c) {
                $urls[] = "/best-{$f}-{$c}";
            }
        }

        return $urls;
    }

    /** /best-{facility}-{city}-{year} */
    public static function allBestYearUrls(): array
    {
        $urls = [];
        $year = (int) date('Y');
        foreach (array_keys(self::FACILITY_TYPES) as $f) {
            foreach (array_keys(self::CITIES) as $c) {
                for ($y = $year; $y <= $year + self::YEARS_AHEAD; $y++) {
                    $urls[] = "/best-{$f}-{$c}-{$y}";
                }
            }
        }

        return $urls;
    }

    /** /alternatif-{competitor} */
    public static function allAlternativeUrls(): array
    {
        return array_map(fn ($k) => "/alternatif-{$k}", array_keys(self::ALTERNATIVES));
    }

    /** /bandingkan/{a}-vs-{b} (feature comparisons) */
    public static function allCompareUrls(): array
    {
        $urls = [];
        $keys = array_keys(self::ALTERNATIVES);
        foreach ($keys as $i => $a) {
            foreach ($keys as $j => $b) {
                if ($i < $j) {
                    $urls[] = "/bandingkan/{$a}-vs-{$b}";
                }
            }
        }

        return $urls;
    }

    /** /fitur-{feature} */
    public static function allFeatureUrls(): array
    {
        return array_map(fn ($k) => "/fitur-{$k}", array_keys(self::FEATURES));
    }

    /** /fitur-{feature}-{city} */
    public static function allFeatureCityUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::FEATURES) as $f) {
            foreach (array_keys(self::CITIES) as $c) {
                $urls[] = "/fitur-{$f}-{$c}";
            }
        }

        return $urls;
    }

    /** /aplikasi-{facility}-{city} */
    public static function allFacilityCityUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::FACILITY_TYPES) as $f) {
            foreach (array_keys(self::CITIES) as $c) {
                $urls[] = "/aplikasi-{$f}-{$c}";
            }
        }

        return $urls;
    }

    // ── Source code sales URLs ──

    /** /beli-{keyword} */
    public static function allSourceCodeUrls(): array
    {
        return array_map(fn ($k) => "/beli-{$k}", array_keys(self::SOURCE_CODE_KEYWORDS));
    }

    /** /{keyword}-{city} */
    public static function allSourceCodeCityUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::SOURCE_CODE_KEYWORDS) as $k) {
            foreach (array_keys(self::CITIES) as $c) {
                $urls[] = "/{$k}-{$c}";
            }
        }

        return $urls;
    }

    /** /{keyword}-{scale} */
    public static function allSourceCodeScaleUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::SOURCE_CODE_KEYWORDS) as $k) {
            foreach (array_keys(self::SCALES) as $s) {
                $urls[] = "/{$k}-{$s}";
            }
        }

        return $urls;
    }

    /** /{keyword}-{feature} */
    public static function allSourceCodeFeatureUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::SOURCE_CODE_KEYWORDS) as $k) {
            foreach (array_keys(self::FEATURES) as $f) {
                $urls[] = "/{$k}-fitur-{$f}";
            }
        }

        return $urls;
    }

    /** /{keyword}-{city}-{scale} (massive cross) */
    public static function allSourceCodeCityScaleUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::SOURCE_CODE_KEYWORDS) as $k) {
            foreach (array_keys(self::CITIES) as $c) {
                foreach (array_keys(self::SCALES) as $s) {
                    $urls[] = "/{$k}-{$c}-{$s}";
                }
            }
        }

        return $urls;
    }

    // ── Specialty cross ──

    /** /aplikasi-poli-{specialty}-{city} */
    public static function allSpecialtyCityUrls(): array
    {
        $urls = [];
        foreach (array_keys(self::SPECIALTIES) as $sp) {
            foreach (array_keys(self::CITIES) as $c) {
                $urls[] = "/aplikasi-poli-{$sp}-{$c}";
            }
        }

        return $urls;
    }

    // ── Helpers to resolve labels from slugs ──

    public static function cityName(string $slug): ?string
    {
        return self::CITIES[$slug] ?? null;
    }

    public static function facilityName(string $slug): ?string
    {
        return self::FACILITY_TYPES[$slug] ?? null;
    }

    public static function featureName(string $slug): ?string
    {
        return self::FEATURES[$slug] ?? null;
    }

    public static function specialtyName(string $slug): ?string
    {
        return self::SPECIALTIES[$slug] ?? null;
    }

    public static function scaleName(string $slug): ?string
    {
        return self::SCALES[$slug] ?? null;
    }

    public static function alternativeName(string $slug): ?string
    {
        return self::ALTERNATIVES[$slug] ?? null;
    }

    public static function sourceCodeName(string $slug): ?string
    {
        return self::SOURCE_CODE_KEYWORDS[$slug] ?? null;
    }
}
