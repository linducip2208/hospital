<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Smoke test: semua endpoint GET API harus bisa diakses web
 * (tidak 500/404-route). Dijalankan dengan token staf & pasien.
 */
class ApiSmokeTest extends TestCase
{
    use RefreshDatabase;

    private string $staffToken;
    private string $patientToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        $user = User::factory()->create(['role' => 'admin', 'password' => Hash::make('secret-pass')]);
        $res = $this->postJson('/api/auth/tokens', [
            'email' => $user->email, 'password' => 'secret-pass', 'name' => 'smoke',
        ]);
        $this->staffToken = $res->json('token');

        $patient = Patient::factory()->create(['password' => Hash::make('pasien123')]);
        $res = $this->postJson('/api/auth/patients/tokens', [
            'email' => $patient->email, 'password' => 'pasien123', 'name' => 'smoke',
        ]);
        $this->patientToken = $res->json('token');
    }

    private function staff()
    {
        $this->app['auth']->forgetGuards();

        return $this->withHeader('Authorization', 'Bearer ' . $this->staffToken);
    }

    private function patient()
    {
        $this->app['auth']->forgetGuards();

        return $this->withHeader('Authorization', 'Bearer ' . $this->patientToken);
    }

    public function test_semua_route_api_terdaftar_dan_bisa_diakses(): void
    {
        $failures = [];
        $checked = 0;

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (! str_starts_with($uri, 'api/v1/')) {
                continue;
            }
            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }
            if (str_contains($uri, '{')) {
                continue; // diuji terpisah dengan ID
            }
            if (str_contains($uri, 'public/queue-status')) {
                $poli = \App\Models\Polyclinic::create(['name' => 'Poli Smoke', 'code' => 'PS-01']);
                $checked++;
                $res = $this->staff()->getJson('/api/v1/public/queue-status?polyclinic_id=' . $poli->id);
                if ($res->status() !== 200) {
                    $failures[] = "GET /api/v1/public/queue-status => {$res->status()}";
                }
                continue;
            }
            $checked++;
            $res = $this->staff()->getJson('/' . $uri);
            if (! in_array($res->status(), [200, 403], true)) {
                $failures[] = "GET /{$uri} => {$res->status()}";
            }
        }

        // Endpoint mandiri pasien
        foreach (['api/v1/me', 'api/v1/me/appointments', 'api/v1/me/medical-records', 'api/v1/me/invoices'] as $uri) {
            $checked++;
            $res = $this->patient()->getJson('/' . $uri);
            if ($res->status() !== 200) {
                $failures[] = "GET /{$uri} (pasien) => {$res->status()}";
            }
        }

        // Endpoint generik: index 200 + show ID-acak 404
        foreach (array_keys(config('api_resources', [])) as $slug) {
            $checked++;
            $res = $this->staff()->getJson('/api/v1/' . $slug);
            if (! in_array($res->status(), [200, 403], true)) {
                $failures[] = "GET /api/v1/{$slug} => {$res->status()}";
            }
            $checked++;
            $res = $this->staff()->getJson("/api/v1/{$slug}/999999");
            if ($res->status() !== 404) {
                $failures[] = "GET /api/v1/{$slug}/999999 => {$res->status()} (harusnya 404)";
            }
        }

        $this->assertSame([], $failures, "Endpoint bermasalah:\n" . implode("\n", $failures));
        $this->assertGreaterThan(50, $checked, 'Terlalu sedikit endpoint terperiksa.');
    }

    public function test_show_unknown_id_returns_404_not_500(): void
    {
        foreach (array_keys(config('api_resources', [])) as $slug) {
            $res = $this->staff()->getJson("/api/v1/{$slug}/999999");
            $this->assertSame(404, $res->status(), "GET /api/v1/{$slug}/999999 => {$res->status()}");
        }
    }
}
