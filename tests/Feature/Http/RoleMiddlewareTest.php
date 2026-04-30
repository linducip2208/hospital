<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_protected_route(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_staff_can_access_protected_route(): void
    {
        $user = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($user)->get('/patients');

        $response->assertStatus(200);
    }

    public function test_doctor_can_access_protected_route(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);

        $response = $this->actingAs($user)->get('/appointments');

        $response->assertStatus(200);
    }
}
