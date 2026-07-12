<?php

namespace App\Http\Controllers;

use App\Models\BabyImmunization;
use App\Models\Maternity;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BabyImmunizationController extends Controller
{
    public function index(Request $request): View
    {
        $query = BabyImmunization::with(['patient', 'maternity']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $babyImmunizations = $query->latest('scheduled_date')->paginate(15);
        return view('baby-immunizations.index', compact('babyImmunizations'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $maternities = Maternity::with('patient')->latest()->get();
        return view('baby-immunizations.create', compact('patients', 'maternities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'maternity_id' => 'nullable|exists:maternities,id',
            'vaccine_name' => 'required|string|max:255',
            'dose_number' => 'nullable|integer|min:1',
            'scheduled_date' => 'nullable|date',
            'administered_date' => 'nullable|date',
            'administered_by' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:255',
        ]);
        BabyImmunization::create($validated);
        return redirect()->route('baby-immunizations.index')->with('success', 'Data imunisasi bayi berhasil dicatat.');
    }

    public function show(BabyImmunization $babyImmunization): View
    {
        $babyImmunization->load(['patient', 'maternity']);
        return view('baby-immunizations.show', compact('babyImmunization'));
    }

    public function edit(BabyImmunization $babyImmunization): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $maternities = Maternity::with('patient')->latest()->get();
        return view('baby-immunizations.edit', compact('babyImmunization', 'patients', 'maternities'));
    }

    public function update(Request $request, BabyImmunization $babyImmunization): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'maternity_id' => 'nullable|exists:maternities,id',
            'vaccine_name' => 'required|string|max:255',
            'dose_number' => 'nullable|integer|min:1',
            'scheduled_date' => 'nullable|date',
            'administered_date' => 'nullable|date',
            'administered_by' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:255',
        ]);
        $babyImmunization->update($validated);
        return redirect()->route('baby-immunizations.index')->with('success', 'Data imunisasi bayi berhasil diperbarui.');
    }

    public function destroy(BabyImmunization $babyImmunization): RedirectResponse
    {
        $babyImmunization->delete();
        return redirect()->route('baby-immunizations.index')->with('success', 'Data imunisasi bayi berhasil dihapus.');
    }
}
