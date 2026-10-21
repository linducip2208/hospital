<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Concerns\ThrottlesLoginAttempts;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    use ThrottlesLoginAttempts;

    public function showLogin(): View
    {
        return view('portal.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $ip = $request->ip() ?? 'unknown';

        if (Auth::guard('patient')->attempt($credentials, $request->boolean('remember'))) {
            self::clearAttempts($email, $ip);
            $request->session()->regenerate();

            $patient = Auth::guard('patient')->user();
            $patient->forceFill(['portal_last_login_at' => now()])->saveQuietly();

            return redirect()->intended(route('portal.dashboard'));
        }

        // Kunci 15 menit setelah 10x gagal; pesan tetap generik anti-enumerasi.
        self::registerFailedAttempt($email, $ip);

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('portal.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:patients,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $patient = Patient::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        Auth::guard('patient')->login($patient);

        return redirect()->route('portal.dashboard')->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('patient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
