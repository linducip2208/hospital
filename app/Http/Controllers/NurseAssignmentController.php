<?php

namespace App\Http\Controllers;

use App\Models\NurseAssignment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NurseAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = NurseAssignment::with(['user', 'patient']);
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($patientId = $request->get('patient_id')) {
            $query->where('patient_id', $patientId);
        }
        if ($assignmentType = $request->get('assignment_type')) {
            $query->where('assignment_type', $assignmentType);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        $assignments = $query->latest()->paginate(15);
        $users = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        return view('nurse-assignments.index', compact('assignments', 'users'));
    }

    public function create(): View
    {
        $users = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        return view('nurse-assignments.create', compact('users', 'patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'assignment_type' => 'required|in:nurse,midwife',
            'task_description' => 'required|string',
            'shift' => 'nullable|in:pagi,siang,malam',
            'notes' => 'nullable|string',
        ]);
        NurseAssignment::create($validated);
        return redirect()->route('nurse-assignments.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(NurseAssignment $nurseAssignment): View
    {
        $nurseAssignment->load(['user', 'patient']);
        return view('nurse-assignments.show', compact('nurseAssignment'));
    }

    public function edit(NurseAssignment $nurseAssignment): View
    {
        $users = User::whereIn('role', ['nurse', 'midwife'])->orderBy('name')->get();
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        return view('nurse-assignments.edit', compact('nurseAssignment', 'users', 'patients'));
    }

    public function update(Request $request, NurseAssignment $nurseAssignment): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'assignment_type' => 'required|in:nurse,midwife',
            'task_description' => 'required|string',
            'shift' => 'nullable|in:pagi,siang,malam',
            'status' => 'nullable|in:pending,in_progress,completed',
            'notes' => 'nullable|string',
        ]);
        $nurseAssignment->update($validated);
        return redirect()->route('nurse-assignments.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(NurseAssignment $nurseAssignment): RedirectResponse
    {
        $nurseAssignment->delete();
        return redirect()->route('nurse-assignments.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
