<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Polyclinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PolyclinicController extends Controller
{
    public function index(Request $request): View
    {
        $query = Polyclinic::query();

        if ($search = $request->get('search')) {
            $query->whereAny(['name', 'code'], 'like', "%{$search}%");
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $polyclinics = $query->orderBy('name')->paginate(15);

        return view('polyclinics.index', compact('polyclinics'));
    }

    public function create(): View
    {
        $doctors = Doctor::where('status', 'active')->get();

        return view('polyclinics.create', compact('doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:polyclinics',
            'description' => 'nullable|string',
            'floor' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $polyclinic = Polyclinic::create($validated);

        if ($request->has('doctors')) {
            $polyclinic->doctors()->sync($request->doctors);
        }

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil ditambahkan.');
    }

    public function show(Polyclinic $polyclinic): View
    {
        $polyclinic->load('doctors', 'queues');

        return view('polyclinics.show', compact('polyclinic'));
    }

    public function edit(Polyclinic $polyclinic): View
    {
        $doctors = Doctor::where('status', 'active')->get();

        return view('polyclinics.edit', compact('polyclinic', 'doctors'));
    }

    public function update(Request $request, Polyclinic $polyclinic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:polyclinics,code,' . $polyclinic->id,
            'description' => 'nullable|string',
            'floor' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $polyclinic->update($validated);

        $polyclinic->doctors()->sync($request->input('doctors', []));

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil diperbarui.');
    }

    public function destroy(Polyclinic $polyclinic): RedirectResponse
    {
        $polyclinic->delete();

        return redirect()->route('polyclinics.index')->with('success', 'Poli berhasil dihapus.');
    }
}
