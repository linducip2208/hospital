<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\InformedConsent;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformedConsentController extends Controller
{
    public function index(Request $request): View
    {
        $query = InformedConsent::with(['patient', 'doctor']);
        if ($kind = $request->get('kind')) {
            $query->where('kind', $kind);
        }
        $consents = $query->latest()->paginate(15)->withQueryString();
        return view('informed-consents.index', [
            'consents' => $consents,
            'kinds' => InformedConsent::KINDS,
        ]);
    }

    public function create(): View
    {
        return view('informed-consents.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'kinds' => InformedConsent::KINDS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['consent_no'] = $this->generateNo($validated['kind']);
        $consent = InformedConsent::create($validated);
        return redirect()->route('informed-consents.show', $consent)->with('success', 'Surat persetujuan dibuat.');
    }

    public function show(InformedConsent $informedConsent): View
    {
        $informedConsent->load(['patient', 'doctor']);
        return view('informed-consents.show', ['consent' => $informedConsent]);
    }

    public function edit(InformedConsent $informedConsent): View
    {
        return view('informed-consents.edit', [
            'consent' => $informedConsent,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
            'kinds' => InformedConsent::KINDS,
        ]);
    }

    public function update(Request $request, InformedConsent $informedConsent): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $informedConsent->update($validated);
        return redirect()->route('informed-consents.show', $informedConsent)->with('success', 'Surat diperbarui.');
    }

    public function destroy(InformedConsent $informedConsent): RedirectResponse
    {
        $informedConsent->delete();
        return redirect()->route('informed-consents.index')->with('success', 'Surat dihapus.');
    }

    public function print(InformedConsent $informedConsent): View
    {
        $informedConsent->load(['patient', 'doctor']);
        return view('informed-consents.print', ['consent' => $informedConsent]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'kind' => 'required|in:' . implode(',', array_keys(InformedConsent::KINDS)),
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'procedure_name' => 'required|string|max:255',
            'procedure_description' => 'nullable|string',
            'risks' => 'nullable|string',
            'alternatives' => 'nullable|string',
            'signed_by_name' => 'nullable|string|max:255',
            'signed_by_relation' => 'nullable|string|max:100',
            'witness_name' => 'nullable|string|max:255',
            'signed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
    }

    private function generateNo(string $kind): string
    {
        $prefix = match ($kind) {
            'consent' => 'IC',
            'refusal' => 'PT',
            'aps' => 'APS',
            default => 'IC',
        };
        $count = InformedConsent::whereDate('created_at', today())->count() + 1;
        return sprintf('%s/%s/%04d', $prefix, now()->format('Ymd'), $count);
    }
}
