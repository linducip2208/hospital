@extends('layouts.print')
@section('title','Hasil Lab')
@section('content')
<div class="doc-title">HASIL PEMERIKSAAN LABORATORIUM</div>
<div class="doc-no">No: LAB/{{ str_pad((string) $labTest->id, 6, '0', STR_PAD_LEFT) }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $labTest->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $labTest->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $labTest->patient?->birth_date?->format('d/m/Y') }}</td><td>L/P</td><td>: {{ $labTest->patient?->gender }}</td></tr>
    <tr><td>Dokter Pengirim</td><td>: {{ $labTest->doctor->name ?? '-' }}</td><td>Tgl Hasil</td><td>: {{ $labTest->result_date?->format('d/m/Y H:i') }}</td></tr>
    <tr><td>Pemeriksaan</td><td colspan="3">: <strong>{{ $labTest->test_name }}</strong> ({{ $labTest->test_type }})</td></tr>
    <tr><td>Sampel</td><td colspan="3">: {{ $labTest->sample_type }}</td></tr>
</table>

<table class="data-table" style="margin-top:14px;">
    <thead><tr><th>Hasil Pemeriksaan</th></tr></thead>
    <tbody><tr><td><div style="white-space:pre-wrap;">{{ $labTest->results }}</div></td></tr></tbody>
</table>

@if($labTest->notes)<p class="small">Catatan: {{ $labTest->notes }}</p>@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ ($labTest->result_date ?? now())->format('d F Y') }}</div>
        <div>Dokter Patologi Klinik / Analis</div>
        <div class="signature-space"></div>
        <div><strong>(.....................)</strong></div>
    </div>
</div>
@endsection
