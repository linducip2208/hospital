@extends('layouts.print')
@section('title','Hasil Radiologi')
@section('content')
<div class="doc-title">HASIL PEMERIKSAAN RADIOLOGI</div>
<div class="doc-no">No: RAD/{{ str_pad((string) $radiology->id, 6, '0', STR_PAD_LEFT) }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $radiology->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $radiology->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $radiology->patient?->birth_date?->format('d/m/Y') }}</td><td>L/P</td><td>: {{ $radiology->patient?->gender }}</td></tr>
    <tr><td>Dokter Pengirim</td><td>: {{ $radiology->doctor->name ?? '-' }}</td><td>Status</td><td>: {{ $radiology->status }}</td></tr>
    <tr><td>Pemeriksaan</td><td colspan="3">: <strong>{{ $radiology->examination_name }}</strong></td></tr>
    <tr><td>Lokasi</td><td colspan="3">: {{ $radiology->body_part }}</td></tr>
</table>

<table class="data-table" style="margin-top:14px;">
    <thead><tr><th>Hasil Bacaan</th></tr></thead>
    <tbody><tr><td><div style="white-space:pre-wrap;">{{ $radiology->findings }}</div></td></tr></tbody>
</table>

@if($radiology->notes)<p class="small">Catatan: {{ $radiology->notes }}</p>@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Dokter Spesialis Radiologi</div>
        <div class="signature-space"></div>
        <div><strong>{{ $radiology->doctor->name ?? '(.....................)' }}</strong></div>
        @if($radiology->doctor?->str_number)<div class="small">STR: {{ $radiology->doctor->str_number }}</div>@endif
    </div>
</div>
@endsection
