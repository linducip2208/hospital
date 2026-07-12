<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientScreening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientScreeningController extends Controller
{
    public function index(Request $request): View
    {
        $query = PatientScreening::with(['patient', 'user']);
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        $screenings = $query->latest()->paginate(15)->withQueryString();
        return view('patient-screenings.index', [
            'screenings' => $screenings,
            'types' => PatientScreening::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('patient-screenings.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => PatientScreening::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['screening_no'] = $this->generateNo();
        $validated['user_id'] = Auth::id();
        $screening = PatientScreening::create($validated);
        return redirect()->route('patient-screenings.show', $screening)->with('success', 'Skrining dibuat.');
    }

    public function show(PatientScreening $patientScreening): View
    {
        $patientScreening->load(['patient', 'user']);
        return view('patient-screenings.show', ['screening' => $patientScreening]);
    }

    public function edit(PatientScreening $patientScreening): View
    {
        return view('patient-screenings.edit', [
            'screening' => $patientScreening,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => PatientScreening::TYPES,
        ]);
    }

    public function update(Request $request, PatientScreening $patientScreening): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $patientScreening->update($validated);
        return redirect()->route('patient-screenings.show', $patientScreening)->with('success', 'Skrining diperbarui.');
    }

    public function destroy(PatientScreening $patientScreening): RedirectResponse
    {
        $patientScreening->delete();
        return redirect()->route('patient-screenings.index')->with('success', 'Skrining dihapus.');
    }

    public function print(PatientScreening $patientScreening): View
    {
        $patientScreening->load(['patient', 'user']);
        return view('patient-screenings.print', ['screening' => $patientScreening]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(PatientScreening::TYPES)),
            'patient_id' => 'required|exists:patients,id',
            'screened_at' => 'required|date',
            'answers' => 'nullable|array',
            'score' => 'nullable|integer|min:0|max:1000',
            'risk_level' => 'required|in:low,moderate,high',
            'intervention' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    }

    private function generateNo(): string
    {
        $count = PatientScreening::whereDate('created_at', today())->count() + 1;
        return sprintf('SKR/%s/%04d', now()->format('Ymd'), $count);
    }
}
