<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceCallController extends Controller
{
    public function index(Request $request): View
    {
        $query = AmbulanceCall::with('ambulance');
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $calls = $query->latest()->paginate(15);
        return view('ambulance-calls.index', compact('calls'));
    }

    public function create(): View
    {
        $ambulances = Ambulance::where('status', 'available')->orderBy('vehicle_number')->get();
        return view('ambulance-calls.create', compact('ambulances'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'patient_name' => 'required|string|max:255',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'call_date' => 'required|date',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        AmbulanceCall::create($validated);
        return redirect()->route('ambulance-calls.index')->with('success', 'Panggilan ambulans berhasil dicatat.');
    }

    public function show(AmbulanceCall $ambulanceCall): View
    {
        $ambulanceCall->load('ambulance');
        return view('ambulance-calls.show', compact('ambulanceCall'));
    }

    public function edit(AmbulanceCall $ambulanceCall): View
    {
        $ambulances = Ambulance::orderBy('vehicle_number')->get();
        return view('ambulance-calls.edit', compact('ambulanceCall', 'ambulances'));
    }

    public function update(Request $request, AmbulanceCall $ambulanceCall): RedirectResponse
    {
        $validated = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'patient_name' => 'required|string|max:255',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'call_date' => 'required|date',
            'status' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $ambulanceCall->update($validated);
        return redirect()->route('ambulance-calls.index')->with('success', 'Panggilan ambulans berhasil diperbarui.');
    }

    public function destroy(AmbulanceCall $ambulanceCall): RedirectResponse
    {
        $ambulanceCall->delete();
        return redirect()->route('ambulance-calls.index')->with('success', 'Panggilan ambulans berhasil dihapus.');
    }

    public function print(AmbulanceCall $ambulanceCall): View
    {
        $ambulanceCall->load('ambulance');
        return view('ambulance-calls.print', compact('ambulanceCall'));
    }
}
