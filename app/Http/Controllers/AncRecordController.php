<?php

namespace App\Http\Controllers;

use App\Models\AncRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AncRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = AncRecord::with(['patient', 'midwife']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($visitDate = $request->get('visit_date')) {
            $query->whereDate('visit_date', $visitDate);
        }
        $ancRecords = $query->latest('visit_date')->paginate(15);
        return view('anc-records.index', compact('ancRecords'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $midwives = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('anc-records.create', compact('patients', 'midwives'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'midwife_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'gestational_age' => 'nullable|integer|min:1|max:45',
            'fundal_height' => 'nullable|numeric|min:0|max:50',
            'fetal_presentation' => 'nullable|string|max:255',
            'fetal_heart_rate' => 'nullable|integer|min:0|max:200',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'weight' => 'nullable|numeric|min:0|max:300',
            'hemoglobin' => 'nullable|numeric|min:0|max:30',
            'urine_protein' => 'nullable|string|max:255',
            'tt_immunization' => 'nullable|string|max:255',
            'iron_folate' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        AncRecord::create($validated);
        return redirect()->route('anc-records.index')->with('success', 'Data ANC berhasil dicatat.');
    }

    public function show(AncRecord $ancRecord): View
    {
        $ancRecord->load(['patient', 'midwife']);
        return view('anc-records.show', compact('ancRecord'));
    }

    public function edit(AncRecord $ancRecord): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $midwives = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('anc-records.edit', compact('ancRecord', 'patients', 'midwives'));
    }

    public function update(Request $request, AncRecord $ancRecord): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'midwife_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'gestational_age' => 'nullable|integer|min:1|max:45',
            'fundal_height' => 'nullable|numeric|min:0|max:50',
            'fetal_presentation' => 'nullable|string|max:255',
            'fetal_heart_rate' => 'nullable|integer|min:0|max:200',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'weight' => 'nullable|numeric|min:0|max:300',
            'hemoglobin' => 'nullable|numeric|min:0|max:30',
            'urine_protein' => 'nullable|string|max:255',
            'tt_immunization' => 'nullable|string|max:255',
            'iron_folate' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $ancRecord->update($validated);
        return redirect()->route('anc-records.index')->with('success', 'Data ANC berhasil diperbarui.');
    }

    public function destroy(AncRecord $ancRecord): RedirectResponse
    {
        $ancRecord->delete();
        return redirect()->route('anc-records.index')->with('success', 'Data ANC berhasil dihapus.');
    }
}
