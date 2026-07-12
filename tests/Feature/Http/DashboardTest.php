<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Pasien Hari Ini');
        $response->assertSee('Dokter Aktif');
        $response->assertSee('Appointment Hari Ini');
        $response->assertSee('Pendapatan Bulan Ini');
    }

    public function test_dashboard_redirects_for_guest(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
