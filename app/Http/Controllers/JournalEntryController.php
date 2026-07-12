<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    public function index(Request $request): View
    {
        $query = JournalEntry::with('postedBy');
        if ($search = $request->get('search')) {
            $query->whereAny(['journal_number', 'description', 'reference'], 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($date = $request->get('date')) {
            $query->whereDate('entry_date', $date);
        }
        $journalEntries = $query->latest()->paginate(15);
        return view('journal-entries.index', compact('journalEntries'));
    }

    public function create(): View
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
        return view('journal-entries.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ]);

        $totalDebit = collect($validated['lines'])->sum(fn($l) => (float) ($l['debit'] ?? 0));
        $totalCredit = collect($validated['lines'])->sum(fn($l) => (float) ($l['credit'] ?? 0));

        DB::transaction(function () use ($validated, $totalDebit, $totalCredit) {
            $lines = $validated['lines'];
            unset($validated['lines']);
            $validated['total_debit'] = $totalDebit;
            $validated['total_credit'] = $totalCredit;
            $validated['status'] = 'draft';
            $je = JournalEntry::create($validated);
            foreach ($lines as $line) {
                $line['journal_entry_id'] = $je->id;
                JournalEntryLine::create($line);
            }
        });

        return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function show(JournalEntry $journalEntry): View
    {
        $journalEntry->load(['lines.account', 'postedBy']);
        return view('journal-entries.show', compact('journalEntry'));
    }

    public function edit(JournalEntry $journalEntry): View
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
        $journalEntry->load('lines');
        return view('journal-entries.edit', compact('journalEntry', 'accounts'));
    }

    public function update(Request $request, JournalEntry $journalEntry): RedirectResponse
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'nullable|string',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ]);

        $totalDebit = collect($validated['lines'])->sum(fn($l) => (float) ($l['debit'] ?? 0));
        $totalCredit = collect($validated['lines'])->sum(fn($l) => (float) ($l['credit'] ?? 0));

        DB::transaction(function () use ($validated, $journalEntry, $totalDebit, $totalCredit) {
            $lines = $validated['lines'];
            unset($validated['lines']);
            $validated['total_debit'] = $totalDebit;
            $validated['total_credit'] = $totalCredit;
            $journalEntry->update($validated);
            $journalEntry->lines()->delete();
            foreach ($lines as $line) {
                $line['journal_entry_id'] = $journalEntry->id;
                JournalEntryLine::create($line);
            }
        });

        return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(JournalEntry $journalEntry): RedirectResponse
    {
        $journalEntry->lines()->delete();
        $journalEntry->delete();
        return redirect()->route('journal-entries.index')->with('success', 'Jurnal berhasil dihapus.');
    }

    public function post(JournalEntry $journalEntry): RedirectResponse
    {
        if ($journalEntry->status === 'posted') {
            return back()->with('error', 'Jurnal sudah diposting.');
        }
        $journalEntry->update([
            'status' => 'posted',
            'posted_by' => auth()->id(),
            'posted_at' => now(),
        ]);
        return back()->with('success', 'Jurnal berhasil diposting.');
    }
}
