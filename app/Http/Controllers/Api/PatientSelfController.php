<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientSelfController extends Controller
{
    private function patient(): \App\Models\Patient
    {
        return Auth::guard('patient')->user();
    }

    public function me(): JsonResponse
    {
        $patient = $this->patient();
        $payload = $patient->toArray();
        unset($payload['password'], $payload['remember_token']);

        return response()->json($payload);
    }

    public function appointments(Request $request): JsonResponse
    {
        $appointments = Appointment::with(['doctor:id,name,specialization', 'polyclinic:id,name', 'treatment:id,name,price'])
            ->where('patient_id', $this->patient()->id)
            ->latest('appointment_date')
            ->paginate(min(max((int) $request->get('per_page', 15), 1), 100));

        return response()->json($appointments);
    }

    public function showAppointment(Appointment $appointment): JsonResponse
    {
        abort_unless($appointment->patient_id === $this->patient()->id, 403);
        $appointment->load(['doctor', 'polyclinic', 'treatment', 'medicalRecord']);

        return response()->json($appointment);
    }

    public function bookAppointment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'polyclinic_id' => 'nullable|exists:polyclinics,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated + [
            'patient_id' => $this->patient()->id,
            'status' => 'scheduled',
        ]);
        $appointment->load(['doctor:id,name,specialization', 'polyclinic:id,name']);

        return response()->json($appointment, 201);
    }

    public function medicalRecords(Request $request): JsonResponse
    {
        $records = MedicalRecord::with(['doctor:id,name', 'appointment:id,appointment_date'])
            ->where('patient_id', $this->patient()->id)
            ->latest()
            ->paginate(min(max((int) $request->get('per_page', 15), 1), 100));

        return response()->json($records);
    }

    public function showMedicalRecord(MedicalRecord $medicalRecord): JsonResponse
    {
        abort_unless($medicalRecord->patient_id === $this->patient()->id, 403);
        $medicalRecord->load(['doctor', 'appointment', 'prescriptions']);

        return response()->json($medicalRecord);
    }

    public function invoices(Request $request): JsonResponse
    {
        $invoices = Payment::with('appointment:id,appointment_date')
            ->where('patient_id', $this->patient()->id)
            ->orderByDesc('created_at')
            ->paginate(min(max((int) $request->get('per_page', 15), 1), 100));

        return response()->json($invoices);
    }

    public function showInvoice(Payment $invoice): JsonResponse
    {
        abort_unless($invoice->patient_id === $this->patient()->id, 403);
        $invoice->load(['appointment', 'patient:id,name']);

        return response()->json($invoice);
    }
}
