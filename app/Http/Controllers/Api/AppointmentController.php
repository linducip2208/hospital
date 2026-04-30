<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Appointment::with(['patient:id,name,phone', 'doctor:id,name,specialization', 'treatment:id,name,price']);

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        return response()->json(
            $query->latest('appointment_date')->paginate($request->get('per_page', 15))
        );
    }

    public function calendar(Request $request): JsonResponse
    {
        $query = Appointment::with(['patient:id,name', 'doctor:id,name']);

        if ($request->has('start')) {
            $query->where('appointment_date', '>=', $request->start);
        }

        if ($request->has('end')) {
            $query->where('appointment_date', '<=', $request->end);
        }

        $appointments = $query->get()->map(fn ($a) => [
            'id' => $a->id,
            'title' => $a->patient->name . ' - ' . ($a->doctor->name ?? ''),
            'start' => $a->appointment_date?->format('Y-m-d') . 'T' . ($a->start_time?->format('H:i:s') ?? '00:00:00'),
            'end' => $a->appointment_date?->format('Y-m-d') . 'T' . ($a->end_time?->format('H:i:s') ?? '23:59:00'),
            'status' => $a->status,
            'backgroundColor' => match ($a->status) {
                'scheduled' => '#3b82f6',
                'confirmed' => '#10b981',
                'in_progress' => '#f59e0b',
                'completed' => '#6b7280',
                'cancelled' => '#ef4444',
                'no_show' => '#dc2626',
                default => '#3b82f6',
            },
        ]);

        return response()->json($appointments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'nullable|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
            'reminders' => 'nullable|array',
        ]);

        $appointment = Appointment::create($validated);
        $appointment->load(['patient:id,name,phone', 'doctor:id,name,specialization']);

        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment): JsonResponse
    {
        $appointment->load(['patient', 'doctor', 'treatment', 'medicalRecord', 'payments']);
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'appointment_date' => 'sometimes|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'nullable|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
            'reminders' => 'nullable|array',
        ]);

        $appointment->update($validated);
        $appointment->load(['patient:id,name,phone', 'doctor:id,name,specialization']);

        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();
        return response()->json(null, 204);
    }
}
