<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class SatusehatDiagnose extends Command
{
    protected $signature = 'satusehat:diagnose {--env= : stg, dev, atau prod (default: env aktif)}';

    protected $description = 'Diagnosa konfigurasi & token SatuSehat per environment (client id/secret tampil tersamar)';

    public function handle(): int
    {
        $settings = Setting::where('group', 'satu_sehat')->get()->pluck('value', 'key');
        $active = strtolower(trim((string) ($settings['satusehat_env'] ?? 'stg')));
        if (! in_array($active, ['stg', 'dev', 'prod'], true)) {
            $active = 'stg';
        }
        $target = strtolower((string) ($this->option('env') ?? ''));
        if ($target !== '' && ! in_array($target, ['stg', 'dev', 'prod'], true)) {
            $this->error('--env harus stg, dev, atau prod.');

            return self::FAILURE;
        }

        foreach (['stg', 'dev', 'prod'] as $env) {
            $this->info('== [' . strtoupper($env) . ']' . ($env === $active ? ' (AKTIF)' : ''));
            $p = 'satusehat_' . $env . '_';
            $this->line('base_url      : ' . ($settings[$p . 'base_url'] ?? '(kosong → default)'));
            $this->line('auth_base_url : ' . ($settings[$p . 'auth_base_url'] ?? '(kosong → ikut base_url)'));
            $this->line('org_id        : ' . ($settings[$p . 'organization_id'] ?? '(kosong)'));
            foreach (['client_id', 'client_secret'] as $k) {
                $this->checkSecret($p . $k, $settings[$p . $k] ?? null, $settings['satusehat_' . $k] ?? null);
            }
        }

        $env = $target !== '' ? $target : $active;
        // Token hanya bisa diuji untuk env aktif (client membaca env dari DB).
        $this->info('== Percobaan token [' . strtoupper($active) . '] (env aktif) ==');
        if ($target !== '' && $target !== $active) {
            $this->warn('Untuk menguji ' . strtoupper($target) . ', ganti Environment Aktif di halaman Settings dulu.');
        }

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

    private function checkSecret(string $label, mixed $raw, mixed $legacyRaw): void
    {
        if (($raw === null || $raw === '') && ($legacyRaw === null || $legacyRaw === '')) {
            $this->error($label . ' : KOSONG (belum disimpan)');

            return;
        }
        $used = ($raw === null || $raw === '') ? $legacyRaw : $raw;
        $suffix = ($raw === null || $raw === '') ? ' (pakai kunci lama)' : '';
        try {
            $dec = Crypt::decryptString((string) $used);
        } catch (\Throwable $e) {
            $this->error($label . ' : ADA tapi GAGAL didekripsi — APP_KEY kemungkinan berubah. Simpan ulang.' . $suffix);

            return;
        }
        $n = strlen($dec);
        $mask = $n <= 8 ? str_repeat('*', $n) : substr($dec, 0, 4) . str_repeat('*', max(4, $n - 8)) . substr($dec, -4);
        $ws = ($dec !== trim($dec)) ? ' <-- ADA SPASI/whitespace!' : '';
        $this->line($label . ' : ' . $mask . ' (panjang ' . $n . ')' . $ws . $suffix);
    }
}
