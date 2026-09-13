<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Treatment;
use App\Services\EncounterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::with(['patient', 'doctor', 'treatment']);
        
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($date = $request->get('date')) {
            $query->whereDate('appointment_date', $date);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('doctor', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $appointments = $query->latest()->paginate(15);
        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();
        return view('appointments.create', compact('patients', 'doctors', 'treatments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'polyclinic_id' => 'nullable|exists:polyclinics,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        Appointment::create($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment berhasil dibuat.');
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'doctor', 'treatment', 'medicalRecord', 'payments']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'treatments'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $appointment->update($validated);
        return redirect()->route('appointments.index')->with('success', 'Appointment berhasil diperbarui.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment berhasil dihapus.');
    }

    public function status(Appointment $appointment, string $status): RedirectResponse
    {
        $allowed = ['confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'];
        if (!in_array($status, $allowed)) {
            return back()->with('error', 'Status tidak valid.');
        }
        if ($status === 'confirmed') {
            app(EncounterService::class)->fromAppointment($appointment, auth()->id());
        } elseif ($status === 'in_progress') {
            $encounter = $appointment->encounter ?: app(EncounterService::class)->fromAppointment($appointment, auth()->id());
            app(EncounterService::class)->start($encounter);
        } elseif ($status === 'completed' && $appointment->encounter) {
            app(EncounterService::class)->complete($appointment->encounter);
        }
        $appointment->update(['status' => $status]);
        return back()->with('success', 'Status appointment diubah ke ' . $status . '.');
    }
}
