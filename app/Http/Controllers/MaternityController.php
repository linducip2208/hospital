<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Maternity;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaternityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Maternity::with(['patient', 'doctor']);
        if ($search = $request->get('search')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $maternities = $query->latest()->paginate(15);
        return view('maternities.index', compact('maternities'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->whereIn('specialization', ['Obgyn', 'Umum'])->orderBy('name')->get();
        return view('maternities.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'admission_date' => 'required|date',
            'delivery_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Maternity::create($validated);
        return redirect()->route('maternities.index')->with('success', 'Data persalinan berhasil dicatat.');
    }

    public function show(Maternity $maternity): View
    {
        $maternity->load(['patient', 'doctor']);
        return view('maternities.show', compact('maternity'));
    }

    public function edit(Maternity $maternity): View
    {
        $patients = Patient::where('is_active', true)->where('gender', 'female')->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->whereIn('specialization', ['Obgyn', 'Umum'])->orderBy('name')->get();
        return view('maternities.edit', compact('maternity', 'patients', 'doctors'));
    }

    public function update(Request $request, Maternity $maternity): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'admission_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'delivery_type' => 'nullable|string|max:255',
            'baby_gender' => 'nullable|string|max:255',
            'baby_weight' => 'nullable|numeric|min:0',
            'baby_length' => 'nullable|numeric|min:0',
            'baby_name' => 'nullable|string|max:255',
            'complications' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $maternity->update($validated);
        return redirect()->route('maternities.index')->with('success', 'Data persalinan berhasil diperbarui.');
    }

    public function destroy(Maternity $maternity): RedirectResponse
    {
        $maternity->delete();
        return redirect()->route('maternities.index')->with('success', 'Data persalinan berhasil dihapus.');
    }
}
