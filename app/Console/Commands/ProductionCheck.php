<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProductionCheck extends Command
{
    protected $signature = 'production:check {--strict : Fail on any production warning}';

    protected $description = 'Validasi konfigurasi dan prasyarat deployment production';

    public function handle(): int
    {
        $errors = 0;
        $warnings = 0;

        $checks = [
            ['APP_KEY tersedia', filled(config('app.key')), true],
            ['APP_DEBUG=false', config('app.debug') === false, true],
            ['APP_URL bukan placeholder', ! str_contains((string) config('app.url'), 'your-domain.example'), true],
            ['APP_URL menggunakan HTTPS', str_starts_with((string) config('app.url'), 'https://'), true],
            ['LICENSE_DEV_BYPASS=false', ! (bool) env('LICENSE_DEV_BYPASS', false), true],
            ['Queue tidak memakai sync', config('queue.default') !== 'sync', false],
            ['Cache tidak memakai array', config('cache.default') !== 'array', false],
            ['Session cookie secure', (bool) config('session.secure') === true, false],
            ['Mailer bukan log', config('mail.default') !== 'log', false],
            ['Storage writable', $this->canWrite(storage_path('framework')), true],
            ['Bootstrap cache writable', is_writable(base_path('bootstrap/cache')), true],
        ];

        foreach ($checks as [$label, $passed, $critical]) {
            if ($passed) {
                $this->line("<fg=green>PASS</> {$label}");
                continue;
            }

            $critical ? $errors++ : $warnings++;
            $this->line(sprintf('<fg=%s>%s</> %s', $critical ? 'red' : 'yellow', $critical ? 'FAIL' : 'WARN', $label));
        }

        try {
            DB::connection()->getPdo()->query('select 1');
            $this->line('<fg=green>PASS</> Database connection');
        } catch (\Throwable) {
            $errors++;
            $this->line('<fg=red>FAIL</> Database connection');
        }

        if ($this->option('strict') && $warnings > 0) {
            $errors += $warnings;
        }

        $this->newLine();
        $this->info("Production check selesai: {$errors} error, {$warnings} warning.");

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function canWrite(string $directory): bool
    {
        if (! is_dir($directory)) {
            return false;
        }

        $path = $directory.DIRECTORY_SEPARATOR.'.production-check-'.bin2hex(random_bytes(8));

        try {
            if (@file_put_contents($path, 'ok') !== 2) {
                return false;
            }

            @unlink($path);

            return true;
        } catch (\Throwable) {
            @unlink($path);

            return false;
        }
    }
}
