<?php

namespace App\Http\Controllers;

use App\Models\ClinicalPathway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalPathwayController extends Controller
{
    public function index(): View
    {
        $pathways = ClinicalPathway::latest()->paginate(15);
        return view('clinical-pathways.index', compact('pathways'));
    }

    public function create(): View
    {
        return view('clinical-pathways.create');
    }

    public function store(Request $request): RedirectResponse
    {
        ClinicalPathway::create($this->validateRequest($request));
        return redirect()->route('clinical-pathways.index')->with('success', 'Pathway dibuat.');
    }

    public function show(ClinicalPathway $clinicalPathway): View
    {
        return view('clinical-pathways.show', ['pathway' => $clinicalPathway]);
    }

    public function edit(ClinicalPathway $clinicalPathway): View
    {
        return view('clinical-pathways.edit', ['pathway' => $clinicalPathway]);
    }

    public function update(Request $request, ClinicalPathway $clinicalPathway): RedirectResponse
    {
        $clinicalPathway->update($this->validateRequest($request));
        return redirect()->route('clinical-pathways.show', $clinicalPathway)->with('success', 'Diperbarui.');
    }

    public function destroy(ClinicalPathway $clinicalPathway): RedirectResponse
    {
        $clinicalPathway->delete();
        return redirect()->route('clinical-pathways.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'diagnosis_code' => 'nullable|string|max:50',
            'diagnosis' => 'nullable|string|max:255',
            'expected_los_days' => 'nullable|integer|min:1|max:365',
            'phases' => 'nullable|array',
            'inclusion_criteria' => 'nullable|string',
            'exclusion_criteria' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
