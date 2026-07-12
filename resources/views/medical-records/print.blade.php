@extends('layouts.print')
@section('title','Rekam Medis')
@section('content')
<div class="doc-title">REKAM MEDIS PASIEN</div>
<div class="doc-no">No. RM: {{ str_pad((string) ($medicalRecord->patient_id ?? 0), 8, '0', STR_PAD_LEFT) }} &middot; Catatan #{{ $medicalRecord->id }}</div>

<table class="meta-table">
    <tr><td>Pasien</td><td>: <strong>{{ $medicalRecord->patient->name ?? '-' }}</strong></td><td>NIK</td><td>: {{ $medicalRecord->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $medicalRecord->patient?->birth_date?->format('d/m/Y') }}</td><td>L/P</td><td>: {{ $medicalRecord->patient?->gender }}</td></tr>
    <tr><td>Dokter</td><td colspan="3">: {{ $medicalRecord->doctor->name ?? '-' }}</td></tr>
    <tr><td>Tgl Pencatatan</td><td colspan="3">: {{ $medicalRecord->created_at?->format('d/m/Y H:i') }}</td></tr>
</table>

<table class="data-table" style="margin-top:10px;">
    <tr><th style="width:200px">Diagnosis</th><td>{{ $medicalRecord->diagnosis }}</td></tr>
    <tr><th>Tindakan</th><td>{!! nl2br(e($medicalRecord->action)) !!}</td></tr>
    <tr><th>Pengobatan</th><td>{!! nl2br(e($medicalRecord->medicine)) !!}</td></tr>
    @if($medicalRecord->vital_signs)
    <tr><th>Tanda Vital</th><td>
        @foreach((array) $medicalRecord->vital_signs as $k => $v)
            <strong>{{ ucfirst(str_replace('_',' ', (string) $k)) }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}<br>
        @endforeach
    </td></tr>
    @endif
    <tr><th>Hasil Lab</th><td>{!! nl2br(e($medicalRecord->lab_results)) !!}</td></tr>
    <tr><th>Catatan</th><td>{!! nl2br(e($medicalRecord->notes)) !!}</td></tr>
</table>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ ($medicalRecord->created_at ?? now())->translatedFormat('d F Y') }}</div>
        <div>Dokter Pemeriksa,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $medicalRecord->doctor->name ?? '(.....................)' }}</strong></div>
    </div>
</div>
@endsection
