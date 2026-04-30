<?php

namespace Tests\Feature\Http;

use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_index(): void
    {
        MedicalRecord::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/medical-records');

        $response->assertStatus(200);
        $response->assertViewIs('medical-records.index');
    }

    public function test_create(): void
    {
        $response = $this->actingAs($this->user)->get('/medical-records/create');

        $response->assertStatus(200);
        $response->assertViewIs('medical-records.create');
    }

    public function test_store(): void
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'diagnosis' => 'Hipertensi ringan',
            'action' => 'Resep obat dan kontrol rutin',
            'medicine' => 'Amlodipine 5mg',
            'lab_results' => 'Tekanan darah 140/90',
            'notes' => 'Pasien dianjurkan diet rendah garam',
        ];

        $response = $this->actingAs($this->user)->post('/medical-records', $data);

        $response->assertRedirect('/medical-records');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('medical_records', ['diagnosis' => 'Hipertensi ringan']);
    }

    public function test_store_validation_error(): void
    {
        $response = $this->actingAs($this->user)->post('/medical-records', [
            'patient_id' => '',
            'doctor_id' => '',
            'diagnosis' => '',
        ]);

        $response->assertSessionHasErrors(['patient_id', 'doctor_id', 'diagnosis']);
    }

    public function test_show(): void
    {
        $medicalRecord = MedicalRecord::factory()->create();

        $response = $this->actingAs($this->user)->get("/medical-records/{$medicalRecord->id}");

        $response->assertStatus(200);
        $response->assertViewIs('medical-records.show');
        $response->assertSee($medicalRecord->diagnosis);
    }

    public function test_edit(): void
    {
        $medicalRecord = MedicalRecord::factory()->create();

        $response = $this->actingAs($this->user)->get("/medical-records/{$medicalRecord->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('medical-records.edit');
    }

    public function test_update(): void
    {
        $medicalRecord = MedicalRecord::factory()->create(['diagnosis' => 'Old diagnosis']);
        $newPatient = Patient::factory()->create();
        $newDoctor = Doctor::factory()->create();

        $response = $this->actingAs($this->user)->put("/medical-records/{$medicalRecord->id}", [
            'patient_id' => $newPatient->id,
            'doctor_id' => $newDoctor->id,
            'diagnosis' => 'Updated diagnosis',
            'action' => $medicalRecord->action,
            'medicine' => $medicalRecord->medicine,
            'lab_results' => $medicalRecord->lab_results,
            'notes' => $medicalRecord->notes,
        ]);

        $response->assertRedirect('/medical-records');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('medical_records', ['id' => $medicalRecord->id, 'diagnosis' => 'Updated diagnosis']);
    }

    public function test_destroy(): void
    {
        $medicalRecord = MedicalRecord::factory()->create();

        $response = $this->actingAs($this->user)->delete("/medical-records/{$medicalRecord->id}");

        $response->assertRedirect('/medical-records');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($medicalRecord);
    }

    public function test_guest_cannot_access(): void
    {
        $response = $this->get('/medical-records');

        $response->assertRedirect('/login');
    }
}
