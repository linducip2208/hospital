<?php

namespace App\Http\Controllers\Api;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenController
{
    public function store(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $plainToken = Str::random(64);
        ApiToken::create([
            'user_id' => $user->id,
            'name' => $credentials['name'],
            'token_hash' => hash('sha256', $plainToken),
        ]);

        return response()->json([
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'user' => $user->only(['id', 'name', 'email', 'role']),
        ], 201);
    }

    public function destroy(Request $request, ApiToken $token): JsonResponse
    {
        abort_unless($token->user_id === $request->user()->id, 403);
        $token->delete();

        return response()->json(null, 204);
    }
}
