<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Doctor::query();
        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'email', 'phone', 'specialization'], 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $doctors = $query->latest()->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        return view('doctors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,on_leave',
            'notes' => 'nullable|string',
        ]);
        Doctor::create($validated);
        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function show(Doctor $doctor): View
    {
        $doctor->load(['appointments.patient', 'medicalRecords']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor): View
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,on_leave',
            'notes' => 'nullable|string',
        ]);
        $doctor->update($validated);
        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}