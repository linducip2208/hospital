<?php

namespace App\Services\Clinical;

use App\Models\Patient;
use App\Models\Setting;
use App\Services\Insurance\BpjsVClaimGateway;
use App\Services\Insurance\InsuranceGatewayInterface;
use App\Services\Insurance\LocalInsuranceGateway;

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

        $settings = Setting::where('group', 'bpjs')->pluck('value', 'key');
        $configured = ($settings['bpjs_is_enabled'] ?? false) && ! empty($settings['bpjs_base_url']) && ! empty($settings['bpjs_consumer_id']) && ! empty($settings['bpjs_consumer_secret']) && ! empty($settings['bpjs_user_key']);
        /** @var InsuranceGatewayInterface $gateway */
        $gateway = $configured ? new BpjsVClaimGateway : new LocalInsuranceGateway;
        return $gateway->checkEligibility($patient);
    }
}
