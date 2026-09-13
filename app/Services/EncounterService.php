<?php

namespace App\Services;

use App\Jobs\SyncEncounterToSatuSehat;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\Queue;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class EncounterService
{
    public function __construct(private DocumentNumberService $numbers, private BillingService $billing) {}

    public function fromAppointment(Appointment $appointment, ?int $userId = null): Encounter
    {
        return DB::transaction(function () use ($appointment, $userId): Encounter {
            $appointment = Appointment::query()->lockForUpdate()->findOrFail($appointment->id);
            $existing = Encounter::where('appointment_id', $appointment->id)->whereNull('deleted_at')->first();
            if ($existing) {
                return $existing;
            }

            $encounter = Encounter::create([
                'encounter_no' => $this->numbers->next('encounter', 'ENC'),
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'polyclinic_id' => $appointment->polyclinic_id,
                'doctor_id' => $appointment->doctor_id,
                'created_by' => $userId,
                'encounter_type' => 'outpatient',
                'payer_type' => 'general',
                'status' => 'registered',
            ]);
            if ($appointment->treatment) {
                $this->billing->addCharge([
                    'patient_id' => $appointment->patient_id,
                    'encounter_id' => $encounter->id,
                    'source_type' => 'consultation',
                    'source_id' => $appointment->id,
                    'description' => $appointment->treatment->name,
                    'quantity' => 1,
                    'unit_price' => $appointment->treatment->price,
                    'amount' => $appointment->treatment->price,
                ]);
            }
            if (! app()->environment('testing')) SyncEncounterToSatuSehat::dispatch($encounter)->afterCommit();
            return $encounter;
        });
    }

    public function fromQueue(Queue $queue, ?int $userId = null): Encounter
    {
        return DB::transaction(function () use ($queue, $userId): Encounter {
            $queue = Queue::query()->lockForUpdate()->findOrFail($queue->id);
            if ($queue->encounter_id) {
                return Encounter::findOrFail($queue->encounter_id);
            }

            $encounter = $queue->appointment_id
                ? $this->fromAppointment($queue->appointment, $userId)
                : Encounter::create([
                    'encounter_no' => $this->numbers->next('encounter', 'ENC'),
                    'patient_id' => $queue->patient_id,
                    'polyclinic_id' => $queue->polyclinic_id,
                    'doctor_id' => $queue->doctor_id,
                    'created_by' => $userId,
                    'encounter_type' => 'outpatient',
                    'payer_type' => 'general',
                    'status' => 'waiting',
                ]);

            $queue->update(['encounter_id' => $encounter->id]);

            return $encounter;
        });
    }

    public function start(Encounter $encounter): Encounter
    {
        $encounter->update(['status' => 'in_progress', 'started_at' => $encounter->started_at ?: now()]);
        return $encounter->refresh();
    }

    public function forPatient(Patient $patient, ?int $doctorId = null, ?int $userId = null, string $encounterType = 'outpatient', ?string $payerType = null): Encounter
    {
        $encounter = Encounter::create([
            'encounter_no' => $this->numbers->next('encounter', 'ENC'),
            'patient_id' => $patient->id,
            'doctor_id' => $doctorId,
            'created_by' => $userId,
            'encounter_type' => $encounterType,
            'payer_type' => $payerType ?: ($patient->bpjs_number ? 'bpjs' : 'general'),
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
        if (! app()->environment('testing')) SyncEncounterToSatuSehat::dispatch($encounter)->afterCommit();
        return $encounter;
    }

    public function complete(Encounter $encounter): Encounter
    {
        $encounter->update(['status' => 'completed', 'ended_at' => now()]);
        return $encounter->refresh();
    }
}
