<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\RateLimiter;

trait ThrottlesLoginAttempts
{
    private static function lockoutKey(string $email, string $ip): string
    {
        return 'login-lockout:'.strtolower(trim($email)).'|'.$ip;
    }

    private static function isLockedOut(string $email, string $ip, int $max = 10): bool
    {
        return RateLimiter::tooManyAttempts(self::lockoutKey($email, $ip), $max);
    }

    private static function registerFailedAttempt(string $email, string $ip, int $decaySeconds = 900): void
    {
        RateLimiter::hit(self::lockoutKey($email, $ip), $decaySeconds);
    }

    private static function clearAttempts(string $email, string $ip): void
    {
        RateLimiter::clear(self::lockoutKey($email, $ip));
    }
}
