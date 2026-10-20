<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class SatusehatDiagnose extends Command
{
    protected $signature = 'satusehat:diagnose';

    protected $description = 'Diagnosa konfigurasi & token SatuSehat (client id/secret tampil tersamar)';

    public function handle(): int
    {
        $settings = Setting::where('group', 'satu_sehat')->get()->pluck('value', 'key');

        $this->info('== Konfigurasi tersimpan ==');
        $this->line('is_enabled : ' . ($settings['satusehat_is_enabled'] ?? '(kosong)'));
        $this->line('base_url   : ' . ($settings['satusehat_base_url'] ?? '(kosong)'));
        $this->line('org_id     : ' . ($settings['satusehat_organization_id'] ?? '(kosong)'));

        foreach (['satusehat_client_id', 'satusehat_client_secret'] as $key) {
            $raw = $settings[$key] ?? null;
            if ($raw === null || $raw === '') {
                $this->error($key . ' : KOSONG (belum disimpan)');

                continue;
            }
            try {
                $dec = Crypt::decryptString($raw);
            } catch (\Throwable $e) {
                $this->error($key . ' : ADA tapi GAGAL didekripsi — APP_KEY kemungkinan berubah. Simpan ulang dari halaman Settings.');

                continue;
            }
            $n = strlen($dec);
            $mask = $n <= 8 ? str_repeat('*', $n) : substr($dec, 0, 4) . str_repeat('*', max(4, $n - 8)) . substr($dec, -4);
            $ws = ($dec !== trim($dec)) ? ' <-- ADA SPASI/whitespace di awal/akhir!' : '';
            $this->line($key . ' : ' . $mask . ' (panjang ' . $n . ')' . $ws);
        }

        $base = rtrim($settings['satusehat_base_url'] ?? '', '/');
        if ($base === '') {
            $this->error('Base URL kosong — token tidak dicoba.');

            return self::FAILURE;
        }

        $isDevCred = str_contains($base, 'dev');
        $this->line('lingkungan  : ' . ($isDevCred ? 'DEV/sandbox' : 'PROD'));
        $this->warn('Kredensial developer (sandbox) HANYA berlaku untuk https://api-satusehat-dev.dto.kemkes.go.id');

        $this->info('== Percobaan token ==');
        $client = new \App\Services\SatuSehatClient;
        $client->clearToken();
        $result = $client->testConnection();
        if ($result['ok']) {
            $this->info('OK: ' . ($result['message'] ?? 'terhubung'));

            return self::SUCCESS;
        }
        $this->error('GAGAL: ' . ($result['error'] ?? 'unknown'));

        return self::FAILURE;
    }
}
