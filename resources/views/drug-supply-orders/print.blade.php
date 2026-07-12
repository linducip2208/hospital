@extends('layouts.print')
@section('title', $order->order_type_label.' '.$order->order_no)
@section('content')
@php
    $title = match($order->order_type) {
        'narcotic' => 'SURAT PESANAN NARKOTIKA',
        'psychotropic' => 'SURAT PESANAN PSIKOTROPIKA',
        'precursor' => 'SURAT PESANAN PREKURSOR FARMASI',
        default => 'SURAT PESANAN',
    };
@endphp
<div class="doc-title">{{ $title }}</div>
<div class="doc-no">No: {{ $order->order_no }}</div>

<p>Yang bertanda tangan di bawah ini:</p>
<table class="meta-table">
    <tr><td>Nama</td><td>: <strong>{{ $order->responsible_pharmacist }}</strong></td></tr>
    <tr><td>Jabatan</td><td>: Apoteker Penanggung Jawab</td></tr>
    <tr><td>No. SIPA</td><td>: {{ $order->pharmacist_sipa_no ?? '-' }}</td></tr>
</table>

<p>Mengajukan pesanan kepada:</p>
<table class="meta-table">
    <tr><td>Nama PBF</td><td>: <strong>{{ $order->supplier_name }}</strong></td></tr>
    <tr><td>Alamat</td><td>: {{ $order->supplier_address ?? '-' }}</td></tr>
    <tr><td>No. Izin PBF</td><td>: {{ $order->supplier_license_no ?? '-' }}</td></tr>
</table>

<p>Untuk pemesanan obat-obatan sebagai berikut:</p>
<table class="data-table">
    <thead><tr><th style="width:40px">No</th><th>Nama Obat</th><th>Bentuk</th><th>Kekuatan</th><th>Jumlah</th></tr></thead>
    <tbody>
    @foreach($order->items as $i => $it)
        <tr><td>{{ $i+1 }}</td><td>{{ $it->drug_name }}</td><td>{{ $it->dose_form }}</td><td>{{ $it->strength }}</td><td>{{ $it->quantity }} {{ $it->unit }}</td></tr>
    @endforeach
    </tbody>
</table>

@if(in_array($order->order_type, ['narcotic','psychotropic','precursor']))
<p class="small" style="margin-top:12px;">
    Obat-obatan tersebut akan digunakan untuk: keperluan pelayanan kesehatan di {{ \App\Models\Setting::where('key','branding_app_name')->value('value') ?? config('app.name') }} sesuai ketentuan peraturan perundang-undangan yang berlaku.
</p>
@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ $order->order_date?->translatedFormat('d F Y') }}</div>
        <div>Apoteker Penanggung Jawab,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $order->responsible_pharmacist }}</strong></div>
        <div class="small">SIPA: {{ $order->pharmacist_sipa_no ?? '-' }}</div>
    </div>
</div>
@endsection
