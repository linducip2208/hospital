<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientSafetyIncident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientSafetyIncidentController extends Controller
{
    public function index(Request $request): View
    {
        $query = PatientSafetyIncident::with(['patient', 'reporter']);
        if ($t = $request->get('type')) $query->where('incident_type', $t);
        if ($s = $request->get('status')) $query->where('status', $s);
        $incidents = $query->latest()->paginate(15)->withQueryString();
        return view('patient-safety-incidents.index', [
            'incidents' => $incidents,
            'types' => PatientSafetyIncident::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('patient-safety-incidents.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => PatientSafetyIncident::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['incident_no'] = sprintf('IKP/%s/%04d', now()->format('Ymd'), PatientSafetyIncident::whereDate('created_at', today())->count() + 1);
        $validated['reporter_id'] = Auth::id();
        $validated['reported_at'] ??= now();
        PatientSafetyIncident::create($validated);
        return redirect()->route('patient-safety-incidents.index')->with('success', 'Laporan dibuat.');
    }

    public function show(PatientSafetyIncident $patientSafetyIncident): View
    {
        return view('patient-safety-incidents.show', ['incident' => $patientSafetyIncident->load(['patient', 'reporter'])]);
    }

    public function edit(PatientSafetyIncident $patientSafetyIncident): View
    {
        return view('patient-safety-incidents.edit', [
            'incident' => $patientSafetyIncident,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => PatientSafetyIncident::TYPES,
        ]);
    }

    public function update(Request $request, PatientSafetyIncident $patientSafetyIncident): RedirectResponse
    {
        $patientSafetyIncident->update($this->validateRequest($request));
        return redirect()->route('patient-safety-incidents.show', $patientSafetyIncident)->with('success', 'Diperbarui.');
    }

    public function destroy(PatientSafetyIncident $patientSafetyIncident): RedirectResponse
    {
        $patientSafetyIncident->delete();
        return redirect()->route('patient-safety-incidents.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'incident_type' => 'required|in:knc,ktc,ktd,kpc,sentinel',
            'severity' => 'required|in:none,minor,moderate,major,catastrophic',
            'patient_id' => 'nullable|exists:patients,id',
            'occurred_at' => 'required|date',
            'reported_at' => 'nullable|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'immediate_action' => 'nullable|string',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'status' => 'nullable|in:reported,investigating,closed',
        ]);
    }
}
