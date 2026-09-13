<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_bearer_token_authenticates_v1_and_token_hash_is_not_returned(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => Hash::make('secret-pass')]);
        $tokenResponse = $this->postJson('/api/auth/tokens', ['email' => $user->email, 'password' => 'secret-pass', 'name' => 'integration-test']);
        $tokenResponse->assertCreated()->assertJsonMissing(['token_hash']);
        $token = $tokenResponse->json('token');
        Patient::factory()->create();

        $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/patients')->assertOk()->assertJsonCount(1, 'data');
    }
}
