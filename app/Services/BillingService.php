<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Charge;
use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BillingService
{
    public function __construct(private JournalService $journal)
    {
    }

    public function addCharge(array $attributes): Charge
    {
        return Charge::firstOrCreate(
            ['source_type' => $attributes['source_type'], 'source_id' => $attributes['source_id'] ?? null],
            $attributes + ['status' => 'pending', 'created_by' => auth()->id()]
        );
    }

    public function generateBill(Encounter $encounter): Bill
    {
        return DB::transaction(function () use ($encounter) {
            $encounter = Encounter::with('patient')->lockForUpdate()->findOrFail($encounter->id);
            $bill = Bill::where('encounter_id', $encounter->id)
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->lockForUpdate()->first();
            if ($bill) {
                return $bill->load('items', 'invoices');
            }

            $charges = Charge::where('encounter_id', $encounter->id)
                ->where('status', 'pending')->lockForUpdate()->get();
            $subtotal = (float) $charges->sum('amount');
            $coverage = in_array($encounter->payer_type, ['bpjs', 'insurance', 'corporate'], true) ? 0.0 : 0.0;
            $responsibility = max(0, $subtotal - $coverage);
            $bill = Bill::create([
                'bill_no' => app(DocumentNumberService::class)->next('bill', 'BILL', 4),
                'patient_id' => $encounter->patient_id,
                'encounter_id' => $encounter->id,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => 0,
                'insurance_coverage' => $coverage,
                'patient_responsibility' => $responsibility,
                'deposit' => 0,
                'paid_amount' => 0,
                'balance' => $responsibility,
                'status' => 'issued',
                'issued_at' => now(),
            ]);
            foreach ($charges as $charge) {
                BillItem::create([
                    'bill_id' => $bill->id,
                    'charge_id' => $charge->id,
                    'description' => $charge->description,
                    'quantity' => $charge->quantity,
                    'unit_price' => $charge->unit_price,
                    'amount' => $charge->amount,
                ]);
                $charge->update(['status' => 'billed']);
            }
            Invoice::create([
                'bill_id' => $bill->id,
                'invoice_number' => app(DocumentNumberService::class)->next('invoice', 'INV', 5),
                'status' => 'issued',
                'issued_at' => now(),
            ]);
            ActivityLogger::log('created', $bill, 'Tagihan diterbitkan dari charge layanan encounter.');
            $this->postRevenue($bill, $encounter);

            return $bill->load('items', 'invoices');
        });
    }

    public function receivePayment(Bill $bill, array $attributes): Payment
    {
        return DB::transaction(function () use ($bill, $attributes) {
            $bill = Bill::lockForUpdate()->findOrFail($bill->id);
            $amount = round((float) $attributes['amount'], 2);
            $balance = max(0, (float) $bill->patient_responsibility - (float) $bill->paid_amount);
            if ($amount <= 0 || $amount > $balance) {
                throw ValidationException::withMessages(['amount' => 'Nominal pembayaran melebihi sisa tagihan atau tidak valid.']);
            }

            $payment = Payment::create([
                'patient_id' => $bill->patient_id,
                'appointment_id' => $bill->encounter?->appointment_id,
                'medical_record_id' => null,
                'encounter_id' => $bill->encounter_id,
                'invoice_number' => app(DocumentNumberService::class)->next('payment', 'PAY', 5),
                'subtotal' => $amount,
                'discount' => 0,
                'tax' => 0,
                'amount' => $amount,
                'paid_amount' => $amount,
                'change_amount' => 0,
                'payment_method' => $attributes['payment_method'] ?? 'cash',
                'status' => 'completed',
                'notes' => $attributes['notes'] ?? null,
            ]);
            $payment->allocations()->create(['bill_id' => $bill->id, 'amount' => $amount]);
            $newPaid = (float) $bill->paid_amount + $amount;
            $newBalance = max(0, (float) $bill->patient_responsibility - $newPaid);
            $bill->update([
                'paid_amount' => $newPaid,
                'balance' => $newBalance,
                'status' => $newBalance <= 0 ? 'paid' : 'partial',
                'paid_at' => $newBalance <= 0 ? now() : null,
            ]);
            $this->journal->post('payment', $payment->id, 'Penerimaan pembayaran '.$payment->invoice_number, [
                ['account_code' => $this->cashAccount($payment->payment_method), 'debit' => $amount],
                ['account_code' => ['1200', '1-002'], 'credit' => $amount],
            ], $payment->invoice_number);
            ActivityLogger::log('payment', $payment, 'Pembayaran dialokasikan ke tagihan '.$bill->bill_no);

            return $payment->load('allocations');
        });
    }

    private function postRevenue(Bill $bill, Encounter $encounter): void
    {
        $account = match ($encounter->encounter_type) {
            'inpatient' => ['4400', '4-002'],
            default => ['4100', '4-001'],
        };
        $total = (float) $bill->subtotal;
        if ($total > 0) {
            $this->journal->post('bill', $bill->id, 'Pengakuan pendapatan '.$bill->bill_no, [
                ['account_code' => ['1200', '1-002'], 'debit' => $total],
                ['account_code' => $account, 'credit' => $total],
            ], $bill->bill_no);
        }
    }

    private function cashAccount(string $method): array
    {
        return match ($method) {
            'transfer', 'debit', 'credit', 'qris' => ['1120', '1-001'],
            default => ['1110', '1-001'],
        };
    }
}
