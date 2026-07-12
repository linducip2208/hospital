<?php

namespace App\Http\Controllers;

use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $query = StaffSchedule::with('user');
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($shiftDate = $request->get('shift_date')) {
            $query->whereDate('shift_date', $shiftDate);
        }
        if ($dept = $request->get('department')) {
            $query->where('department', $dept);
        }
        $schedules = $query->orderBy('shift_date')->orderBy('start_time')->paginate(31);
        $users = User::orderBy('name')->get();
        $departments = StaffSchedule::select('department')->distinct()->whereNotNull('department')->pluck('department');
        return view('staff-schedules.index', compact('schedules', 'users', 'departments'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('staff-schedules.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'department' => 'nullable|string|max:255',
            'shift_type' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        StaffSchedule::create($validated);
        return redirect()->route('staff-schedules.index')->with('success', 'Jadwal staff berhasil ditambahkan.');
    }

    public function show(StaffSchedule $staffSchedule): View
    {
        $staffSchedule->load('user');
        return view('staff-schedules.show', compact('staffSchedule'));
    }

    public function edit(StaffSchedule $staffSchedule): View
    {
        $users = User::orderBy('name')->get();
        return view('staff-schedules.edit', compact('staffSchedule', 'users'));
    }

    public function update(Request $request, StaffSchedule $staffSchedule): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'department' => 'nullable|string|max:255',
            'shift_type' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $staffSchedule->update($validated);
        return redirect()->route('staff-schedules.index')->with('success', 'Jadwal staff berhasil diperbarui.');
    }

    public function destroy(StaffSchedule $staffSchedule): RedirectResponse
    {
        $staffSchedule->delete();
        return redirect()->route('staff-schedules.index')->with('success', 'Jadwal staff berhasil dihapus.');
    }
}
