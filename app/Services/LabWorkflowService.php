<?php

namespace App\Services;

use App\Models\LabTest;
use App\Models\User;
use App\Notifications\CriticalLabResultNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LabWorkflowService
{
    public function collect(LabTest $labTest, ?string $specimen = null): LabTest
    {
        return DB::transaction(function () use ($labTest, $specimen) {
            $labTest = LabTest::lockForUpdate()->findOrFail($labTest->id);
            if (! in_array($labTest->status, ['requested', 'sample_collected'], true)) {
                throw ValidationException::withMessages(['lab_test' => 'Sampel tidak dapat dikoleksi pada status saat ini.']);
            }
            $labTest->update([
                'status' => 'sample_collected',
                'accession_no' => $labTest->accession_no ?: app(DocumentNumberService::class)->next('lab_accession', 'LAB'),
                'specimen' => $specimen ?: $labTest->specimen ?: $labTest->sample_type,
                'collected_at' => $labTest->collected_at ?: now(),
                'collected_by' => auth()->id(),
            ]);
            return $labTest->refresh();
        });
    }

    public function start(LabTest $labTest): LabTest
    {
        return DB::transaction(function () use ($labTest) {
            $labTest = LabTest::lockForUpdate()->findOrFail($labTest->id);
            if ($labTest->status !== 'sample_collected') {
                throw ValidationException::withMessages(['lab_test' => 'Lab harus memiliki sampel terkumpul sebelum diproses.']);
            }
            $labTest->update(['status' => 'in_progress']);
            return $labTest->refresh();
        });
    }

    public function verify(LabTest $labTest, array $result): LabTest
    {
        return DB::transaction(function () use ($labTest, $result) {
            $labTest = LabTest::lockForUpdate()->findOrFail($labTest->id);
            if (! in_array($labTest->status, ['sample_collected', 'in_progress'], true)) {
                throw ValidationException::withMessages(['lab_test' => 'Hasil lab tidak dapat diverifikasi pada status saat ini.']);
            }
            $labTest->update([
                'status' => 'completed',
                'results' => $result['results'],
                'result_date' => now(),
                'result_status' => 'verified',
                'reference_range' => $result['reference_range'] ?? null,
                'unit' => $result['unit'] ?? null,
                'abnormal_flag' => $result['abnormal_flag'] ?? 'normal',
                'critical_flag' => (bool) ($result['critical_flag'] ?? false),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'notes' => $result['notes'] ?? $labTest->notes,
            ]);
            $labTest = $labTest->refresh();
            if ($labTest->critical_flag) {
                User::whereIn('role', ['admin', 'director', 'doctor', 'nurse', 'laboratory'])->get()->each->notify(new CriticalLabResultNotification($labTest));
            }
            return $labTest;
        });
    }
}
