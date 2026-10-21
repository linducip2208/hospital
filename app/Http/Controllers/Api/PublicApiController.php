<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientFeedback;
use App\Models\Polyclinic;
use App\Models\Queue;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoint PUBLIK tanpa auth untuk aplikasi Flutter/pendaftaran mandiri.
 * Prinsip: baca = data non-sensitif yang disaring; tulis = throttle ketat,
 * validasi ketat, tanpa akses ke data internal lain.
 */
class PublicApiController extends Controller
{
    public function polyclinics(Request $request): JsonResponse
    {
        $polyclinics = Polyclinic::query()
            ->select(['id', 'name', 'code', 'description', 'floor', 'phone'])
            ->when($request->get('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(min(max((int) $request->get('per_page', 20), 1), 100));

        return response()->json($polyclinics);
    }

    public function doctors(Request $request): JsonResponse
    {
        $doctors = Doctor::query()
            ->select(['id', 'name', 'specialization', 'consultation_fee', 'status'])
            ->when($request->get('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->get('specialization'), fn ($q, $s) => $q->where('specialization', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(min(max((int) $request->get('per_page', 20), 1), 100));

        return response()->json($doctors);
    }

    public function queueStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'date' => 'nullable|date',
        ]);

        $queues = Queue::query()
            ->select(['queue_number', 'status', 'called_at'])
            ->where('polyclinic_id', $validated['polyclinic_id'])
            ->when($validated['date'] ?? null, fn ($q, $d) => $q->whereDate('created_at', $d), fn ($q) => $q->whereDate('created_at', today()))
            ->orderBy('queue_number')
            ->limit(200)
            ->get();

        return response()->json(['data' => $queues]);
    }

    public function bookAppointment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'nik' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'polyclinic_id' => 'nullable|exists:polyclinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'complaint' => 'nullable|string',
        ]);

        $patient = null;
        if (! empty($validated['nik'])) {
            $patient = Patient::where('nik', $validated['nik'])->first();
        }
        if (! $patient && ! empty($validated['email'])) {
            $patient = Patient::where('email', $validated['email'])->first();
        }
        if (! $patient) {
            $patient = Patient::where('phone', $validated['phone'])->where('name', $validated['name'])->first();
        }
        if (! $patient) {
            $patient = Patient::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'nik' => $validated['nik'] ?? null,
                'email' => $validated['email'] ?? null,
                'is_active' => true,
            ]);
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $validated['doctor_id'],
            'polyclinic_id' => $validated['polyclinic_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'] ?? null,
            'status' => 'scheduled',
            'complaint' => $validated['complaint'] ?? null,
        ]);
        ActivityLogger::log('created', $appointment, null, ['via' => 'api-public', 'resource' => 'appointments']);

        return response()->json($appointment->load(['doctor:id,name,specialization']), 201);
    }

    public function ambulanceCall(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'pickup_location' => 'required|string|max:500',
            'destination' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:1000',
        ]);

        $ambulance = Ambulance::where('status', 'available')->orderBy('id')->first();
        if (! $ambulance) {
            return response()->json(['message' => 'Armada ambulans sedang tidak tersedia. Hubungi IGD langsung.'], 422);
        }

        $contact = trim((string) ($validated['contact_phone'] ?? ''));
        $notes = trim(trim((string) ($validated['notes'] ?? '')).($contact !== '' ? ' [Kontak: '.$contact.']' : ''));
        $call = AmbulanceCall::create([
            'ambulance_id' => $ambulance->id,
            'patient_name' => $validated['patient_name'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'] ?? null,
            'call_date' => now(),
            'status' => 'pending',
            'notes' => $notes !== '' ? $notes : null,
        ]);
        ActivityLogger::log('created', $call, null, ['via' => 'api-public', 'resource' => 'ambulance-calls']);

        return response()->json(['id' => $call->id, 'status' => $call->status, 'call_date' => $call->call_date], 201);
    }

    public function feedback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating_overall' => 'required|integer|min:1|max:5',
            'rating_doctor' => 'nullable|integer|min:1|max:5',
            'rating_nurse' => 'nullable|integer|min:1|max:5',
            'rating_facility' => 'nullable|integer|min:1|max:5',
            'service_type' => 'nullable|string|max:100',
            'suggestion' => 'nullable|string|max:2000',
            'positive' => 'nullable|string|max:2000',
            'is_anonymous' => 'nullable|boolean',
            'respondent_name' => 'nullable|string|max:255',
            'respondent_contact' => 'nullable|string|max:50',
        ]);

        $feedback = PatientFeedback::create($validated + ['status' => 'new']);

        return response()->json(['id' => $feedback->id, 'message' => 'Terima kasih atas masukan Anda.'], 201);
    }
}
