<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\BillingService;
use App\Services\DocumentNumberService;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensurePermission('billing.view');
        $query = Payment::with(['appointment.patient']);
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('appointment.patient', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        $payments = $query->latest()->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create(): View
    {
        $this->ensurePermission('payments.receive');
        // Limit 500 terbaru untuk dropdown supaya page tidak hang
        $appointments = Appointment::with('patient')
            ->whereIn('status', ['completed', 'in_progress', 'confirmed'])
            ->orderBy('appointment_date', 'desc')
            ->limit(500)
            ->get();
        return view('payments.create', compact('appointments'));
    }

    public function store(Request $request, BillingService $billing): RedirectResponse
    {
        $this->ensurePermission('payments.receive');
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'bill_id' => 'nullable|exists:bills,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,debit,credit,qris,card,insurance,other',
            'status' => 'required|in:pending,completed,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        if (! empty($validated['bill_id'])) {
            $billing->receivePayment(\App\Models\Bill::findOrFail($validated['bill_id']), $validated);
            return redirect()->route('payments.index')->with('success', 'Pembayaran dialokasikan ke tagihan.');
        }

        $validated['patient_id'] = $appointment->patient_id;
        $validated['invoice_number'] = app(DocumentNumberService::class)->next('legacy_invoice', 'INV', 5);
        $validated['subtotal'] = $validated['amount'];
        $validated['discount'] = 0;
        $validated['tax'] = 0;
        $validated['paid_amount'] = $validated['status'] === 'completed' ? $validated['amount'] : 0;
        $validated['change_amount'] = 0;

        Payment::create($validated);
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Payment $payment): View
    {
        $this->ensurePermission('billing.view');
        $payment->load(['appointment.patient', 'appointment.doctor', 'appointment.treatment']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $appointments = Appointment::with('patient')->orderBy('appointment_date', 'desc')->limit(500)->get();
        return view('payments.edit', compact('payment', 'appointments'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,debit,credit,qris,card,insurance,other',
            'status' => 'required|in:pending,completed,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        $validated['patient_id'] = $appointment->patient_id;
        $validated['subtotal'] = $validated['amount'];
        $validated['discount'] = 0;
        $validated['tax'] = 0;
        $validated['paid_amount'] = $validated['status'] === 'completed' ? $validated['amount'] : 0;
        $validated['change_amount'] = 0;

        $payment->update($validated);
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function printReceipt(Payment $payment): View
    {
        $payment->load(['appointment.patient', 'appointment.doctor']);
        return view('payments.print-receipt', compact('payment'));
    }

    public function printBill(Payment $payment): View
    {
        $payment->load(['appointment.patient', 'appointment.doctor']);
        return view('payments.print-bill', compact('payment'));
    }

    public function refund(Request $request, Payment $payment, RefundService $service): RedirectResponse
    {
        $this->ensurePermission('payments.refund');
        $data = $request->validate(['amount' => 'required|numeric|min:0.01', 'reason' => 'required|string|max:1000']);
        $service->process($payment, (float) $data['amount'], $data['reason']);
        return back()->with('success', 'Refund diproses dan jurnal pembalik dibuat.');
    }

}
