<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
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

        $token = ApiToken::query()
            ->with('user')
            ->where('token_hash', hash('sha256', $plainToken))
            ->first();

        if (! $token || ! $token->user || $token->user->trashed()) {
            return $this->unauthorized('Token tidak valid atau sudah tidak aktif.');
        }

        Auth::guard('web')->setUser($token->user);
        $token->forceFill(['last_used_at' => now()])->saveQuietly();

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json(['message' => $message], 401, [
            'WWW-Authenticate' => 'Bearer',
        ]);
    }
}
