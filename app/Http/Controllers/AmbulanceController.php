<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ambulance::query();
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $ambulances = $query->latest()->paginate(15);
        return view('ambulances.index', compact('ambulances'));
    }

    public function create(): View
    {
        return view('ambulances.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Ambulance::create($validated);
        return redirect()->route('ambulances.index')->with('success', 'Ambulans berhasil ditambahkan.');
    }

    public function show(Ambulance $ambulance): View
    {
        return view('ambulances.show', compact('ambulance'));
    }

    public function edit(Ambulance $ambulance): View
    {
        return view('ambulances.edit', compact('ambulance'));
    }

    public function update(Request $request, Ambulance $ambulance): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $ambulance->update($validated);
        return redirect()->route('ambulances.index')->with('success', 'Ambulans berhasil diperbarui.');
    }

    public function destroy(Ambulance $ambulance): RedirectResponse
    {
        $ambulance->delete();
        return redirect()->route('ambulances.index')->with('success', 'Ambulans berhasil dihapus.');
    }
}
