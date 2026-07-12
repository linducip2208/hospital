@extends('layouts.print')
@section('title','Skrining '.$screening->screening_no)
@section('content')
<div class="doc-title">FORM SKRINING — {{ strtoupper($screening->type_label) }}</div>
<div class="doc-no">No: {{ $screening->screening_no }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $screening->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $screening->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tanggal</td><td>: {{ $screening->screened_at?->format('d/m/Y H:i') }}</td><td>Petugas</td><td>: {{ $screening->user->name ?? '-' }}</td></tr>
</table>

@if($screening->answers)
<table class="data-table" style="margin-top:10px;">
    <thead><tr><th>Parameter</th><th>Jawaban / Skor</th></tr></thead>
    <tbody>
    @foreach((array) $screening->answers as $k => $v)
        <tr><td>{{ ucfirst(str_replace('_',' ', (string) $k)) }}</td><td>{{ is_array($v) ? json_encode($v) : $v }}</td></tr>
    @endforeach
    </tbody>
</table>
@endif

<table class="data-table" style="margin-top:10px;">
    <tr><th style="width:200px">Total Skor</th><td><strong>{{ $screening->score }}</strong></td></tr>
    <tr><th>Tingkat Risiko</th><td><strong>{{ ['low'=>'RENDAH','moderate'=>'SEDANG','high'=>'TINGGI'][$screening->risk_level] ?? $screening->risk_level }}</strong></td></tr>
    <tr><th>Intervensi</th><td>{!! nl2br(e($screening->intervention)) !!}</td></tr>
</table>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ ($screening->screened_at ?? now())->translatedFormat('d F Y') }}</div>
        <div>Petugas Skrining,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $screening->user->name ?? '(....................)' }}</strong></div>
    </div>
</div>
@endsection
