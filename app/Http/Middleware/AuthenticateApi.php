<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use App\Models\PatientApiToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): Response
    {
        // Backward-compatible browser access is opt-in and disabled in production by default.
        if (config('api.allow_session', false) && Auth::guard('web')->check()) {
            return $next($request);
        }

        $plainToken = $request->bearerToken();
        if (! $plainToken) {
            return $this->unauthorized('Bearer token diperlukan.');
        }
        $hash = hash('sha256', $plainToken);

        $staffToken = ApiToken::query()->with('user')->where('token_hash', $hash)->first();
        if ($staffToken) {
            if ($staffToken->expires_at !== null && $staffToken->expires_at->isPast()) {
                return $this->unauthorized('Token kedaluwarsa. Silakan login ulang atau refresh token.');
            }
            if (! $staffToken->user || $staffToken->user->trashed()) {
                return $this->unauthorized('Token tidak valid atau sudah tidak aktif.');
            }
            Auth::guard('web')->setUser($staffToken->user);
            $staffToken->forceFill(['last_used_at' => now()])->saveQuietly();

            return $next($request);
        }

        $patientToken = PatientApiToken::query()->with('patient')->where('token_hash', $hash)->first();
        if ($patientToken) {
            if ($patientToken->isExpired()) {
                return $this->unauthorized('Token kedaluwarsa. Silakan login ulang atau refresh token.');
            }
            if (! $patientToken->patient || ! $patientToken->patient->is_active) {
                return $this->unauthorized('Token tidak valid atau akun pasien tidak aktif.');
            }
            Auth::guard('patient')->setUser($patientToken->patient);
            $patientToken->forceFill(['last_used_at' => now()])->saveQuietly();

            return $next($request);
        }

        return $this->unauthorized('Token tidak valid atau sudah tidak aktif.');
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json(['message' => $message], 401, [
            'WWW-Authenticate' => 'Bearer',
        ]);
    }
}
