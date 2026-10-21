<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResourcePermissionTest extends TestCase
{
    use RefreshDatabase;

    private function loginAs(string $role): void
    {
        $user = User::factory()->create(['role' => $role, 'password' => Hash::make('secret-pass')]);
        $this->actingAs($user);
    }

    public function test_fail_open_tanpa_seed_permission(): void
    {
        $this->loginAs('staff');
        // Permission drugs.manage belum ada di DB → diizinkan (perilaku lama).
        $this->post('/drugs', ['name' => 'Obat FailOpen'])->assertRedirect();
        $this->assertDatabaseHas('drugs', ['name' => 'Obat FailOpen']);
    }

    public function test_peran_dibatasi_setelah_seed(): void
    {
        $this->seed(PermissionSeeder::class);

        // Staff boleh baca, tidak boleh tulis obat.
        $this->loginAs('staff');
        $this->get('/drugs')->assertOk();
        $this->post('/drugs', ['name' => 'Obat Terlarang'])->assertForbidden();
        $this->assertDatabaseMissing('drugs', ['name' => 'Obat Terlarang']);

        // Apoteker boleh tulis.
        $this->loginAs('pharmacist');
        $this->post('/drugs', ['name' => 'Paracetamol'])->assertRedirect();
        $this->assertDatabaseHas('drugs', ['name' => 'Paracetamol']);

        // Kasir tidak boleh baca gaji.
        $this->loginAs('cashier');
        $this->get('/salaries')->assertForbidden();

        // HR boleh kelola gaji.
        $this->loginAs('HR');
        $this->get('/salaries')->assertOk();

        // Admin lolos semua.
        $this->loginAs('admin');
        $this->get('/salaries')->assertOk();
        $this->post('/drugs', ['name' => 'Obat Admin'])->assertRedirect();
    }

    public function test_route_dengan_permission_eksplisit_tidak_terpengaruh(): void
    {
        $this->seed(PermissionSeeder::class);
        // Lab create/store tidak ada di config granular → perilaku lama (auth saja).
        $this->loginAs('cashier');
        $this->get('/lab-tests')->assertOk();
    }
}
