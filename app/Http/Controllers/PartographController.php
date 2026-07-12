<?php

namespace App\Http\Controllers;

use App\Models\Maternity;
use App\Models\Partograph;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartographController extends Controller
{
    public function index(Request $request): View
    {
        $query = Partograph::with(['maternity.patient']);
        if ($maternityId = $request->get('maternity_id')) {
            $query->where('maternity_id', $maternityId);
        }
        $partographs = $query->latest('recorded_at')->paginate(15);
        return view('partographs.index', compact('partographs'));
    }

    public function create(): View
    {
        $maternities = Maternity::with('patient')->latest()->get();
        return view('partographs.create', compact('maternities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maternity_id' => 'required|exists:maternities,id',
            'recorded_at' => 'required|date',
            'cervical_dilation' => 'nullable|numeric|min:0|max:10',
            'fetal_heart_rate' => 'nullable|integer|min:0|max:200',
            'contractions_per_10min' => 'nullable|integer|min:0|max:20',
            'amniotic_fluid' => 'nullable|string|max:255',
            'moulding' => 'nullable|string|max:255',
            'oxytocin' => 'nullable|string|max:255',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'pulse' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'urine_output' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        Partograph::create($validated);
        return redirect()->route('partographs.index')->with('success', 'Data partograf berhasil dicatat.');
    }

    public function show(Partograph $partograph): View
    {
        $partograph->load(['maternity.patient']);
        return view('partographs.show', compact('partograph'));
    }

    public function edit(Partograph $partograph): View
    {
        $maternities = Maternity::with('patient')->latest()->get();
        return view('partographs.edit', compact('partograph', 'maternities'));
    }

    public function update(Request $request, Partograph $partograph): RedirectResponse
    {
        $validated = $request->validate([
            'maternity_id' => 'required|exists:maternities,id',
            'recorded_at' => 'required|date',
            'cervical_dilation' => 'nullable|numeric|min:0|max:10',
            'fetal_heart_rate' => 'nullable|integer|min:0|max:200',
            'contractions_per_10min' => 'nullable|integer|min:0|max:20',
            'amniotic_fluid' => 'nullable|string|max:255',
            'moulding' => 'nullable|string|max:255',
            'oxytocin' => 'nullable|string|max:255',
            'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
            'pulse' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'urine_output' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        $partograph->update($validated);
        return redirect()->route('partographs.index')->with('success', 'Data partograf berhasil diperbarui.');
    }

    public function destroy(Partograph $partograph): RedirectResponse
    {
        $partograph->delete();
        return redirect()->route('partographs.index')->with('success', 'Data partograf berhasil dihapus.');
    }
}
