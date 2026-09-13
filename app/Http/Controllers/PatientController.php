<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Jobs\SyncPatientToSatuSehat;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Patient::class);
        $query = Patient::query();
        if ($search = $request->get('search')) $query->whereAny(['name', 'phone', 'nik', 'email'], 'like', "%{$search}%");
        return view('patients.index', ['patients' => $query->latest()->paginate(15)]);
    }

    public function create(): View { $this->authorize('create', Patient::class); return view('patients.create'); }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Patient::class);
        $patient = Patient::create($this->validated($request));
        if (! app()->environment('testing')) SyncPatientToSatuSehat::dispatch($patient)->afterCommit();
        return redirect()->route('patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function show(Patient $patient): View
    {
        $this->authorize('view', $patient);
        ActivityLogger::log('patient_viewed', $patient, 'Rekam pasien diakses.');
        $patient->load(['appointments.doctor', 'medicalRecords', 'payments.appointment']);
        return view('patients.show', compact('patient'));
    }

    public function clinicalTimeline(Patient $patient): View
    {
        $this->authorize('view', $patient);
        $patient->load(['encounters.polyclinic', 'encounters.doctor', 'encounters.diagnoses', 'appointments.doctor', 'medicalRecords.doctor', 'prescriptions.items', 'labTests', 'radiologies', 'payments', 'admissions']);
        $events = collect();
        $add = function (Collection $items, string $type, callable $label) use (&$events): void {
            foreach ($items as $item) $events->push(['at' => $item->created_at ?? now(), 'type' => $type, 'label' => $label($item)]);
        };
        $add($patient->encounters, 'Encounter', fn ($e) => $e->encounter_no.' / '.$e->encounter_type.' / '.$e->status);
        $add($patient->appointments, 'Appointment', fn ($a) => 'Appointment dokter '.($a->doctor?->name ?? '-').' / '.$a->status);
        $add($patient->medicalRecords, 'Medical record', fn ($r) => $r->diagnosis ?? 'Catatan klinis');
        $add($patient->prescriptions, 'Prescription', fn ($r) => $r->rx_no.' / '.$r->status);
        $add($patient->labTests, 'Laboratorium', fn ($l) => $l->test_name.' / '.$l->status);
        $add($patient->radiologies, 'Radiologi', fn ($r) => $r->examination_name.' / '.$r->status);
        $add($patient->admissions, 'Admission', fn ($a) => $a->admission_no.' / '.$a->status);
        $add($patient->payments, 'Payment', fn ($p) => $p->invoice_number.' / Rp '.number_format((float) $p->amount, 0, ',', '.'));
        return view('patients.clinical-timeline', ['patient' => $patient, 'events' => $events->sortByDesc('at')->values()]);
    }

    public function edit(Patient $patient): View { $this->authorize('update', $patient); return view('patients.edit', compact('patient')); }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $this->authorize('update', $patient);
        $patient->update($this->validated($request, $patient));
        return redirect()->route('patients.index')->with('success', 'Pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $this->authorize('delete', $patient); $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Pasien berhasil dihapus.');
    }

    public function printCard(Patient $patient): View { $this->authorize('view', $patient); return view('patients.print-card', compact('patient')); }
    public function printWristband(Patient $patient): View { $this->authorize('view', $patient); return view('patients.print-wristband', compact('patient')); }
    public function printStickers(Patient $patient): View { $this->authorize('view', $patient); return view('patients.print-stickers', compact('patient')); }

    private function validated(Request $request, ?Patient $patient = null): array
    {
        $unique = 'unique:patients,nik'.($patient ? ','.$patient->id : '');
        return $request->validate([
            'name' => 'required|string|max:255', 'email' => 'nullable|email|max:255', 'phone' => 'nullable|string|max:20', 'nik' => 'nullable|string|max:20|'.$unique,
            'bpjs_number' => 'nullable|string|max:20', 'nik_verified' => 'nullable|boolean', 'birth_date' => 'nullable|date', 'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string', 'blood_type' => 'nullable|string|max:5', 'allergies' => 'nullable|string', 'medical_history' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255', 'emergency_contact_phone' => 'nullable|string|max:20', 'notes' => 'nullable|string',
        ]);
    }
}
