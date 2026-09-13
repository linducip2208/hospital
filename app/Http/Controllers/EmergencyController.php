<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Emergency;
use App\Models\Patient;
use App\Services\EncounterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmergencyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Emergency::with(['patient', 'doctor']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($triage = $request->get('triage')) {
            $query->where('triage', $triage);
        }
        $emergencies = $query->latest()->paginate(15);
        return view('emergencies.index', compact('emergencies'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('emergencies.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'triage' => 'required|in:red,yellow,green,black',
            'arrival_mode' => 'nullable|string|max:255',
            'complaint' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $encounter = app(EncounterService::class)->forPatient(Patient::findOrFail($validated['patient_id']), $validated['doctor_id'] ?? null, auth()->id(), 'emergency');
        $validated['encounter_id'] = $encounter->id;
        $validated['arrived_at'] = now();
        $validated['triaged_at'] = now();
        Emergency::create($validated);
        return redirect()->route('emergencies.index')->with('success', 'Pasien IGD berhasil dicatat.');
    }

    public function show(Emergency $emergency): View
    {
        $emergency->load(['patient', 'doctor']);
        return view('emergencies.show', compact('emergency'));
    }

    public function edit(Emergency $emergency): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('emergencies.edit', compact('emergency', 'patients', 'doctors'));
    }

    public function update(Request $request, Emergency $emergency): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'triage' => 'nullable|string|max:255',
            'arrival_mode' => 'nullable|string|max:255',
            'complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'discharge_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $emergency->update($validated);
        return redirect()->route('emergencies.index')->with('success', 'Pasien IGD berhasil diperbarui.');
    }

    public function destroy(Emergency $emergency): RedirectResponse
    {
        $emergency->delete();
        return redirect()->route('emergencies.index')->with('success', 'Pasien IGD berhasil dihapus.');
    }

    public function printTriage(Emergency $emergency): View
    {
        $emergency->load(['patient', 'doctor']);
        return view('emergencies.print-triage', compact('emergency'));
    }
}
