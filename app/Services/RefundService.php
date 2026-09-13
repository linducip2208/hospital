<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function __construct(private JournalService $journal) {}

    public function process(Payment $payment, float $amount, string $reason): Refund
    {
        return DB::transaction(function () use ($payment, $amount, $reason) {
            $payment = Payment::with('allocations')->lockForUpdate()->findOrFail($payment->id);
            $refunded = (float) $payment->refunds()->whereIn('status', ['approved', 'processed'])->sum('amount');
            $max = (float) $payment->paid_amount - $refunded;
            if ($amount <= 0 || $amount > $max) throw ValidationException::withMessages(['amount' => 'Nominal refund melebihi pembayaran yang masih dapat dikembalikan.']);
            $refund = Refund::create(['payment_id' => $payment->id, 'bill_id' => $payment->allocations()->first()?->bill_id, 'amount' => $amount, 'reason' => $reason, 'status' => 'processed', 'processed_by' => auth()->id(), 'processed_at' => now()]);
            foreach ($payment->allocations as $allocation) {
                $bill = $allocation->bill()->lockForUpdate()->first();
                if (! $bill) continue;
                $portion = min((float) $allocation->amount, $amount);
                $bill->update(['paid_amount' => max(0, (float) $bill->paid_amount - $portion), 'balance' => (float) $bill->balance + $portion, 'status' => 'partial']);
                $amount -= $portion;
                if ($amount <= 0) break;
            }
            $remaining = (float) $payment->paid_amount - $refunded - (float) $refund->amount;
            $payment->update(['status' => $remaining <= 0 ? 'refunded' : 'completed']);
            $this->journal->post('refund', $refund->id, 'Pembalikan pembayaran '.$payment->invoice_number, [['account_code' => ['1200', '1-002'], 'debit' => $refund->amount], ['account_code' => ['1110', '1-001'], 'credit' => $refund->amount]], $payment->invoice_number);
            ActivityLogger::log('refund', $refund, 'Refund pembayaran diproses.');
            return $refund;
        });
    }
}
