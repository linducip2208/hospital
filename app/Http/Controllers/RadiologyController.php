<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Radiology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadiologyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Radiology::with(['patient', 'doctor']);
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $radiologies = $query->latest()->paginate(15);
        return view('radiologies.index', compact('radiologies'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('radiologies.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'examination_name' => 'required|string|max:255',
            'body_part' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Radiology::create($validated);
        return redirect()->route('radiologies.index')->with('success', 'Pemeriksaan radiologi berhasil ditambahkan.');
    }

    public function show(Radiology $radiology): View
    {
        $radiology->load(['patient', 'doctor']);
        return view('radiologies.show', compact('radiology'));
    }

    public function edit(Radiology $radiology): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('radiologies.edit', compact('radiology', 'patients', 'doctors'));
    }

    public function update(Request $request, Radiology $radiology): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'examination_name' => 'required|string|max:255',
            'body_part' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'findings' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $radiology->update($validated);
        return redirect()->route('radiologies.index')->with('success', 'Pemeriksaan radiologi berhasil diperbarui.');
    }

    public function destroy(Radiology $radiology): RedirectResponse
    {
        $radiology->delete();
        return redirect()->route('radiologies.index')->with('success', 'Pemeriksaan radiologi berhasil dihapus.');
    }

    public function print(Radiology $radiology): View
    {
        $radiology->load(['patient', 'doctor']);
        return view('radiologies.print', compact('radiology'));
    }
}
