@extends('portal.layout')

@section('title', 'Detail Tagihan')

@section('content')
<a href="{{ route('portal.invoices.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali</a>
<h1 class="h4 fw-bold my-3">Invoice {{ $invoice->invoice_number }}</h1>

<div class="card-soft p-4" style="max-width:640px">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Ditagihkan kepada</div>
            <div class="fw-bold">{{ $invoice->patient?->name }}</div>
        </div>
        <span class="badge bg-{{ $invoice->status === 'completed' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'secondary') }} fs-6">{{ strtoupper($invoice->status) }}</span>
    </div>

    <table class="table">
        <tr><td class="text-muted">Subtotal</td><td class="text-end">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td class="text-muted">Diskon</td><td class="text-end">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</td></tr>
        <tr><td class="text-muted">Pajak</td><td class="text-end">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td></tr>
        <tr class="fw-bold border-top"><td>Total</td><td class="text-end">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td></tr>
        <tr><td class="text-muted">Dibayar</td><td class="text-end">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td></tr>
    </table>

    <div class="text-muted small">Metode pembayaran: {{ ucfirst($invoice->payment_method) }} · {{ $invoice->created_at?->format('d F Y H:i') }}</div>
    @if($invoice->notes)<div class="alert alert-light mt-3 mb-0 small">{{ $invoice->notes }}</div>@endif
</div>
@endsection
