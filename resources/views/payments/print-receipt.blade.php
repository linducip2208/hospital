@extends('layouts.print')
@section('page_size','A5')
@section('title','Kwitansi '.$payment->invoice_number)
@section('content')
<div class="doc-title">KWITANSI PEMBAYARAN</div>
<div class="doc-no">No: {{ $payment->invoice_number ?? '-' }}</div>

<table class="meta-table">
    <tr><td>Telah diterima dari</td><td>: <strong>{{ $payment->appointment?->patient?->name ?? '-' }}</strong></td></tr>
    <tr><td>Banyaknya uang</td><td>: <strong>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</strong></td></tr>
    <tr><td>Untuk pembayaran</td><td>: {{ $payment->notes ?? 'Pelayanan medis' }}</td></tr>
    <tr><td>Metode</td><td>: {{ $payment->payment_method }}</td></tr>
    <tr><td>Status</td><td>: {{ ucfirst($payment->status) }}</td></tr>
</table>

@if($payment->subtotal)
<table class="data-table" style="margin-top:10px;">
    <tr><th>Subtotal</th><td class="text-end">Rp {{ number_format((float) $payment->subtotal, 0, ',', '.') }}</td></tr>
    @if($payment->discount > 0)<tr><th>Diskon</th><td class="text-end">-Rp {{ number_format((float) $payment->discount, 0, ',', '.') }}</td></tr>@endif
    @if($payment->tax > 0)<tr><th>Pajak</th><td class="text-end">Rp {{ number_format((float) $payment->tax, 0, ',', '.') }}</td></tr>@endif
    <tr><th>Total</th><td class="text-end"><strong>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</strong></td></tr>
    @if($payment->paid_amount)<tr><th>Dibayar</th><td class="text-end">Rp {{ number_format((float) $payment->paid_amount, 0, ',', '.') }}</td></tr>@endif
    @if($payment->change_amount)<tr><th>Kembali</th><td class="text-end">Rp {{ number_format((float) $payment->change_amount, 0, ',', '.') }}</td></tr>@endif
</table>
@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ $payment->created_at?->translatedFormat('d F Y') }}</div>
        <div>Kasir,</div>
        <div class="signature-space"></div>
        <div><strong>(.....................)</strong></div>
    </div>
</div>
@endsection
