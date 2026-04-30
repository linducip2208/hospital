<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = MedicalRecord::with([
            'patient:id,name,nik',
            'doctor:id,name,specialization',
            'appointment:id,appointment_date',
        ]);

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        return response()->json(
            $query->latest()->paginate($request->get('per_page', 15))
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $record = MedicalRecord::create($validated);
        $record->load(['patient:id,name', 'doctor:id,name']);

        return response()->json($record, 201);
    }

    public function show(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->load(['patient', 'doctor', 'appointment']);
        return response()->json($medicalRecord);
    }

    public function update(Request $request, MedicalRecord $medicalRecord): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $medicalRecord->update($validated);

        return response()->json($medicalRecord);
    }

    public function destroy(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->delete();
        return response()->json(null, 204);
    }
}
