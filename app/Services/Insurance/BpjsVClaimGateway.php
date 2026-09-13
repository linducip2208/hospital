<?php

namespace App\Services\Insurance;

use App\Models\Patient;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BpjsVClaimGateway implements InsuranceGatewayInterface
{
    private array $settings;

    public function __construct()
    {
        $this->settings = Setting::where('group', 'bpjs')->pluck('value', 'key')->all();
        foreach (['bpjs_consumer_id', 'bpjs_consumer_secret', 'bpjs_user_key'] as $key) {
            if (! empty($this->settings[$key])) {
                try { $this->settings[$key] = Crypt::decryptString($this->settings[$key]); } catch (\Throwable) { $this->settings[$key] = null; }
            }
        }
    }

    public function checkEligibility(Patient $patient): array
    {
        $number = preg_replace('/\D/', '', (string) $patient->bpjs_number);
        if (strlen($number) !== 13) return ['eligible' => false, 'payer' => 'bpjs', 'status' => 'Nomor tidak valid', 'message' => 'Nomor BPJS harus 13 digit.', 'detail' => [], 'mode' => 'live'];
        $timestamp = (string) now()->timestamp;
        $consumer = (string) $this->settings['bpjs_consumer_id'];
        $signature = base64_encode(hash_hmac('sha256', $consumer.'&'.$timestamp, (string) $this->settings['bpjs_consumer_secret'], true));
        try {
            $response = Http::timeout(15)->retry(2, 250)->withHeaders(['X-cons-id' => $consumer, 'X-timestamp' => $timestamp, 'X-signature' => $signature, 'user_key' => $this->settings['bpjs_user_key'], 'Accept' => 'application/json'])->get(rtrim((string) $this->settings['bpjs_base_url'], '/').'/Peserta/nokartu/'.$number.'/tglSEP/'.now()->format('Y-m-d'));
            if (! $response->successful()) return ['eligible' => false, 'payer' => 'bpjs', 'status' => 'Gagal terhubung (HTTP '.$response->status().')', 'message' => 'BPJS live gateway menolak permintaan.', 'detail' => [], 'mode' => 'live'];
            $body = $response->json(); $meta = $body['metaData'] ?? [];
            return ['eligible' => ($meta['code'] ?? null) === '200', 'payer' => 'bpjs', 'status' => $meta['message'] ?? 'Respons BPJS', 'message' => 'Hasil eligibility dari BPJS VClaim live.', 'detail' => is_array($body['response'] ?? null) ? $body['response'] : [], 'mode' => 'live'];
        } catch (\Throwable $e) {
            Log::warning('BPJS eligibility request failed', ['status' => 'error', 'message' => $e->getMessage()]);
            return ['eligible' => false, 'payer' => 'bpjs', 'status' => 'Gateway error', 'message' => 'BPJS live gateway tidak tersedia; tidak ada fallback diam-diam ke data simulasi.', 'detail' => [], 'mode' => 'live'];
        }
    }

    public function mode(): string { return 'live'; }
}
