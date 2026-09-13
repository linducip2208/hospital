<?php

namespace App\Services;

use App\Models\Radiology;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RadiologyWorkflowService
{
    public function start(Radiology $radiology): Radiology
    {
        return DB::transaction(function () use ($radiology) {
            $radiology = Radiology::lockForUpdate()->findOrFail($radiology->id);
            if ($radiology->status !== 'requested') throw ValidationException::withMessages(['radiology' => 'Radiologi tidak dapat dimulai pada status saat ini.']);
            $radiology->update(['status' => 'in_progress']);
            return $radiology->refresh();
        });
    }

    public function verify(Radiology $radiology, array $result): Radiology
    {
        return DB::transaction(function () use ($radiology, $result) {
            $radiology = Radiology::lockForUpdate()->findOrFail($radiology->id);
            if (! in_array($radiology->status, ['requested', 'in_progress'], true)) throw ValidationException::withMessages(['radiology' => 'Radiologi tidak dapat diverifikasi pada status saat ini.']);
            $radiology->update([
                'status' => 'completed',
                'findings' => $result['findings'],
                'result_status' => 'verified',
                'radiologist_id' => $result['radiologist_id'] ?? $radiology->radiologist_id,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'pacs_reference_url' => $result['pacs_reference_url'] ?? $radiology->pacs_reference_url,
                'notes' => $result['notes'] ?? $radiology->notes,
            ]);
            return $radiology->refresh();
        });
    }
}
