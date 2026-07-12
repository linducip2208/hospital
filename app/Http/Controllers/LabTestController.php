<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabTestController extends Controller
{
    public function index(Request $request): View
    {
        $query = LabTest::with(['patient', 'doctor']);
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($testType = $request->get('test_type')) {
            $query->where('test_type', $testType);
        }
        $labTests = $query->latest()->paginate(15);
        return view('lab-tests.index', compact('labTests'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('lab-tests.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'test_name' => 'required|string|max:255',
            'test_type' => 'nullable|string|max:255',
            'sample_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $validated['status'] = $validated['status'] ?? 'requested';
        LabTest::create($validated);
        return redirect()->route('lab-tests.index')->with('success', 'Pemeriksaan lab berhasil ditambahkan.');
    }

    public function show(LabTest $labTest): View
    {
        $labTest->load(['patient', 'doctor']);
        return view('lab-tests.show', compact('labTest'));
    }

    public function edit(LabTest $labTest): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('lab-tests.edit', compact('labTest', 'patients', 'doctors'));
    }

    public function update(Request $request, LabTest $labTest): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'test_name' => 'required|string|max:255',
            'test_type' => 'nullable|string|max:255',
            'sample_type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'results' => 'nullable|string',
            'result_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $labTest->update($validated);
        return redirect()->route('lab-tests.index')->with('success', 'Pemeriksaan lab berhasil diperbarui.');
    }

    public function destroy(LabTest $labTest): RedirectResponse
    {
        $labTest->delete();
        return redirect()->route('lab-tests.index')->with('success', 'Pemeriksaan lab berhasil dihapus.');
    }

    public function print(LabTest $labTest): View
    {
        $labTest->load(['patient', 'doctor']);
        return view('lab-tests.print', compact('labTest'));
    }
}
