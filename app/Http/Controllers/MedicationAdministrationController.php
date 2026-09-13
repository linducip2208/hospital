<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\MedicationAdministration;
use App\Models\Patient;
use App\Models\User;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicationAdministrationController extends Controller
{
    public function index(Request $request): View
    {
        $query = MedicationAdministration::with(['patient', 'nurse', 'drug']);
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($date = $request->get('date')) {
            $query->whereDate('administered_at', $date);
        }
        $medications = $query->latest('administered_at')->paginate(15);
        return view('medication-administrations.index', compact('medications'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $drugs = Drug::orderBy('name')->get();
        return view('medication-administrations.create', compact('patients', 'nurses', 'drugs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'drug_id' => 'nullable|exists:drugs,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'prescription_item_id' => 'nullable|exists:prescription_items,id',
            'drug_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'administered_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        if (! empty($validated['prescription_id'])) {
            $prescription = Prescription::findOrFail($validated['prescription_id']);
            abort_unless($prescription->patient_id === (int) $validated['patient_id'], 422, 'Resep bukan milik pasien ini.');
            $validated['encounter_id'] ??= $prescription->encounter_id;
        }
        MedicationAdministration::create($validated);
        return redirect()->route('medication-administrations.index')->with('success', 'Pemberian obat berhasil dicatat.');
    }

    public function show(MedicationAdministration $medicationAdministration): View
    {
        $medicationAdministration->load(['patient', 'nurse', 'drug']);
        return view('medication-administrations.show', compact('medicationAdministration'));
    }

    public function edit(MedicationAdministration $medicationAdministration): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $drugs = Drug::orderBy('name')->get();
        return view('medication-administrations.edit', compact('medicationAdministration', 'patients', 'nurses', 'drugs'));
    }

    public function update(Request $request, MedicationAdministration $medicationAdministration): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:users,id',
            'drug_id' => 'nullable|exists:drugs,id',
            'drug_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'administered_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $medicationAdministration->update($validated);
        return redirect()->route('medication-administrations.index')->with('success', 'Pemberian obat berhasil diperbarui.');
    }

    public function destroy(MedicationAdministration $medicationAdministration): RedirectResponse
    {
        $medicationAdministration->delete();
        return redirect()->route('medication-administrations.index')->with('success', 'Pemberian obat berhasil dihapus.');
    }
}
