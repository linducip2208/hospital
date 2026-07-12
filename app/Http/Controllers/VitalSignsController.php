<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSignsRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VitalSignsController extends Controller
{
    public function index(Request $request): View
    {
        $query = VitalSignsRecord::with(['patient', 'nurse']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($date = $request->get('date')) {
            $query->whereDate('recorded_at', $date);
        }
        $vitalSigns = $query->latest('recorded_at')->paginate(15);
        return view('vital-signs.index', compact('vitalSigns'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('vital-signs.create', compact('patients', 'nurses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'recorded_at' => 'required|date',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'respiratory_rate' => 'nullable|integer|min:0|max:100',
            'oxygen_saturation' => 'nullable|integer|min:0|max:100',
            'blood_sugar' => 'nullable|numeric|min:0|max:1000',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'pain_level' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string',
        ]);
        VitalSignsRecord::create($validated);
        return redirect()->route('vital-signs.index')->with('success', 'Tanda vital berhasil dicatat.');
    }

    public function show(VitalSignsRecord $vitalSign): View
    {
        $vitalSign->load(['patient', 'nurse']);
        return view('vital-signs.show', compact('vitalSign'));
    }

    public function edit(VitalSignsRecord $vitalSign): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('vital-signs.edit', compact('vitalSign', 'patients', 'nurses'));
    }

    public function update(Request $request, VitalSignsRecord $vitalSign): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'recorded_at' => 'required|date',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'respiratory_rate' => 'nullable|integer|min:0|max:100',
            'oxygen_saturation' => 'nullable|integer|min:0|max:100',
            'blood_sugar' => 'nullable|numeric|min:0|max:1000',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'pain_level' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string',
        ]);
        $vitalSign->update($validated);
        return redirect()->route('vital-signs.index')->with('success', 'Tanda vital berhasil diperbarui.');
    }

    public function destroy(VitalSignsRecord $vitalSign): RedirectResponse
    {
        $vitalSign->delete();
        return redirect()->route('vital-signs.index')->with('success', 'Tanda vital berhasil dihapus.');
    }
}
