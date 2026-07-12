<?php

namespace App\Http\Controllers;

use App\Models\InfectionSurveillance;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InfectionSurveillanceController extends Controller
{
    public function index(): View
    {
        $cases = InfectionSurveillance::with('patient')->latest()->paginate(15);
        return view('infection-surveillances.index', [
            'cases' => $cases,
            'types' => InfectionSurveillance::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('infection-surveillances.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => InfectionSurveillance::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['case_no'] = sprintf('HAI/%s/%04d', now()->format('Ymd'), InfectionSurveillance::whereDate('created_at', today())->count() + 1);
        InfectionSurveillance::create($validated);
        return redirect()->route('infection-surveillances.index')->with('success', 'Kasus dicatat.');
    }

    public function show(InfectionSurveillance $infectionSurveillance): View
    {
        return view('infection-surveillances.show', ['case' => $infectionSurveillance->load('patient')]);
    }

    public function edit(InfectionSurveillance $infectionSurveillance): View
    {
        return view('infection-surveillances.edit', [
            'case' => $infectionSurveillance,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'types' => InfectionSurveillance::TYPES,
        ]);
    }

    public function update(Request $request, InfectionSurveillance $infectionSurveillance): RedirectResponse
    {
        $infectionSurveillance->update($this->validateRequest($request));
        return redirect()->route('infection-surveillances.show', $infectionSurveillance)->with('success', 'Diperbarui.');
    }

    public function destroy(InfectionSurveillance $infectionSurveillance): RedirectResponse
    {
        $infectionSurveillance->delete();
        return redirect()->route('infection-surveillances.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'infection_type' => 'required|in:vap,clabsi,cauti,ssi,phlebitis,decubitus,other',
            'detection_date' => 'required|date',
            'onset_date' => 'nullable|date',
            'site' => 'required|string|max:255',
            'organism' => 'nullable|string|max:255',
            'symptoms' => 'nullable|string',
            'antibiotic_therapy' => 'nullable|string',
            'intervention' => 'nullable|string',
            'outcome' => 'required|in:resolved,ongoing,died',
        ]);
    }
}
