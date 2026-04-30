<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
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
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,card,insurance,other',
            'status' => 'nullable|in:pending,paid,partial,refunded,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['discount'] ??= 0;
        $validated['tax'] ??= 0;
        $validated['total'] = $validated['subtotal'] - $validated['discount'] + $validated['tax'];
        $validated['change_amount'] = max(0, $validated['paid_amount'] - $validated['total']);
        $validated['invoice_number'] = $this->generateInvoiceNumber();

        $payment = Payment::create($validated);
        $payment->load(['patient:id,name', 'appointment:id,appointment_date']);

        return response()->json($payment, 201);
    }

    public function show(Payment $payment): JsonResponse
    {
        $payment->load(['patient', 'appointment']);
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'subtotal' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,card,insurance,other',
            'status' => 'nullable|in:pending,paid,partial,refunded,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['subtotal']) || array_key_exists('discount', $validated) || array_key_exists('tax', $validated) || isset($validated['paid_amount'])) {
            $subtotal = $validated['subtotal'] ?? $payment->subtotal;
            $discount = $validated['discount'] ?? $payment->discount;
            $tax = $validated['tax'] ?? $payment->tax;
            $paidAmount = $validated['paid_amount'] ?? $payment->paid_amount;

            $validated['total'] = $subtotal - $discount + $tax;
            $validated['change_amount'] = max(0, $paidAmount - $validated['total']);
        }

        $payment->update($validated);
        $payment->load(['patient:id,name', 'appointment:id,appointment_date']);

        return response()->json($payment);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();
        return response()->json(null, 204);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV/' . now()->format('Y/m');
        $last = Payment::where('invoice_number', 'like', $prefix . '/%')
            ->orderBy('id', 'desc')
            ->first();

        $next = $last ? (int) Str::afterLast($last->invoice_number, '/') + 1 : 1;

        return $prefix . '/' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
