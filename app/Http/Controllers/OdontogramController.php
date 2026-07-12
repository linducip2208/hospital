<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Odontogram;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OdontogramController extends Controller
{
    public function index(): View
    {
        $odontograms = Odontogram::with(['patient', 'doctor'])->latest()->paginate(15);
        return view('odontograms.index', compact('odontograms'));
    }

    public function create(): View
    {
        return view('odontograms.create', [
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Odontogram::create($this->validateRequest($request));
        return redirect()->route('odontograms.index')->with('success', 'Odontogram dibuat.');
    }

    public function show(Odontogram $odontogram): View
    {
        return view('odontograms.show', ['odontogram' => $odontogram->load(['patient', 'doctor'])]);
    }

    public function edit(Odontogram $odontogram): View
    {
        return view('odontograms.edit', [
            'odontogram' => $odontogram,
            'patients' => Patient::where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Odontogram $odontogram): RedirectResponse
    {
        $odontogram->update($this->validateRequest($request));
        return redirect()->route('odontograms.show', $odontogram)->with('success', 'Diperbarui.');
    }

    public function destroy(Odontogram $odontogram): RedirectResponse
    {
        $odontogram->delete();
        return redirect()->route('odontograms.index')->with('success', 'Dihapus.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'exam_date' => 'required|date',
            'teeth_state' => 'nullable|array',
            'general_findings' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    }
}
