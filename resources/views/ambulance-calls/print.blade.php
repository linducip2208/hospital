@extends('layouts.print')
@section('title','Surat Tugas Ambulans')
@section('content')
<div class="doc-title">SURAT TUGAS AMBULANS</div>
<div class="doc-no">No: AMB/{{ str_pad((string) $ambulanceCall->id, 6, '0', STR_PAD_LEFT) }}</div>

<p>Berdasarkan permintaan, dengan ini ditugaskan armada ambulans untuk menjemput/mengantar:</p>

<table class="meta-table">
    <tr><td>Pasien / Yang Diangkut</td><td>: <strong>{{ $ambulanceCall->patient_name }}</strong></td></tr>
    <tr><td>Tgl & Jam Panggilan</td><td>: {{ $ambulanceCall->call_date instanceof \Carbon\Carbon ? $ambulanceCall->call_date->format('d/m/Y H:i') : $ambulanceCall->call_date }}</td></tr>
    <tr><td>Lokasi Penjemputan</td><td>: {{ $ambulanceCall->pickup_location }}</td></tr>
    <tr><td>Tujuan</td><td>: {{ $ambulanceCall->destination }}</td></tr>
    <tr><td>Armada</td><td>: {{ $ambulanceCall->ambulance->plate_number ?? '-' }} ({{ $ambulanceCall->ambulance->name ?? '-' }})</td></tr>
    <tr><td>Status</td><td>: {{ $ambulanceCall->status }}</td></tr>
</table>

@if($ambulanceCall->notes)<p class="small" style="margin-top:10px;">Catatan: {{ $ambulanceCall->notes }}</p>@endif

<table style="width:100%; margin-top:30px;">
    <tr>
        <td style="width:50%; text-align:center; vertical-align:top;">
            <div>Pengemudi/Petugas</div>
            <div class="signature-space"></div>
            <div><strong>(.....................)</strong></div>
        </td>
        <td style="width:50%; text-align:center; vertical-align:top;">
            <div>Petugas Penanggung Jawab</div>
            <div class="signature-space"></div>
            <div><strong>(.....................)</strong></div>
        </td>
    </tr>
</table>
@endsection
