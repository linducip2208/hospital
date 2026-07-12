<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with('user');

        if ($department = $request->get('department')) {
            $query->where('department', $department);
        }
        if ($status = $request->get('employment_status')) {
            $query->where('employment_status', $status);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('employee_code', 'like', "%{$search}%")
              ->orWhere('position', 'like', "%{$search}%");
        }

        $employees = $query->latest()->paginate(15);
        $departments = Employee::select('department')->distinct()->whereNotNull('department')->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('employee')
            ->whereNotIn('role', ['admin', 'patient'])
            ->orderBy('name')->get();

        return view('employees.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'employee_code' => 'nullable|string|max:20|unique:employees,employee_code',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'bpjs_tk' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:30',
            'employment_status' => 'nullable|in:permanent,contract,probation,intern,resigned',
            'education_level' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['user', 'salaries' => function ($q) {
            $q->latest()->limit(12);
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $users = User::where(function ($q) use ($employee) {
            $q->whereDoesntHave('employee')
                ->orWhere('id', $employee->user_id);
        })->whereNotIn('role', ['admin', 'patient'])
          ->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'users'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id,' . $employee->id,
            'employee_code' => 'nullable|string|max:20|unique:employees,employee_code,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'bpjs_tk' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:30',
            'employment_status' => 'nullable|in:permanent,contract,probation,intern,resigned',
            'education_level' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
