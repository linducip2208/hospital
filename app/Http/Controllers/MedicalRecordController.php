<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Services\EncounterService;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MedicalRecord::class);
        $query = MedicalRecord::with(['patient', 'doctor', 'appointment']);
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhere('diagnosis', 'like', "%{$search}%");
        }
        $records = $query->latest()->paginate(15);

        return view('medical-records.index', compact('records'));
    }

    public function create(): View
    {
        $this->authorize('create', MedicalRecord::class);
        // Limit dropdown agar page tidak hang dengan 10K+ data
        $patients = Patient::where('is_active', true)->orderBy('name')->limit(500)->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->limit(200)->get();
        $appointments = Appointment::whereIn('status', ['in_progress', 'completed', 'confirmed'])->orderBy('appointment_date', 'desc')->limit(500)->get();

        return view('medical-records.create', compact('patients', 'doctors', 'appointments'));
    }

    public function store(Request $request, EncounterService $encounters): RedirectResponse
    {
        $this->authorize('create', MedicalRecord::class);
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'diagnosis' => 'required|string',
            'icd10_code' => 'nullable|string|max:10',
            'icd10_name' => 'nullable|string|max:255',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $record = DB::transaction(function () use ($validated, $encounters) {
            $data = $validated;
            $appointment = ! empty($data['appointment_id']) ? Appointment::findOrFail($data['appointment_id']) : null;
            if ($appointment) {
                abort_unless($appointment->patient_id === (int) $data['patient_id'], 422, 'Pasien rekam medis tidak sama dengan appointment.');
                $data['encounter_id'] ??= $encounters->fromAppointment($appointment, auth()->id())->id;
            }
            if (empty($data['encounter_id'])) {
                $data['encounter_id'] = $encounters->forPatient(Patient::findOrFail($data['patient_id']), $data['doctor_id'], auth()->id())->id;
            }
            $record = MedicalRecord::create($data);
            if (! empty($data['icd10_code']) && ! empty($data['diagnosis'])) {
                $record->diagnoses()->create([
                    'encounter_id' => $data['encounter_id'], 'patient_id' => $data['patient_id'], 'doctor_id' => $data['doctor_id'],
                    'icd10_code' => $data['icd10_code'], 'diagnosis_name' => $data['icd10_name'] ?: $data['diagnosis'],
                    'diagnosis_type' => 'primary', 'is_confirmed' => true,
                ]);
            }
            return $record;
        });

        // Keep the existing redirect contract; the detail page remains available through the returned record link.
        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan ke encounter.');
    }

    public function show(MedicalRecord $medicalRecord): View
    {
        $this->authorize('view', $medicalRecord);
        ActivityLogger::log('medical_record_viewed', $medicalRecord, 'Rekam medis diakses.');
        $medicalRecord->load(['patient', 'doctor', 'appointment', 'encounter', 'diagnoses', 'prescriptions', 'labTests', 'radiologies']);

        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord): View
    {
        $this->authorize('update', $medicalRecord);
        // Limit dropdown agar page tidak hang dengan 10K+ data
        $patients = Patient::where('is_active', true)->orderBy('name')->limit(500)->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->limit(200)->get();
        $appointments = Appointment::whereIn('status', ['in_progress', 'completed', 'confirmed'])->orderBy('appointment_date', 'desc')->limit(500)->get();

        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors', 'appointments'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('update', $medicalRecord);
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'diagnosis' => 'required|string',
            'icd10_code' => 'nullable|string|max:10',
            'icd10_name' => 'nullable|string|max:255',
            'action' => 'nullable|string',
            'medicine' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        abort_if($medicalRecord->status === 'finalized', 422, 'Rekam medis sudah finalized; gunakan amendment/version history.');
        $medicalRecord->update($validated);

        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function finalize(MedicalRecord $medicalRecord): RedirectResponse
    {
        $this->authorize('finalize', $medicalRecord);
        abort_if($medicalRecord->status === 'finalized', 422, 'Rekam medis sudah finalized.');
        $medicalRecord->update(['status' => 'finalized', 'finalized_by' => auth()->id(), 'finalized_at' => now()]);
        return back()->with('success', 'Rekam medis telah ditandatangani dan dikunci.');
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
