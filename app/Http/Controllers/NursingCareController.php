<?php

namespace App\Http\Controllers;

use App\Models\NursingCare;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NursingCareController extends Controller
{
    public function index(Request $request): View
    {
        $query = NursingCare::with(['patient', 'nurse']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $nursingCares = $query->latest('care_date')->paginate(15);
        return view('nursing-cares.index', compact('nursingCares'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('nursing-cares.create', compact('patients', 'nurses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'care_date' => 'required|date',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'implementation' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        NursingCare::create($validated);
        return redirect()->route('nursing-cares.index')->with('success', 'Asuhan keperawatan berhasil dicatat.');
    }

    public function show(NursingCare $nursingCare): View
    {
        $nursingCare->load(['patient', 'nurse']);
        return view('nursing-cares.show', compact('nursingCare'));
    }

    public function edit(NursingCare $nursingCare): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('nursing-cares.edit', compact('nursingCare', 'patients', 'nurses'));
    }

    public function update(Request $request, NursingCare $nursingCare): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'care_date' => 'required|date',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'implementation' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $nursingCare->update($validated);
        return redirect()->route('nursing-cares.index')->with('success', 'Asuhan keperawatan berhasil diperbarui.');
    }

    public function destroy(NursingCare $nursingCare): RedirectResponse
    {
        $nursingCare->delete();
        return redirect()->route('nursing-cares.index')->with('success', 'Asuhan keperawatan berhasil dihapus.');
    }
}
