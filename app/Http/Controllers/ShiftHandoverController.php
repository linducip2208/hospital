<?php

namespace App\Http\Controllers;

use App\Models\ShiftHandover;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftHandoverController extends Controller
{
    public function index(Request $request): View
    {
        $query = ShiftHandover::with(['fromNurse', 'toNurse']);
        if ($date = $request->get('date')) {
            $query->whereDate('shift_date', $date);
        }
        $shiftHandovers = $query->latest('shift_date')->paginate(15);
        return view('shift-handovers.index', compact('shiftHandovers'));
    }

    public function create(): View
    {
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('shift-handovers.create', compact('nurses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_nurse_id' => 'required|exists:users,id',
            'to_nurse_id' => 'required|exists:users,id|different:from_nurse_id',
            'shift_date' => 'required|date',
            'shift_type' => 'nullable|string|max:255',
            'patient_summary' => 'nullable|string',
            'tasks_pending' => 'nullable|string',
            'incidents' => 'nullable|string',
            'equipment_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        ShiftHandover::create($validated);
        return redirect()->route('shift-handovers.index')->with('success', 'Laporan serah terima shift berhasil disimpan.');
    }

    public function show(ShiftHandover $shiftHandover): View
    {
        $shiftHandover->load(['fromNurse', 'toNurse']);
        return view('shift-handovers.show', compact('shiftHandover'));
    }

    public function edit(ShiftHandover $shiftHandover): View
    {
        $nurses = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('shift-handovers.edit', compact('shiftHandover', 'nurses'));
    }

    public function update(Request $request, ShiftHandover $shiftHandover): RedirectResponse
    {
        $validated = $request->validate([
            'from_nurse_id' => 'required|exists:users,id',
            'to_nurse_id' => 'required|exists:users,id|different:from_nurse_id',
            'shift_date' => 'required|date',
            'shift_type' => 'nullable|string|max:255',
            'patient_summary' => 'nullable|string',
            'tasks_pending' => 'nullable|string',
            'incidents' => 'nullable|string',
            'equipment_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $shiftHandover->update($validated);
        return redirect()->route('shift-handovers.index')->with('success', 'Laporan serah terima shift berhasil diperbarui.');
    }

    public function destroy(ShiftHandover $shiftHandover): RedirectResponse
    {
        $shiftHandover->delete();
        return redirect()->route('shift-handovers.index')->with('success', 'Laporan serah terima shift berhasil dihapus.');
    }
}
