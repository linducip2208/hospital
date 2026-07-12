@extends('layouts.admin')
@section('title', 'Detail Surat')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">{{ $certificate->type_label }}</h1>
    <div>
        <a href="{{ route('medical-certificates.print', $certificate) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('medical-certificates.edit', $certificate) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('medical-certificates.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table">
            <tr><th style="width:200px">No. Surat</th><td><code>{{ $certificate->cert_no }}</code></td></tr>
            <tr><th>Jenis</th><td>{{ $certificate->type_label }}</td></tr>
            <tr><th>Pasien</th><td>{{ $certificate->patient->name ?? '-' }}</td></tr>
            <tr><th>Dokter</th><td>{{ $certificate->doctor->name ?? '-' }}</td></tr>
            <tr><th>Tgl Terbit</th><td>{{ $certificate->issue_date?->format('d F Y') }}</td></tr>
            <tr><th>Diagnosis</th><td>{{ $certificate->diagnosis ?? '-' }}</td></tr>
            <tr><th>Tujuan</th><td>{{ $certificate->purpose ?? '-' }}</td></tr>
            <tr><th>Istirahat</th><td>
                @if($certificate->rest_from)
                    {{ $certificate->rest_from->format('d M Y') }} s.d. {{ $certificate->rest_until?->format('d M Y') }}
                    ({{ $certificate->rest_days }} hari)
                @else - @endif
            </td></tr>
            <tr><th>Status</th><td><span class="badge bg-{{ $certificate->status === 'issued' ? 'success' : 'secondary' }}">{{ $certificate->status }}</span></td></tr>
            <tr><th>Catatan</th><td>{{ $certificate->notes ?? '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection
