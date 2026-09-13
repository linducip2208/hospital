<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('patients.view'), 403);
        $query = Patient::query();

        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'phone', 'nik', 'email'], 'like', "%{$search}%");
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $patients = $query->latest()->paginate($perPage);
        $patients->through(fn (Patient $patient) => $this->patientPayload($patient));

        return response()->json($patients);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('patients.create'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20|unique:patients,nik',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $patient = Patient::create($validated);

        return response()->json($this->patientPayload($patient), 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        abort_unless(request()->user()?->hasPermission('patients.view'), 403);
        $patient->load(['appointments.doctor', 'medicalRecords', 'payments']);

        return response()->json($this->patientPayload($patient));
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('patients.update'), 403);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20|unique:patients,nik,'.$patient->id,
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $patient->update($validated);

        return response()->json($this->patientPayload($patient));
    }

    public function destroy(Request $request, Patient $patient): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('patients.update'), 403);
        $patient->delete();

        return response()->json(null, 204);
    }

    private function patientPayload(Patient $patient): array
    {
        $payload = $patient->toArray();
        $clinicalRoles = ['admin', 'developer', 'doctor', 'nurse', 'midwife'];

        if (! in_array(request()->user()?->role, $clinicalRoles, true)) {
            foreach ([
                'nik', 'bpjs_number', 'address', 'allergies', 'medical_history',
                'emergency_contact_name', 'emergency_contact_phone', 'notes',
            ] as $field) {
                unset($payload[$field]);
            }
        }

        return $payload;
    }
}
