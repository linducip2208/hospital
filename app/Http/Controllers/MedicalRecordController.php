<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = MedicalRecord::with(['patient', 'doctor', 'appointment']);
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('diagnosis', 'like', "%{$search}%");
        }
        $records = $query->latest()->paginate(15);
        return view('medical-records.index', compact('records'));
    }

    public function create(): View
    {
        // Limit dropdown agar page tidak hang dengan 10K+ data
        $patients = Patient::where('is_active', true)->orderBy('name')->limit(500)->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->limit(200)->get();
        $appointments = Appointment::whereIn('status', ['in_progress', 'completed', 'confirmed'])->orderBy('appointment_date', 'desc')->limit(500)->get();
        return view('medical-records.create', compact('patients', 'doctors', 'appointments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        MedicalRecord::create($validated);
        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    public function show(MedicalRecord $medicalRecord): View
    {
        $medicalRecord->load(['patient', 'doctor', 'appointment']);
        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord): View
    {
        // Limit dropdown agar page tidak hang dengan 10K+ data
        $patients = Patient::where('is_active', true)->orderBy('name')->limit(500)->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->limit(200)->get();
        $appointments = Appointment::whereIn('status', ['in_progress', 'completed', 'confirmed'])->orderBy('appointment_date', 'desc')->limit(500)->get();
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors', 'appointments'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $medicalRecord->update($validated);
        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(MedicalRecord $medicalRecord): RedirectResponse
    {
        $medicalRecord->delete();
        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil dihapus.');
    }

    public function print(MedicalRecord $medicalRecord): View
    {
        $medicalRecord->load(['patient', 'doctor', 'appointment']);
        return view('medical-records.print', compact('medicalRecord'));
    }
}