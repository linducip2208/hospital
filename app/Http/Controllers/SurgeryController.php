<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Surgery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurgeryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Surgery::with(['patient', 'doctor']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($scheduled = $request->get('scheduled_date')) {
            $query->whereDate('scheduled_date', $scheduled);
        }
        $surgeries = $query->latest()->paginate(15);
        return view('surgeries.index', compact('surgeries'));
    }

    public function create(): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('surgeries.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Surgery::create($validated);
        return redirect()->route('surgeries.index')->with('success', 'Operasi berhasil dicatat.');
    }

    public function show(Surgery $surgery): View
    {
        $surgery->load(['patient', 'doctor']);
        return view('surgeries.show', compact('surgery'));
    }

    public function edit(Surgery $surgery): View
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::where('status', 'active')->orderBy('name')->get();
        return view('surgeries.edit', compact('surgery', 'patients', 'doctors'));
    }

    public function update(Request $request, Surgery $surgery): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $surgery->update($validated);
        return redirect()->route('surgeries.index')->with('success', 'Operasi berhasil diperbarui.');
    }

    public function destroy(Surgery $surgery): RedirectResponse
    {
        $surgery->delete();
        return redirect()->route('surgeries.index')->with('success', 'Operasi berhasil dihapus.');
    }

    public function print(Surgery $surgery): View
    {
        $surgery->load(['patient', 'doctor']);
        return view('surgeries.print', compact('surgery'));
    }
}
