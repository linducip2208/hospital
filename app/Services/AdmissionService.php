<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\BedMovement;
use App\Models\DischargeSummary;
use App\Models\Encounter;
use App\Models\HospitalBed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdmissionService
{
    public function __construct(private DocumentNumberService $numbers, private BillingService $billing)
    {
    }

    public function admit(Encounter $encounter, HospitalBed $bed): Admission
    {
        return DB::transaction(function () use ($encounter, $bed) {
            $encounter = Encounter::lockForUpdate()->findOrFail($encounter->id);
            $bed = HospitalBed::lockForUpdate()->findOrFail($bed->id);
            if ($bed->status !== 'available' || $bed->current_patient_id) {
                throw ValidationException::withMessages(['hospital_bed_id' => 'Bed tidak tersedia.']);
            }
            if (Admission::where('encounter_id', $encounter->id)->where('status', 'admitted')->exists()) {
                throw ValidationException::withMessages(['encounter' => 'Encounter sudah memiliki admission aktif.']);
            }
            $admission = Admission::create([
                'admission_no' => $this->numbers->next('admission', 'ADM'),
                'patient_id' => $encounter->patient_id,
                'encounter_id' => $encounter->id,
                'admitted_by' => auth()->id(),
                'admitted_at' => now(),
                'status' => 'admitted',
            ]);
            BedMovement::create([
                'admission_id' => $admission->id,
                'room_id' => $bed->room_id,
                'hospital_bed_id' => $bed->id,
                'moved_at' => now(),
                'moved_by' => auth()->id(),
                'reason' => 'Admission awal',
            ]);
            $bed->update(['status' => 'occupied', 'current_patient_id' => $encounter->patient_id, 'occupied_since' => now()]);
            $encounter->update(['hospital_bed_id' => $bed->id, 'room_id' => $bed->room_id, 'status' => 'admitted']);
            $roomPrice = (float) ($bed->room?->price_per_day ?? 0);
            if ($roomPrice > 0) {
                $this->billing->addCharge([
                    'patient_id' => $encounter->patient_id,
                    'encounter_id' => $encounter->id,
                    'source_type' => 'room',
                    'source_id' => $bed->id,
                    'description' => 'Rawat inap '.$bed->room?->room_number.' / '.$bed->bed_code,
                    'quantity' => 1,
                    'unit_price' => $roomPrice,
                    'amount' => $roomPrice,
                ]);
            }

            return $admission->load('bedMovements');
        });
    }

    public function discharge(Admission $admission, bool $override = false): Admission
    {
        return DB::transaction(function () use ($admission, $override) {
            $admission = Admission::with('encounter')->lockForUpdate()->findOrFail($admission->id);
            if ($admission->status !== 'admitted') {
                throw ValidationException::withMessages(['admission' => 'Admission tidak aktif.']);
            }
            $billReady = $admission->encounter?->bills()->whereNotIn('status', ['draft', 'cancelled'])->exists();
            $summaryReady = DischargeSummary::where('encounter_id', $admission->encounter_id)->where('status', 'finalized')->exists();
            $authorised = auth()->user() && in_array(auth()->user()->role, ['admin', 'director', 'developer'], true);
            if ((! $billReady || ! $summaryReady) && ! ($override && $authorised)) {
                throw ValidationException::withMessages(['admission' => 'Discharge membutuhkan final billing dan discharge summary finalized.']);
            }
            $movement = $admission->bedMovements()->latest('moved_at')->first();
            if ($movement) {
                $bed = HospitalBed::lockForUpdate()->find($movement->hospital_bed_id);
                $bed?->update(['status' => 'available', 'current_patient_id' => null, 'occupied_since' => null]);
            }
            $admission->update(['status' => 'discharged', 'discharged_at' => now(), 'discharged_by' => auth()->id()]);
            $admission->encounter?->update(['status' => 'discharged', 'ended_at' => now()]);
            if ($override && (! $billReady || ! $summaryReady)) {
                ActivityLogger::log('discharge_override', $admission, 'Discharge override oleh role berwenang.', ['bill_ready' => $billReady, 'summary_ready' => $summaryReady]);
            }
            return $admission->refresh();
        });
    }
}
