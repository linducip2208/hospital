<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $patient = Auth::guard('patient')->user();

        $invoices = Payment::with('appointment')
            ->where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('portal.invoices.index', compact('invoices'));
    }

    public function show(Payment $invoice): View
    {
        abort_unless($invoice->patient_id === Auth::guard('patient')->id(), 403);
        $invoice->load('appointment', 'patient');

        return view('portal.invoices.show', compact('invoice'));
    }
}
