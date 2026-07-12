<?php

namespace App\Services\Clinical;

class Icd10Service
{
    /** Subset ICD-10 umum dipakai di faskes Indonesia. Bisa diperluas. */
    public const CODES = [
        'A09' => 'Diare & gastroenteritis',
        'A15' => 'Tuberkulosis paru',
        'A90' => 'Demam berdarah dengue (DBD)',
        'B34.2' => 'Infeksi coronavirus',
        'E11' => 'Diabetes melitus tipe 2',
        'E78.5' => 'Hiperlipidemia',
        'I10' => 'Hipertensi esensial (primer)',
        'I20' => 'Angina pektoris',
        'I21' => 'Infark miokard akut',
        'I50' => 'Gagal jantung',
        'J00' => 'Nasofaringitis akut (common cold)',
        'J06.9' => 'Infeksi saluran napas atas akut',
        'J18' => 'Pneumonia',
        'J44' => 'Penyakit paru obstruktif kronik (PPOK)',
        'J45' => 'Asma',
        'K29' => 'Gastritis & duodenitis',
        'K35' => 'Apendisitis akut',
        'K52.9' => 'Gastroenteritis non-infeksi',
        'N39.0' => 'Infeksi saluran kemih (ISK)',
        'M54.5' => 'Nyeri punggung bawah',
        'O80' => 'Persalinan tunggal spontan',
        'O82' => 'Persalinan seksio sesarea',
        'P07' => 'Bayi berat lahir rendah (BBLR)',
        'R50.9' => 'Demam tidak spesifik',
        'R51' => 'Sakit kepala',
        'S06' => 'Cedera intrakranial',
        'S72' => 'Fraktur femur',
        'Z00.0' => 'Pemeriksaan kesehatan umum',
        'Z34' => 'Pengawasan kehamilan normal',
        'Z38' => 'Bayi baru lahir (tempat lahir)',
    ];

    /** Cari kode/nama berdasarkan query. */
    public function search(string $q, int $limit = 20): array
    {
        $q = strtolower(trim($q));
        if ($q === '') {
            return array_slice(self::CODES, 0, $limit, true);
        }

        $result = [];
        foreach (self::CODES as $code => $name) {
            if (str_contains(strtolower($code), $q) || str_contains(strtolower($name), $q)) {
                $result[$code] = $name;
            }
            if (count($result) >= $limit) {
                break;
            }
        }

        return $result;
    }

    public function name(string $code): ?string
    {
        return self::CODES[$code] ?? null;
    }

    public function all(): array
    {
        return self::CODES;
    }
}
