@extends('layouts.print')
@section('title','Lembar Triase IGD')
@section('content')
@php
    $color = match(strtolower((string) $emergency->triage)) {
        'red' => '#dc2626', 'merah' => '#dc2626',
        'yellow' => '#eab308', 'kuning' => '#eab308',
        'green' => '#16a34a', 'hijau' => '#16a34a',
        'black' => '#1f2937', 'hitam' => '#1f2937',
        default => '#6b7280',
    };
@endphp
<div class="doc-title">LEMBAR TRIASE IGD</div>
<div class="doc-no">No: IGD/{{ str_pad((string) $emergency->id, 6, '0', STR_PAD_LEFT) }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $emergency->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $emergency->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $emergency->patient?->birth_date?->format('d/m/Y') }}</td><td>L/P</td><td>: {{ $emergency->patient?->gender }}</td></tr>
    <tr><td>Cara Datang</td><td>: {{ $emergency->arrival_mode }}</td><td>Dokter</td><td>: {{ $emergency->doctor->name ?? '-' }}</td></tr>
</table>

<div style="margin:14px 0; padding:10px; border:3px solid {{ $color }}; border-radius:6px;">
    <div style="font-size:11pt;">KATEGORI TRIASE</div>
    <div style="font-size:22pt; font-weight:bold; color:{{ $color }};">{{ strtoupper($emergency->triage ?? '-') }}</div>
</div>

<table class="data-table">
    <tr><th style="width:200px">Keluhan Utama</th><td>{!! nl2br(e($emergency->complaint)) !!}</td></tr>
    <tr><th>Diagnosis</th><td>{!! nl2br(e($emergency->diagnosis)) !!}</td></tr>
    <tr><th>Tindakan</th><td>{!! nl2br(e($emergency->action_taken)) !!}</td></tr>
    <tr><th>Status</th><td>{{ $emergency->status }}</td></tr>
    <tr><th>Catatan</th><td>{!! nl2br(e($emergency->notes)) !!}</td></tr>
</table>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ now()->translatedFormat('d F Y') }}</div>
        <div>Petugas Triase,</div>
        <div class="signature-space"></div>
        <div><strong>(.....................)</strong></div>
    </div>
</div>
@endsection
