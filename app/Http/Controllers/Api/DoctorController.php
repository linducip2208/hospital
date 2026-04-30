<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Doctor::query();

        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'email', 'phone', 'specialization'], 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        return response()->json(
            $query->latest()->paginate($request->get('per_page', 15))
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,on_leave',
            'notes' => 'nullable|string',
        ]);

        $doctor = Doctor::create($validated);

        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor): JsonResponse
    {
        $doctor->load(['appointments.patient', 'medicalRecords']);
        return response()->json($doctor);
    }

    public function update(Request $request, Doctor $doctor): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,on_leave',
            'notes' => 'nullable|string',
        ]);

        $doctor->update($validated);

        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();
        return response()->json(null, 204);
    }
}
