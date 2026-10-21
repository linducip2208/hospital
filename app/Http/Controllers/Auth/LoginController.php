<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Concerns\ThrottlesLoginAttempts;
use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    use ThrottlesLoginAttempts;

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $ip = $request->ip() ?? 'unknown';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            self::clearAttempts($email, $ip);
            $request->session()->regenerate();
            ActivityLogger::log('login');

            return redirect()->intended(route('dashboard'));
        }

        // Kunci 15 menit setelah 10x gagal; pesan tetap generik anti-enumerasi.
        self::registerFailedAttempt($email, $ip);
        if (self::isLockedOut($email, $ip)) {
            ActivityLogger::log('login_locked', null, 'Blokir brute-force login untuk '.$email);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLogger::log('logout');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
