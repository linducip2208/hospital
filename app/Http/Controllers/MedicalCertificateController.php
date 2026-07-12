<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\MedicalCertificate;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalCertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = MedicalCertificate::with(['patient', 'doctor']);
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        if ($q = $request->get('q')) {
            $query->where('cert_no', 'like', "%{$q}%");
        }
        $certificates = $query->latest()->paginate(15)->withQueryString();
        return view('medical-certificates.index', [
            'certificates' => $certificates,
            'types' => MedicalCertificate::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('medical-certificates.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'types' => MedicalCertificate::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(MedicalCertificate::TYPES)),
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'issue_date' => 'required|date',
            'rest_from' => 'nullable|date',
            'rest_until' => 'nullable|date|after_or_equal:rest_from',
            'rest_days' => 'nullable|integer|min:0|max:365',
            'diagnosis' => 'nullable|string|max:500',
            'purpose' => 'nullable|string|max:255',
            'exam_data' => 'nullable|array',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:draft,issued,cancelled',
        ]);
        $validated['cert_no'] = $this->generateNo($validated['type']);
        $validated['status'] ??= 'issued';
        $cert = MedicalCertificate::create($validated);
        return redirect()->route('medical-certificates.show', $cert)->with('success', 'Surat berhasil diterbitkan.');
    }

    public function show(MedicalCertificate $medicalCertificate): View
    {
        $medicalCertificate->load(['patient', 'doctor']);
        return view('medical-certificates.show', ['certificate' => $medicalCertificate]);
    }

    public function edit(MedicalCertificate $medicalCertificate): View
    {
        return view('medical-certificates.edit', [
            'certificate' => $medicalCertificate,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'types' => MedicalCertificate::TYPES,
        ]);
    }

    public function update(Request $request, MedicalCertificate $medicalCertificate): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(MedicalCertificate::TYPES)),
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'issue_date' => 'required|date',
            'rest_from' => 'nullable|date',
            'rest_until' => 'nullable|date|after_or_equal:rest_from',
            'rest_days' => 'nullable|integer|min:0|max:365',
            'diagnosis' => 'nullable|string|max:500',
            'purpose' => 'nullable|string|max:255',
            'exam_data' => 'nullable|array',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,issued,cancelled',
        ]);
        $medicalCertificate->update($validated);
        return redirect()->route('medical-certificates.show', $medicalCertificate)->with('success', 'Surat diperbarui.');
    }

    public function destroy(MedicalCertificate $medicalCertificate): RedirectResponse
    {
        $medicalCertificate->delete();
        return redirect()->route('medical-certificates.index')->with('success', 'Surat dihapus.');
    }

    public function print(MedicalCertificate $medicalCertificate): View
    {
        $medicalCertificate->load(['patient', 'doctor']);
        return view('medical-certificates.print', ['certificate' => $medicalCertificate]);
    }

    private function generateNo(string $type): string
    {
        $prefix = match ($type) {
            'sick_leave' => 'SKS',
            'healthy' => 'SKD',
            'drug_free' => 'SBN',
            'pregnancy' => 'SKH',
            'not_pregnancy' => 'SKTH',
            'birth' => 'SKL',
            'death' => 'SKM',
            'visum' => 'VeR',
            'color_blind_free' => 'SBBW',
            'medical_check_up' => 'MCU',
            default => 'SKM',
        };
        $count = MedicalCertificate::whereDate('created_at', today())->count() + 1;
        return sprintf('%s/%s/%04d', $prefix, now()->format('Ymd'), $count);
    }
}
