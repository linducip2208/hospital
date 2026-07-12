@extends('layouts.print')
@section('title', 'Estimasi Biaya '.$estimate->estimate_no)
@section('content')
<div class="doc-title">ESTIMASI BIAYA TINDAKAN</div>
<div class="doc-no">No: {{ $estimate->estimate_no }} &middot; Tgl: {{ $estimate->estimate_date?->translatedFormat('d F Y') }}</div>

<table class="meta-table">
    <tr><td>Nama Pasien</td><td>: <strong>{{ $estimate->patient->name ?? '-' }}</strong></td></tr>
    <tr><td>NIK</td><td>: {{ $estimate->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Dokter</td><td>: {{ $estimate->doctor->name ?? '-' }}</td></tr>
    <tr><td>Tindakan</td><td>: <strong>{{ $estimate->procedure_name }}</strong></td></tr>
</table>

<table class="data-table" style="margin-top:10px;">
    <thead>
        <tr>
            <th style="width:40px">No</th>
            <th>Deskripsi</th>
            <th style="width:60px">Qty</th>
            <th style="width:60px">Sat.</th>
            <th style="width:120px" class="text-end">Harga Satuan</th>
            <th style="width:140px" class="text-end">Subtotal</th>
        </tr>
    </thead>
    <tbody>
    @foreach($estimate->items as $i => $it)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $it->description }}</td>
            <td>{{ $it->quantity }}</td>
            <td>{{ $it->unit }}</td>
            <td class="text-end">Rp {{ number_format((float) $it->unit_price,0,',','.') }}</td>
            <td class="text-end">Rp {{ number_format((float) $it->subtotal,0,',','.') }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="5" class="text-end"><strong>TOTAL ESTIMASI</strong></td>
        <td class="text-end"><strong>Rp {{ number_format((float) $estimate->total_amount, 0, ',', '.') }}</strong></td></tr>
    </tfoot>
</table>

<p class="small muted" style="margin-top:14px;">
    Estimasi ini bersifat perkiraan dan dapat berubah sesuai kondisi medis aktual pasien.
    Biaya final akan disesuaikan pada saat penagihan.
</p>

@if($estimate->notes)<p class="small">Catatan: {{ $estimate->notes }}</p>@endif

<table style="width:100%; margin-top:30px;">
    <tr>
        <td style="width:50%; text-align:center; vertical-align:top;">
            <div>Pasien / Keluarga</div>
            <div class="signature-space"></div>
            <div><strong>(......................)</strong></div>
        </td>
        <td style="width:50%; text-align:center; vertical-align:top;">
            <div>Petugas</div>
            <div class="signature-space"></div>
            <div><strong>(......................)</strong></div>
        </td>
    </tr>
</table>
@endsection
