@extends('layouts.print')
@section('title','Rincian Tagihan '.$payment->invoice_number)
@section('content')
<div class="doc-title">RINCIAN TAGIHAN</div>
<div class="doc-no">No: {{ $payment->invoice_number ?? '-' }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $payment->appointment?->patient?->name ?? '-' }}</strong></td></tr>
    <tr><td>Dokter</td><td>: {{ $payment->appointment?->doctor?->name ?? '-' }}</td></tr>
    <tr><td>Tgl Layanan</td><td>: {{ $payment->appointment?->date?->format('d/m/Y') ?? $payment->created_at?->format('d/m/Y') }}</td></tr>
</table>

<table class="data-table" style="margin-top:14px;">
    <thead><tr><th>Komponen</th><th class="text-end">Jumlah</th></tr></thead>
    <tbody>
        @if($payment->subtotal)<tr><td>Subtotal Layanan</td><td class="text-end">Rp {{ number_format((float) $payment->subtotal, 0, ',', '.') }}</td></tr>@endif
        @if($payment->discount > 0)<tr><td>Diskon</td><td class="text-end">-Rp {{ number_format((float) $payment->discount, 0, ',', '.') }}</td></tr>@endif
        @if($payment->tax > 0)<tr><td>Pajak / Admin</td><td class="text-end">Rp {{ number_format((float) $payment->tax, 0, ',', '.') }}</td></tr>@endif
    </tbody>
    <tfoot>
        <tr><td><strong>TOTAL</strong></td><td class="text-end"><strong>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</strong></td></tr>
        @if($payment->paid_amount)<tr><td>Dibayar</td><td class="text-end">Rp {{ number_format((float) $payment->paid_amount, 0, ',', '.') }}</td></tr>@endif
        @if($payment->change_amount > 0)<tr><td>Kembalian</td><td class="text-end">Rp {{ number_format((float) $payment->change_amount, 0, ',', '.') }}</td></tr>@endif
        @php $sisa = (float) $payment->amount - (float) ($payment->paid_amount ?? 0); @endphp
        @if($sisa > 0)<tr><td><strong>SISA</strong></td><td class="text-end"><strong>Rp {{ number_format($sisa, 0, ',', '.') }}</strong></td></tr>@endif
    </tfoot>
</table>

@if($payment->notes)<p class="small" style="margin-top:10px;">Catatan: {{ $payment->notes }}</p>@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Kasir,</div>
        <div class="signature-space"></div>
        <div><strong>(....................)</strong></div>
    </div>
</div>
@endsection
