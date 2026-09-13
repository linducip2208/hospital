<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Services\DocumentNumberService;
use App\Services\EncounterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EncounterController extends Controller
{
    public function index(): View
    {
        $this->ensurePermission('encounters.manage');
        $encounters = Encounter::with(['patient', 'polyclinic', 'doctor'])->latest()->paginate(20);
        return view('encounters.index', compact('encounters'));
    }

    public function create(): View
    {
        $this->ensurePermission('encounters.manage');
        return view('encounters.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->limit(500)->get(),
            'polyclinics' => Polyclinic::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensurePermission('encounters.manage');
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'polyclinic_id' => 'nullable|exists:polyclinics,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'encounter_type' => 'required|in:outpatient,inpatient,emergency,telemedicine,surgery',
            'payer_type' => 'required|in:general,bpjs,insurance,corporate',
        ]);
        $data += [
            'encounter_no' => app(DocumentNumberService::class)->next('encounter', 'ENC'),
            'status' => 'registered',
            'created_by' => auth()->id(),
        ];
        $encounter = Encounter::create($data);

        return redirect()->route('encounters.show', $encounter)->with('success', 'Encounter berhasil dibuat.');
    }

    public function show(Encounter $encounter): View
    {
        $this->ensurePermission('encounters.manage');
        $encounter->load(['patient', 'polyclinic', 'doctor', 'diagnoses', 'clinicalOrders.items', 'charges', 'bills']);
        return view('encounters.show', compact('encounter'));
    }

    public function start(Encounter $encounter, EncounterService $service): RedirectResponse
    {
        $service->start($encounter);
        return back()->with('success', 'Encounter dibuka untuk pemeriksaan.');
    }

    public function complete(Encounter $encounter, EncounterService $service): RedirectResponse
    {
        $service->complete($encounter);
        return back()->with('success', 'Encounter ditandai selesai.');
    }

    public function addDiagnosis(Request $request, Encounter $encounter): RedirectResponse
    {
        $this->ensurePermission('diagnoses.manage');
        $data = $request->validate([
            'icd10_code' => 'required|string|max:20',
            'diagnosis_name' => 'required|string|max:255',
            'diagnosis_type' => 'required|in:primary,secondary,differential',
            'is_confirmed' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ]);
        if ($data['diagnosis_type'] === 'primary' && $encounter->diagnoses()->where('diagnosis_type', 'primary')->exists()) {
            return back()->withErrors(['diagnosis_type' => 'Encounter ini sudah memiliki diagnosis primer.']);
        }
        Diagnosis::create($data + ['encounter_id' => $encounter->id, 'patient_id' => $encounter->patient_id]);
        return back()->with('success', 'Diagnosis tersimpan pada encounter.');
    }
}
