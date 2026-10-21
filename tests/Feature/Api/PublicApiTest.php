<?php

namespace Tests\Feature\Api;

use App\Models\Ambulance;
use App\Models\Doctor;
use App\Models\Polyclinic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_publik_bisa_diakses_tanpa_token(): void
    {
        Polyclinic::create(['name' => 'Poli Umum', 'code' => 'PU-01', 'phone' => '021']);
        Doctor::factory()->create(['name' => 'Dr. Publik', 'specialization' => 'Umum']);

        $this->getJson('/api/v1/public/polyclinics')->assertOk()->assertJsonFragment(['name' => 'Poli Umum']);
        $this->getJson('/api/v1/public/doctors?search=Publik')->assertOk()->assertJsonFragment(['name' => 'Dr. Publik']);

        // Data sensitif dokter tidak bocor
        $res = $this->getJson('/api/v1/public/doctors');
        foreach ($res->json('data') as $row) {
            $this->assertArrayNotHasKey('email', $row);
            $this->assertArrayNotHasKey('phone', $row);
            $this->assertArrayNotHasKey('address', $row);
        }
    }

    public function test_booking_tamu_membuat_pasien_dan_appointment(): void
    {
        $doctor = Doctor::factory()->create();
        $res = $this->postJson('/api/v1/public/appointments', [
            'name' => 'Tamu Booking', 'phone' => '0812000001',
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'complaint' => 'Batuk',
        ]);
        $res->assertCreated()->assertJsonFragment(['status' => 'scheduled']);
        $this->assertDatabaseHas('patients', ['phone' => '0812000001']);
        $this->assertDatabaseHas('appointments', ['id' => $res->json('id'), 'status' => 'scheduled']);

        // Validasi tetap jalan (tanggal lampau ditolak)
        $this->postJson('/api/v1/public/appointments', [
            'name' => 'X', 'phone' => '0812000002',
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->subDay()->format('Y-m-d'),
        ])->assertUnprocessable();
    }

    public function test_panggilan_ambulans_dan_feedback_publik(): void
    {
        Ambulance::create(['vehicle_number' => 'B-1234-AMB', 'status' => 'available']);
        $this->postJson('/api/v1/public/ambulance-calls', [
            'patient_name' => 'Darurat', 'pickup_location' => 'Jl. Mawar 1',
            'contact_phone' => '0812999999',
        ])->assertCreated()->assertJsonFragment(['status' => 'pending']);

        $this->postJson('/api/v1/public/patient-feedbacks', [
            'rating_overall' => 5, 'suggestion' => 'Pelayanan baik',
        ])->assertCreated();

        // Tanpa token tetap tidak bisa sentuh endpoint staf
        $this->getJson('/api/v1/drugs')->assertUnauthorized();
        $this->postJson('/api/v1/drugs', ['name' => 'X'])->assertUnauthorized();
    }
}
