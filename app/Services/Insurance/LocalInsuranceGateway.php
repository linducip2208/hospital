<?php

namespace App\Services\Insurance;

use App\Models\Patient;

class LocalInsuranceGateway implements InsuranceGatewayInterface
{
    public function checkEligibility(Patient $patient): array
    {
        $number = preg_replace('/\D/', '', (string) $patient->bpjs_number);
        $valid = strlen($number) === 13;
        $eligible = $valid && (bool) $patient->nik_verified;
        return ['eligible' => $eligible, 'payer' => $valid ? 'bpjs' : 'umum', 'status' => $valid ? ($eligible ? 'Simulation: aktif' : 'Simulation: perlu verifikasi') : 'Nomor tidak valid', 'message' => 'Simulation / Not connected to BPJS. Hasil ini hanya validasi lokal.', 'detail' => ['nomor_bpjs' => $number, 'nik_terverifikasi' => (bool) $patient->nik_verified], 'mode' => 'simulation'];
    }

    public function mode(): string { return 'simulation'; }
}
