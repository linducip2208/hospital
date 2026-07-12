<?php

namespace App\Http\Controllers;

use App\Models\HospitalBed;
use App\Models\IcuMonitoring;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IcuMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $query = IcuMonitoring::with(['patient', 'bed.room']);
        if ($pid = $request->get('patient_id')) {
            $query->where('patient_id', $pid);
        }
        $monitorings = $query->latest('recorded_at')->paginate(20);
        return view('icu-monitorings.index', [
            'monitorings' => $monitorings,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('icu-monitorings.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'beds' => HospitalBed::with('room')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        IcuMonitoring::create($this->validateRequest($request));
        return redirect()->route('icu-monitorings.index')->with('success', 'Pencatatan tersimpan.');
    }

    public function show(IcuMonitoring $icuMonitoring): View
    {
        return view('icu-monitorings.show', ['monitoring' => $icuMonitoring->load(['patient', 'bed.room'])]);
    }

    public function edit(IcuMonitoring $icuMonitoring): View
    {
        return view('icu-monitorings.edit', [
            'monitoring' => $icuMonitoring,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'beds' => HospitalBed::with('room')->get(),
        ]);
    }

    public function update(Request $request, IcuMonitoring $icuMonitoring): RedirectResponse
    {
        $icuMonitoring->update($this->validateRequest($request));
        return redirect()->route('icu-monitorings.show', $icuMonitoring)->with('success', 'Diperbarui.');
    }

    public function destroy(IcuMonitoring $icuMonitoring): RedirectResponse
    {
        $icuMonitoring->delete();
        return redirect()->route('icu-monitorings.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'hospital_bed_id' => 'nullable|exists:hospital_beds,id',
            'recorded_at' => 'required|date',
            'temperature' => 'nullable|numeric|min:0|max:50',
            'hr' => 'nullable|integer|min:0|max:300',
            'rr' => 'nullable|integer|min:0|max:100',
            'sbp' => 'nullable|integer|min:0|max:300',
            'dbp' => 'nullable|integer|min:0|max:200',
            'map' => 'nullable|integer|min:0|max:200',
            'spo2' => 'nullable|integer|min:0|max:100',
            'gcs' => 'nullable|integer|min:3|max:15',
            'cvp' => 'nullable|numeric|min:-50|max:50',
            'ventilator' => 'nullable|array',
            'drips' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);
    }
}
