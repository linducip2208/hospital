@extends('layouts.print')
@section('title','Laporan Operasi')
@section('content')
<div class="doc-title">LAPORAN OPERASI</div>
<div class="doc-no">No: OP/{{ str_pad((string) $surgery->id, 6, '0', STR_PAD_LEFT) }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $surgery->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $surgery->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Operasi</td><td>: {{ $surgery->scheduled_date?->format('d/m/Y H:i') }}</td><td>Status</td><td>: {{ $surgery->status }}</td></tr>
    <tr><td>Dokter Bedah</td><td colspan="3">: {{ $surgery->doctor->name ?? '-' }}</td></tr>
    <tr><td>Nama Operasi</td><td colspan="3">: <strong>{{ $surgery->name }}</strong></td></tr>
</table>

<table class="data-table" style="margin-top:10px;">
    <tr><th style="width:200px">Deskripsi Tindakan</th><td>{!! nl2br(e($surgery->description)) !!}</td></tr>
    <tr><th>Catatan</th><td>{!! nl2br(e($surgery->notes)) !!}</td></tr>
</table>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Dokter Operator,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $surgery->doctor->name ?? '(.....................)' }}</strong></div>
        @if($surgery->doctor?->str_number)<div class="small">STR: {{ $surgery->doctor->str_number }}</div>@endif
    </div>
</div>
@endsection
