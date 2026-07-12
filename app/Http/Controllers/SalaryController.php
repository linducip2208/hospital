<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Salary::with(['employee', 'user']);

        if ($month = $request->get('period_month')) {
            $query->where('period_month', $month);
        }
        if ($year = $request->get('period_year')) {
            $query->where('period_year', $year);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        $salaries = $query->latest()->paginate(15);
        $users = User::orderBy('name')->get();

        return view('salaries.index', compact('salaries', 'users'));
    }

    public function create(): View
    {
        $employees = Employee::with('user')
            ->where('employment_status', '!=', 'resigned')
            ->get();

        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'user_id' => 'required|exists:users,id',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|min:2000|max:2099',
            'base_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'deduction_note' => 'nullable|string',
            'status' => 'nullable|in:draft,approved,paid',
            'notes' => 'nullable|string',
        ]);

        $baseSalary = (float) $validated['base_salary'];
        $overtimePay = (float) ($validated['overtime_pay'] ?? 0);
        $bonus = (float) ($validated['bonus'] ?? 0);
        $deduction = (float) ($validated['deduction'] ?? 0);
        $validated['total_salary'] = $baseSalary + $overtimePay + $bonus - $deduction;
        $validated['status'] = $validated['status'] ?? 'draft';

        Salary::create($validated);

        return redirect()->route('salaries.index')->with('success', 'Penggajian berhasil ditambahkan.');
    }

    public function show(Salary $salary): View
    {
        $salary->load(['employee', 'user']);

        return view('salaries.show', compact('salary'));
    }

    public function edit(Salary $salary): View
    {
        $salary->load('employee');
        $employees = Employee::with('user')
            ->where(function ($q) use ($salary) {
                $q->where('employment_status', '!=', 'resigned')
                    ->orWhere('id', $salary->employee_id);
            })->get();

        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'user_id' => 'required|exists:users,id',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|min:2000|max:2099',
            'base_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'deduction_note' => 'nullable|string',
            'status' => 'nullable|in:draft,approved,paid',
            'notes' => 'nullable|string',
        ]);

        $baseSalary = (float) $validated['base_salary'];
        $overtimePay = (float) ($validated['overtime_pay'] ?? 0);
        $bonus = (float) ($validated['bonus'] ?? 0);
        $deduction = (float) ($validated['deduction'] ?? 0);
        $validated['total_salary'] = $baseSalary + $overtimePay + $bonus - $deduction;
        $validated['status'] = $validated['status'] ?? $salary->status;

        $salary->update($validated);

        return redirect()->route('salaries.index')->with('success', 'Penggajian berhasil diperbarui.');
    }

    public function destroy(Salary $salary): RedirectResponse
    {
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Penggajian berhasil dihapus.');
    }

    public function approve(Salary $salary): RedirectResponse
    {
        if ($salary->status !== 'draft') {
            return redirect()->route('salaries.index')->with('error', 'Hanya penggajian dengan status draft yang dapat disetujui.');
        }

        $salary->update(['status' => 'approved']);

        return redirect()->route('salaries.index')->with('success', 'Penggajian berhasil disetujui.');
    }

    public function pay(Salary $salary): RedirectResponse
    {
        if ($salary->status !== 'approved') {
            return redirect()->route('salaries.index')->with('error', 'Hanya penggajian yang sudah disetujui yang dapat dibayarkan.');
        }

        $salary->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('salaries.index')->with('success', 'Penggajian berhasil dibayarkan.');
    }
}
