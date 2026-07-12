<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChartOfAccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = ChartOfAccount::with('parent');
        if ($search = $request->get('search')) {
            $query->whereAny(['account_code', 'account_name', 'description'], 'like', "%{$search}%");
        }
        if ($accountType = $request->get('account_type')) {
            $query->where('account_type', $accountType);
        }
        $accounts = $query->orderBy('account_code')->paginate(15);
        return view('chart-of-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        $parents = ChartOfAccount::where('is_active', true)->whereNull('parent_id')->orderBy('account_code')->get();
        return view('chart-of-accounts.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:50|unique:chart_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        ChartOfAccount::create($validated);
        return redirect()->route('chart-of-accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show(ChartOfAccount $chartOfAccount): View
    {
        $chartOfAccount->load(['parent', 'children']);
        return view('chart-of-accounts.show', compact('chartOfAccount'));
    }

    public function edit(ChartOfAccount $chartOfAccount): View
    {
        $parents = ChartOfAccount::where('is_active', true)
            ->whereNull('parent_id')
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('account_code')->get();
        return view('chart-of-accounts.edit', compact('chartOfAccount', 'parents'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:50|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $chartOfAccount->update($validated);
        return redirect()->route('chart-of-accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $chartOfAccount->delete();
        return redirect()->route('chart-of-accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
