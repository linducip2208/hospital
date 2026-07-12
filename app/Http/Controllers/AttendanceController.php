<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attendance::with('user');

        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('date', '<=', $dateTo);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(31);
        $users = User::orderBy('name')->get();

        return view('attendances.index', compact('attendances', 'users'));
    }

    public function create(): View
    {
        $users = User::whereNotIn('role', ['patient'])->orderBy('name')->get();

        return view('attendances.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|unique:attendances,date,NULL,id,user_id,' . $request->user_id,
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'nullable|in:present,late,absent,sick,leave,half_day',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['status']) && !empty($validated['check_in'])) {
            $checkInTime = date('H:i', strtotime($validated['check_in']));
            if ($checkInTime < '08:00') {
                $validated['status'] = 'present';
            } elseif ($checkInTime >= '08:00' && $checkInTime <= '08:30') {
                $validated['status'] = 'late';
            } else {
                $validated['status'] = 'absent';
            }
        }

        Attendance::create($validated);

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load('user');

        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance): View
    {
        $users = User::whereNotIn('role', ['patient'])->orderBy('name')->get();

        return view('attendances.edit', compact('attendance', 'users'));
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|unique:attendances,date,' . $attendance->id . ',id,user_id,' . $request->user_id,
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'nullable|in:present,late,absent,sick,leave,half_day',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
