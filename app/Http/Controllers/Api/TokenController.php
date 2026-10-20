<?php

namespace App\Http\Controllers\Api;

use App\Models\ApiToken;
use App\Models\Patient;
use App\Models\PatientApiToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenController
{
    private const DEFAULT_EXPIRY_DAYS = 30;
    private const MAX_EXPIRY_DAYS = 90;

    public function store(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:100'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:' . self::MAX_EXPIRY_DAYS],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        return response()->json($this->issueStaffToken($user, $credentials), 201);
    }

    public function refresh(Request $request): JsonResponse
    {
        $plainToken = $request->bearerToken();
        if (! $plainToken) {
            return response()->json(['message' => 'Bearer token diperlukan.'], 401);
        }
        $hash = hash('sha256', $plainToken);

        $staffToken = ApiToken::query()->with('user')->where('token_hash', $hash)->first();
        if ($staffToken && ! $staffToken->user?->trashed()) {
            if ($staffToken->expires_at !== null && $staffToken->expires_at->isPast()) {
                return response()->json(['message' => 'Token kedaluwarsa. Silakan login ulang.'], 401);
            }
            $user = $staffToken->user;
            $staffToken->delete();

            return response()->json($this->issueStaffToken($user, ['name' => $staffToken->name]), 201);
        }

        $patientToken = PatientApiToken::query()->with('patient')->where('token_hash', $hash)->first();
        if ($patientToken && $patientToken->patient) {
            if ($patientToken->isExpired()) {
                return response()->json(['message' => 'Token kedaluwarsa. Silakan login ulang.'], 401);
            }
            $patient = $patientToken->patient;
            $patientToken->delete();

            return response()->json($this->issuePatientToken($patient, ['name' => $patientToken->name]), 201);
        }

        return response()->json(['message' => 'Token tidak valid atau sudah tidak aktif.'], 401);
    }

    public function destroy(Request $request, ApiToken $token): JsonResponse
    {
        abort_unless($token->user_id === $request->user()->id, 403);
        $token->delete();

        return response()->json(null, 204);
    }

    public function destroyPatientCurrent(Request $request): JsonResponse
    {
        $patient = Auth::guard('patient')->user();
        abort_unless($patient, 403);
        $hash = hash('sha256', (string) $request->bearerToken());
        PatientApiToken::where('token_hash', $hash)->where('patient_id', $patient->id)->delete();

        return response()->json(null, 204);
    }

    public function storePatient(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:100'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:' . self::MAX_EXPIRY_DAYS],
        ]);

        $patient = Patient::where('email', $credentials['email'])->first();
        if (! $patient || ! $patient->password || ! Hash::check($credentials['password'], $patient->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }
        if (! $patient->is_active) {
            return response()->json(['message' => 'Akun pasien tidak aktif. Hubungi administrasi.'], 403);
        }

        $patient->forceFill(['portal_last_login_at' => now()])->saveQuietly();

        return response()->json($this->issuePatientToken($patient, $credentials), 201);
    }

    public function registerPatient(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:patients,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $patient = Patient::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'is_active' => true,
        ]);

        return response()->json($this->issuePatientToken($patient, [
            'name' => $validated['device_name'] ?? 'flutter-patient',
        ]), 201);
    }

    private function expiry(Request|array $input): ?\Carbon\Carbon
    {
        $days = $input instanceof Request ? $input->get('expires_in_days') : ($input['expires_in_days'] ?? null);

        return now()->addDays(min(max((int) ($days ?? self::DEFAULT_EXPIRY_DAYS), 1), self::MAX_EXPIRY_DAYS));
    }

    private function issueStaffToken(User $user, array $input): array
    {
        $plainToken = Str::random(64);
        $token = ApiToken::create([
            'user_id' => $user->id,
            'name' => $input['name'] ?? 'flutter-staff',
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => $this->expiry($input),
        ]);

        return [
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at,
            'user' => $user->only(['id', 'name', 'email', 'role']),
        ];
    }

    private function issuePatientToken(Patient $patient, array $input): array
    {
        $plainToken = Str::random(64);
        $token = PatientApiToken::create([
            'patient_id' => $patient->id,
            'name' => $input['name'] ?? 'flutter-patient',
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => $this->expiry($input),
        ]);

        return [
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at,
            'patient' => $patient->only(['id', 'name', 'email', 'phone']),
        ];
    }
}
