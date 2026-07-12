<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use App\Services\Clinical\DrugInteractionChecker;
use App\Services\Clinical\Icd10Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_records_model_changes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $patient = Patient::create([
            'name' => 'Uji Audit',
            'email' => 'uji@audit.test',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'event' => 'created',
            'subject_type' => Patient::class,
            'subject_id' => $patient->id,
            'category' => 'klinis',
        ]);
    }

    public function test_activity_log_page_accessible_by_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/activity-logs')->assertOk();
    }

    public function test_drug_interaction_checker_detects_major(): void
    {
        $result = app(DrugInteractionChecker::class)->check(['Warfarin', 'Aspirin']);
        $this->assertTrue($result['has_warning']);
        $this->assertNotEmpty($result['interactions']);
    }

    public function test_drug_interaction_detects_patient_allergy(): void
    {
        $patient = Patient::create([
            'name' => 'Alergi Test',
            'email' => 'alergi@test.test',
            'allergies' => 'penicillin, amoxicillin',
            'is_active' => true,
        ]);

        $result = app(DrugInteractionChecker::class)->check(['Amoxicillin 500mg'], $patient);
        $this->assertNotEmpty($result['allergies']);
    }

    public function test_icd10_search_returns_matches(): void
    {
        $results = app(Icd10Service::class)->search('hipertensi');
        $this->assertContains('Hipertensi esensial (primer)', array_values($results));
    }

    public function test_vendor_crud(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post('/vendors', [
            'name' => 'PT Alkes Jaya',
            'code' => 'VND-TEST',
            'category' => 'alkes',
            'is_active' => 1,
        ])->assertRedirect('/vendors');

        $this->assertDatabaseHas('vendors', ['code' => 'VND-TEST']);
    }

    public function test_role_dashboard_renders_for_doctor(): void
    {
        $doctor = User::factory()->create(['role' => 'doctor']);
        $this->actingAs($doctor)->get('/dashboard')->assertOk();
    }
}
