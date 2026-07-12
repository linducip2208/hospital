@extends('layouts.admin')
@section('title', 'Detail Persetujuan')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">{{ $consent->kind_label }}</h1>
    <div>
        <a href="{{ route('informed-consents.print', $consent) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('informed-consents.edit', $consent) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('informed-consents.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
    <tr><th style="width:200px">No.</th><td><code>{{ $consent->consent_no }}</code></td></tr>
    <tr><th>Jenis</th><td>{{ $consent->kind_label }}</td></tr>
    <tr><th>Pasien</th><td>{{ $consent->patient->name ?? '-' }}</td></tr>
    <tr><th>Dokter / DPJP</th><td>{{ $consent->doctor->name ?? '-' }}</td></tr>
    <tr><th>Tindakan</th><td>{{ $consent->procedure_name }}</td></tr>
    <tr><th>Penjelasan</th><td>{!! nl2br(e($consent->procedure_description)) !!}</td></tr>
    <tr><th>Risiko</th><td>{!! nl2br(e($consent->risks)) !!}</td></tr>
    <tr><th>Alternatif</th><td>{!! nl2br(e($consent->alternatives)) !!}</td></tr>
    <tr><th>Penandatangan</th><td>{{ $consent->signed_by_name }} ({{ $consent->signed_by_relation }})</td></tr>
    <tr><th>Saksi</th><td>{{ $consent->witness_name ?? '-' }}</td></tr>
    <tr><th>Ditandatangani</th><td>{{ $consent->signed_at?->format('d M Y H:i') }}</td></tr>
</table>
</div></div>
@endsection
