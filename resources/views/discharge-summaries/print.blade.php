@extends('layouts.print')
@section('title','Resume Medis '.$summary->summary_no)
@section('content')
<div class="doc-title">RESUME MEDIS PASIEN PULANG</div>
<div class="doc-no">No: {{ $summary->summary_no }}</div>

<table class="meta-table">
    <tr><td>Nama Pasien</td><td>: <strong>{{ $summary->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $summary->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $summary->patient?->birth_date?->format('d/m/Y') }}</td><td>L/P</td><td>: {{ $summary->patient?->gender }}</td></tr>
    <tr><td>MRS</td><td>: {{ $summary->admission_date?->format('d/m/Y H:i') }}</td><td>KRS</td><td>: {{ $summary->discharge_date?->format('d/m/Y H:i') }}</td></tr>
    <tr><td>DPJP</td><td colspan="3">: {{ $summary->doctor->name ?? '-' }} @if($summary->doctor?->str_number)(STR: {{ $summary->doctor->str_number }})@endif</td></tr>
</table>

<table class="data-table" style="margin-top:14px;">
    <tr><th style="width:200px">Diagnosis Masuk</th><td>{{ $summary->admission_diagnosis }}</td></tr>
    <tr><th>Diagnosis Pulang</th><td>{{ $summary->discharge_diagnosis }}</td></tr>
    <tr><th>Keluhan Utama</th><td>{!! nl2br(e($summary->chief_complaint)) !!}</td></tr>
    <tr><th>Riwayat Penyakit</th><td>{!! nl2br(e($summary->history)) !!}</td></tr>
    <tr><th>Pemeriksaan Fisik</th><td>{!! nl2br(e($summary->physical_exam)) !!}</td></tr>
    <tr><th>Pemeriksaan Penunjang</th><td>{!! nl2br(e($summary->investigations)) !!}</td></tr>
    <tr><th>Tindakan & Terapi</th><td>{!! nl2br(e($summary->treatment)) !!}</td></tr>
    <tr><th>Perjalanan Penyakit</th><td>{!! nl2br(e($summary->progress)) !!}</td></tr>
    <tr><th>Obat Pulang</th><td>{!! nl2br(e($summary->discharge_medication)) !!}</td></tr>
    <tr><th>Anjuran / Kontrol</th><td>{!! nl2br(e($summary->follow_up)) !!}</td></tr>
    <tr><th>Kondisi Saat Pulang</th><td><strong>{{ \App\Models\DischargeSummary::CONDITIONS[$summary->discharge_condition] ?? '-' }}</strong></td></tr>
</table>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ $summary->discharge_date?->translatedFormat('d F Y') }}</div>
        <div>DPJP,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $summary->doctor->name ?? '(....................)' }}</strong></div>
        @if($summary->doctor?->str_number)<div class="small">STR: {{ $summary->doctor->str_number }}</div>@endif
    </div>
</div>
@endsection
