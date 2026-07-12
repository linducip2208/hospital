<?php

namespace App\Http\Controllers;

use App\Models\DischargeSummary;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DischargeSummaryController extends Controller
{
    public function index(): View
    {
        $summaries = DischargeSummary::with(['patient', 'doctor'])->latest()->paginate(15);
        return view('discharge-summaries.index', compact('summaries'));
    }

    public function create(): View
    {
        return view('discharge-summaries.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'medicalRecords' => MedicalRecord::latest()->limit(200)->get(),
            'conditions' => DischargeSummary::CONDITIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['summary_no'] = $this->generateNo();
        $summary = DischargeSummary::create($validated);
        return redirect()->route('discharge-summaries.show', $summary)->with('success', 'Resume medis dibuat.');
    }

    public function show(DischargeSummary $dischargeSummary): View
    {
        $dischargeSummary->load(['patient', 'doctor', 'medicalRecord']);
        return view('discharge-summaries.show', ['summary' => $dischargeSummary]);
    }

    public function edit(DischargeSummary $dischargeSummary): View
    {
        return view('discharge-summaries.edit', [
            'summary' => $dischargeSummary,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'medicalRecords' => MedicalRecord::latest()->limit(200)->get(),
            'conditions' => DischargeSummary::CONDITIONS,
        ]);
    }

    public function update(Request $request, DischargeSummary $dischargeSummary): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $dischargeSummary->update($validated);
        return redirect()->route('discharge-summaries.show', $dischargeSummary)->with('success', 'Resume diperbarui.');
    }

    public function destroy(DischargeSummary $dischargeSummary): RedirectResponse
    {
        $dischargeSummary->delete();
        return redirect()->route('discharge-summaries.index')->with('success', 'Resume dihapus.');
    }

    public function print(DischargeSummary $dischargeSummary): View
    {
        $dischargeSummary->load(['patient', 'doctor', 'medicalRecord']);
        return view('discharge-summaries.print', ['summary' => $dischargeSummary]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'admission_date' => 'required|date',
            'discharge_date' => 'required|date|after_or_equal:admission_date',
            'admission_diagnosis' => 'required|string|max:500',
            'discharge_diagnosis' => 'required|string|max:500',
            'chief_complaint' => 'nullable|string',
            'history' => 'nullable|string',
            'physical_exam' => 'nullable|string',
            'investigations' => 'nullable|string',
            'treatment' => 'nullable|string',
            'progress' => 'nullable|string',
            'discharge_medication' => 'nullable|string',
            'follow_up' => 'nullable|string',
            'discharge_condition' => 'required|in:recovered,improved,unchanged,worsened,died',
        ]);
    }

    private function generateNo(): string
    {
        $count = DischargeSummary::whereDate('created_at', today())->count() + 1;
        return sprintf('RP/%s/%04d', now()->format('Ymd'), $count);
    }
}
