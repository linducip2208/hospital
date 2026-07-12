<?php

namespace App\Services\Clinical;

use App\Models\Patient;

class InsuranceEligibilityService
{
    /**
     * Cek eligibilitas BPJS/asuransi pasien.
     * Catatan: implementasi nyata memanggil API VClaim BPJS.
     * Di sini validasi format & status lokal (demo/offline-safe).
     */
    public function check(Patient $patient): array
    {
        $bpjs = trim((string) $patient->bpjs_number);

        if ($bpjs === '') {
            return [
                'eligible' => false,
                'payer' => 'umum',
                'status' => 'Tidak ada nomor BPJS',
                'message' => 'Pasien belum memiliki nomor BPJS. Diarahkan sebagai pasien umum.',
                'detail' => [],
            ];
        }

        // Validasi format: BPJS = 13 digit
        $digits = preg_replace('/\D/', '', $bpjs);
        if (strlen($digits) !== 13) {
            return [
                'eligible' => false,
                'payer' => 'bpjs',
                'status' => 'Nomor tidak valid',
                'message' => 'Nomor BPJS harus 13 digit. Silakan verifikasi ulang.',
                'detail' => ['nomor' => $bpjs],
            ];
        }

        // Demo: dianggap aktif jika NIK terverifikasi
        $active = (bool) $patient->nik_verified;

        return [
            'eligible' => $active,
            'payer' => 'bpjs',
            'status' => $active ? 'Aktif' : 'Perlu verifikasi',
            'message' => $active
                ? 'Peserta BPJS aktif. Layanan dapat ditanggung sesuai hak kelas.'
                : 'Status kepesertaan perlu diverifikasi ke BPJS (NIK belum terverifikasi).',
            'detail' => [
                'nomor_bpjs' => $digits,
                'nama' => $patient->name,
                'nik_terverifikasi' => $active,
            ],
        ];
    }
}
