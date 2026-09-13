<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class JournalService
{
    /**
     * Post one balanced journal exactly once for an operational source.
     * Account codes are seeded/configured data, never provider credentials.
     */
    public function post(string $sourceType, int $sourceId, string $description, array $lines, ?string $reference = null): JournalEntry
    {
        return DB::transaction(function () use ($sourceType, $sourceId, $description, $lines, $reference) {
            $existing = JournalEntry::where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->first();
            if ($existing) {
                return $existing->load('lines');
            }

            $normalised = [];
            $debit = 0.0;
            $credit = 0.0;
            foreach ($lines as $line) {
                $account = $this->account($line['account_code']);
                $lineDebit = round((float) ($line['debit'] ?? 0), 2);
                $lineCredit = round((float) ($line['credit'] ?? 0), 2);
                if ($lineDebit < 0 || $lineCredit < 0 || ($lineDebit > 0 && $lineCredit > 0)) {
                    throw new RuntimeException('Baris jurnal harus debit atau kredit dan tidak boleh negatif.');
                }
                $debit += $lineDebit;
                $credit += $lineCredit;
                $normalised[] = [
                    'account_id' => $account->id,
                    'description' => $line['description'] ?? $description,
                    'debit' => $lineDebit,
                    'credit' => $lineCredit,
                ];
            }

            if ($debit <= 0 || abs($debit - $credit) > 0.01) {
                throw new RuntimeException('Jurnal tidak seimbang.');
            }

            $journal = JournalEntry::create([
                'journal_number' => app(DocumentNumberService::class)->next('journal', 'JRN', 4),
                'entry_date' => today(),
                'description' => $description,
                'reference' => $reference,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'total_debit' => $debit,
                'total_credit' => $credit,
                'status' => 'posted',
                'posted_by' => auth()->id(),
                'posted_at' => now(),
            ]);
            $journal->lines()->createMany($normalised);

            return $journal->load('lines');
        });
    }

    public function account(array|string $codes): ChartOfAccount
    {
        foreach ((array) $codes as $code) {
            $account = ChartOfAccount::where('account_code', $code)->where('is_active', true)->first();
            if ($account) {
                return $account;
            }
        }

        throw new RuntimeException('Akun COA belum tersedia: '.implode(', ', (array) $codes));
    }
}
