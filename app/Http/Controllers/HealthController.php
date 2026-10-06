<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HealthController extends Controller
{
    public function live(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => config('app.name'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function ready(): JsonResponse
    {
        $checks = [
            'database' => false,
            'schema' => false,
            'storage' => $this->canWriteStorage(),
            'cache' => false,
        ];

        try {
            DB::connection()->getPdo()->query('select 1');
            $checks['database'] = true;
            // Schema check: tabel kritis harus ada, kalau tidak berarti migrasi
            // di database tersebut (mis. sql_hospital) belum dijalankan.
            // Sengaja generik — tidak membocorkan nama DB/tabel yang hilang.
            $checks['schema'] = $this->schemaIsComplete();
        } catch (\Throwable) {
            // Keep the response intentionally generic; connection details are sensitive.
        }

        try {
            cache()->put('health-check', true, now()->addSeconds(5));
            $checks['cache'] = cache()->get('health-check') === true;
        } catch (\Throwable) {
            // Cache is part of the readiness signal but never leaks the exception.
        }

        $ready = ! in_array(false, $checks, true);

        return response()->json([
            'status' => $ready ? 'ok' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $ready ? 200 : 503);
    }

    /**
     * Tabel kritis harus ada — kalau tidak, migrasi di database aktif
     * belum dijalankan (kasus: sql_hospital belum di-migrate).
     * Return bool saja, tanpa membocorkan tabel mana yang hilang.
     */
    private function schemaIsComplete(): bool
    {
        $required = [
            'users', 'patients', 'doctors', 'polyclinics',
            'doctor_polyclinic', 'queues', 'appointments', 'settings',
        ];

        try {
            foreach ($required as $table) {
                if (! Schema::hasTable($table)) {
                    return false;
                }
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    private function canWriteStorage(): bool    {
        $path = storage_path('framework/health-check-'.bin2hex(random_bytes(8)).'.tmp');

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
