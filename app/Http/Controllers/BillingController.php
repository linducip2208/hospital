<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Encounter;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function show(Bill $bill): View
    {
        $bill->load(['patient', 'encounter', 'items', 'invoices', 'paymentAllocations.payment']);

        return view('billing.show', compact('bill'));
    }

    public function generate(Encounter $encounter, BillingService $billing): RedirectResponse
    {
        $bill = $billing->generateBill($encounter);

        return redirect()->route('billing.show', $bill)->with('success', 'Tagihan diterbitkan dari charge layanan encounter.');
    }

    public function pay(Request $request, Bill $bill, BillingService $billing): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,transfer,debit,credit,qris,card,insurance,bpjs,corporate,other',
            'notes' => 'nullable|string|max:1000',
        ]);
        $billing->receivePayment($bill, $validated);

        return redirect()->route('billing.show', $bill)->with('success', 'Pembayaran berhasil dialokasikan dan dijurnal.');
    }
}
