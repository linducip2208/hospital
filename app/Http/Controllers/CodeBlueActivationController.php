<?php

namespace App\Http\Controllers;

use App\Models\CodeBlueActivation;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CodeBlueActivationController extends Controller
{
    public function index(): View
    {
        $codes = CodeBlueActivation::with('patient')->latest()->paginate(15);
        return view('code-blue-activations.index', compact('codes'));
    }

    public function create(): View
    {
        return view('code-blue-activations.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $validated['code_no'] = sprintf('CB/%s/%04d', now()->format('Ymd'), CodeBlueActivation::whereDate('created_at', today())->count() + 1);
        CodeBlueActivation::create($validated);
        return redirect()->route('code-blue-activations.index')->with('success', 'Aktivasi tercatat.');
    }

    public function show(CodeBlueActivation $codeBlueActivation): View
    {
        return view('code-blue-activations.show', ['code' => $codeBlueActivation->load('patient')]);
    }

    public function edit(CodeBlueActivation $codeBlueActivation): View
    {
        return view('code-blue-activations.edit', [
            'code' => $codeBlueActivation,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, CodeBlueActivation $codeBlueActivation): RedirectResponse
    {
        $codeBlueActivation->update($this->validateRequest($request));
        return redirect()->route('code-blue-activations.show', $codeBlueActivation)->with('success', 'Diperbarui.');
    }

    public function destroy(CodeBlueActivation $codeBlueActivation): RedirectResponse
    {
        $codeBlueActivation->delete();
        return redirect()->route('code-blue-activations.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'location' => 'required|string|max:255',
            'activation_time' => 'required|date',
            'team_arrival_time' => 'nullable|date',
            'return_circulation_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'outcome' => 'required|in:rosc,died,transferred,ongoing',
            'team_leader' => 'nullable|string|max:255',
            'initial_rhythm' => 'nullable|string',
            'interventions' => 'nullable|string',
            'medications_given' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    }
}
