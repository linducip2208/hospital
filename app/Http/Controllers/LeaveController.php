<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(Request $request): View
    {
        $query = Leave::with(['user', 'approver']);
        if ($search = $request->get('search')) {
            $query->whereAny(['leave_type', 'reason', 'notes'], 'like', "%{$search}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($leaveType = $request->get('leave_type')) {
            $query->where('leave_type', $leaveType);
        }
        $leaves = $query->latest()->paginate(15);
        $users = User::orderBy('name')->get();
        return view('leaves.index', compact('leaves', 'users'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('leaves.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:tahunan,sakit,melahirkan,alasan_penting,cuti_besar',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'nullable|integer|min:1',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $validated['status'] = 'pending';
        Leave::create($validated);
        return redirect()->route('leaves.index')->with('success', 'Cuti berhasil ditambahkan.');
    }

    public function show(Leave $leave): View
    {
        $leave->load(['user', 'approver']);
        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave): View
    {
        $users = User::orderBy('name')->get();
        return view('leaves.edit', compact('leave', 'users'));
    }

    public function update(Request $request, Leave $leave): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:tahunan,sakit,melahirkan,alasan_penting,cuti_besar',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'nullable|integer|min:1',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);
        $leave->update($validated);
        return redirect()->route('leaves.index')->with('success', 'Cuti berhasil diperbarui.');
    }

    public function destroy(Leave $leave): RedirectResponse
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Cuti berhasil dihapus.');
    }

    public function approve(Leave $leave): RedirectResponse
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cuti sudah diproses sebelumnya.');
        }
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    public function reject(Leave $leave): RedirectResponse
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cuti sudah diproses sebelumnya.');
        }
        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Cuti berhasil ditolak.');
    }
}
