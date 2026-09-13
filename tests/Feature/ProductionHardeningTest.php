<?php

namespace Tests\Feature;

use App\Http\Middleware\RequirePair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_liveness_endpoint_is_public_and_has_security_headers(): void
    {
        $response = $this->withoutMiddleware(RequirePair::class)->getJson('/health/live');

        $response->assertOk()->assertJsonPath('status', 'ok');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_readiness_endpoint_checks_database_and_cache(): void
    {
        $response = $this->withoutMiddleware(RequirePair::class)->getJson('/health/ready');

        $response->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('checks.database', true)
            ->assertJsonPath('checks.cache', true);
    }

    public function test_api_session_access_can_be_disabled_for_production(): void
    {
        config(['api.allow_session' => false]);

        $response = $this->getJson('/api/v1/patients');

        $response->assertUnauthorized();
    }
}
