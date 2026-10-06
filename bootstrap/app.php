<?php

use App\Http\Middleware\CheckPoliAccess;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\AuthenticateApi;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\RequirePair;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', SecurityHeaders::class);
        $middleware->appendToGroup('web', RequirePair::class);

        $middleware->alias([
            'api.auth' => AuthenticateApi::class,
            'role' => CheckRole::class,
            'poli.access' => CheckPoliAccess::class,
            'permission' => CheckPermission::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('portal', 'portal/*')) {
                return route('portal.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Jangan pernah membocorkan detail SQL/koneksi ke response HTTP.
        // Full trace tetap masuk ke log server untuk developer.
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            $sqlState = $e->getPrevious()?->getCode();
            $isSchemaGap = in_array($sqlState, ['42S02', '42S22'], true)
                || str_contains($e->getMessage(), "doesn't exist")
                || str_contains($e->getMessage(), 'Unknown column');

            \Illuminate\Support\Facades\Log::error('Database query failed', [
                'sql_state' => $sqlState,
                'schema_gap' => $isSchemaGap,
                'route' => $request->path(),
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $isSchemaGap
                        ? 'Skema database belum lengkap. Jalankan migrasi: php artisan migrate --force.'
                        : 'Layanan backend sedang gangguan. Coba lagi nanti.',
                ], 500);
            }

            // Production: halaman 500 generik (tanpa trace SQL).
            // Local/debug: biarkan handler bawaan tampil agar developer bisa debug.
            if (! config('app.debug')) {
                abort(500, $isSchemaGap
                    ? 'Skema database belum lengkap. Hubungi administrator untuk menjalankan migrasi.'
                    : 'Terjadi kesalahan sistem. Coba lagi nanti.');
            }

            return null;
        });
    })->create();
