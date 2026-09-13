<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use App\Services\BillingService;
use App\Services\DocumentNumberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('billing.view'), 403);
        $query = Payment::with(['patient:id,name', 'appointment:id,appointment_date']);

        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return response()->json(
            $query->latest()->paginate($request->get('per_page', 15))
        );
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('payments.receive'), 403);
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'bill_id' => 'nullable|exists:bills,id',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,debit,credit,qris,card,insurance,other',
            'status' => 'nullable|in:pending,completed,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        if (! empty($validated['bill_id'])) {
            $payment = app(BillingService::class)->receivePayment(Bill::findOrFail($validated['bill_id']), [
                'amount' => $validated['paid_amount'],
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json($payment->load(['patient:id,name', 'appointment:id,appointment_date']), 201);
        }

        $validated['discount'] ??= 0;
        $validated['tax'] ??= 0;
        $validated['amount'] = $validated['subtotal'] - $validated['discount'] + $validated['tax'];
        $validated['change_amount'] = max(0, $validated['paid_amount'] - $validated['amount']);
        // Legacy standalone payment payload remains supported for existing clients.
        $validated['invoice_number'] = app(DocumentNumberService::class)->next('legacy_invoice_api', 'INV', 5);

        $payment = Payment::create($validated);
        $payment->load(['patient:id,name', 'appointment:id,appointment_date']);

        return response()->json($payment, 201);
    }

    public function show(Payment $payment): JsonResponse
    {
        abort_unless(request()->user()?->hasPermission('billing.view'), 403);
        $payment->load(['patient', 'appointment']);

        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('billing.manage'), 403);
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'subtotal' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,debit,credit,qris,card,insurance,other',
            'status' => 'nullable|in:pending,completed,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['subtotal']) || array_key_exists('discount', $validated) || array_key_exists('tax', $validated) || isset($validated['paid_amount'])) {
            $subtotal = $validated['subtotal'] ?? $payment->subtotal;
            $discount = $validated['discount'] ?? $payment->discount;
            $tax = $validated['tax'] ?? $payment->tax;
            $paidAmount = $validated['paid_amount'] ?? $payment->paid_amount;

            $validated['amount'] = $subtotal - $discount + $tax;
            $validated['change_amount'] = max(0, $paidAmount - $validated['amount']);
        }

        $payment->update($validated);
        $payment->load(['patient:id,name', 'appointment:id,appointment_date']);

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        abort_unless(request()->user()?->hasPermission('billing.manage'), 403);
        $payment->delete();

        return response()->json(null, 204);
    }
}
