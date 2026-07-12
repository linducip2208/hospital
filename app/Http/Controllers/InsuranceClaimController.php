<?php

namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InsuranceClaimController extends Controller
{
    public function index(Request $request): View
    {
        $query = InsuranceClaim::with(['patient', 'payment']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $claims = $query->latest()->paginate(15)->withQueryString();
        return view('insurance-claims.index', compact('claims'));
    }

    public function create(): View
    {
        return view('insurance-claims.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'payments' => Payment::latest()->limit(200)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['claim_no'] = $this->generateNo();
        $validated['status'] ??= 'draft';
        $claim = InsuranceClaim::create($validated);
        return redirect()->route('insurance-claims.show', $claim)->with('success', 'Klaim dibuat.');
    }

    public function show(InsuranceClaim $insuranceClaim): View
    {
        $insuranceClaim->load(['patient', 'payment']);
        return view('insurance-claims.show', ['claim' => $insuranceClaim]);
    }

    public function edit(InsuranceClaim $insuranceClaim): View
    {
        return view('insurance-claims.edit', [
            'claim' => $insuranceClaim,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'payments' => Payment::latest()->limit(200)->get(),
        ]);
    }

    public function update(Request $request, InsuranceClaim $insuranceClaim): RedirectResponse
    {
        $validated = $this->validateRequest($request, true);
        $insuranceClaim->update($validated);
        return redirect()->route('insurance-claims.show', $insuranceClaim)->with('success', 'Klaim diperbarui.');
    }

    public function destroy(InsuranceClaim $insuranceClaim): RedirectResponse
    {
        $insuranceClaim->delete();
        return redirect()->route('insurance-claims.index')->with('success', 'Klaim dihapus.');
    }

    public function print(InsuranceClaim $insuranceClaim): View
    {
        $insuranceClaim->load(['patient', 'payment']);
        return view('insurance-claims.print', ['claim' => $insuranceClaim]);
    }

    private function validateRequest(Request $request, bool $forUpdate = false): array
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'payment_id' => 'nullable|exists:payments,id',
            'insurance_provider' => 'required|string|max:255',
            'policy_number' => 'nullable|string|max:100',
            'claim_type' => 'required|in:outpatient,inpatient,emergency,maternity',
            'service_date' => 'required|date',
            'claim_date' => 'required|date',
            'diagnosis_code' => 'nullable|string|max:50',
            'diagnosis_text' => 'nullable|string|max:255',
            'claimed_amount' => 'required|numeric|min:0',
            'approved_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
        if ($forUpdate) {
            $rules['status'] = 'required|in:draft,submitted,approved,rejected,paid';
        }
        return $request->validate($rules);
    }

    private function generateNo(): string
    {
        $count = InsuranceClaim::whereDate('created_at', today())->count() + 1;
        return sprintf('CLM/%s/%04d', now()->format('Ymd'), $count);
    }
}
