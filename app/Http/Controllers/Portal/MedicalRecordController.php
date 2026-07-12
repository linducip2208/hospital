<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function index(): View
    {
        $patient = Auth::guard('patient')->user();

        $records = MedicalRecord::with('doctor', 'appointment')
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('portal.medical-records.index', compact('records'));
    }

    public function show(MedicalRecord $medicalRecord): View
    {
        abort_unless($medicalRecord->patient_id === Auth::guard('patient')->id(), 403);
        $medicalRecord->load('doctor', 'appointment');

        return view('portal.medical-records.show', compact('medicalRecord'));
    }
}
