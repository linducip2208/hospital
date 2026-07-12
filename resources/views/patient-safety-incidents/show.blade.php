@extends('layouts.admin')
@section('title','Detail IKP')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">IKP <code>{{ $incident->incident_no }}</code></h1>
    <div>
        <a href="{{ route('patient-safety-incidents.edit', $incident) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('patient-safety-incidents.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Jenis</th><td>{{ strtoupper($incident->incident_type) }}</td><th>Severity</th><td>{{ $incident->severity }}</td></tr>
<tr><th>Pasien</th><td>{{ $incident->patient->name ?? '-' }}</td><th>Pelapor</th><td>{{ $incident->reporter->name ?? '-' }}</td></tr>
<tr><th>Lokasi</th><td>{{ $incident->location }}</td><th>Tgl Kejadian</th><td>{{ $incident->occurred_at?->format('d M Y H:i') }}</td></tr>
<tr><th>Status</th><td colspan="3">{{ $incident->status }}</td></tr>
<tr><th>Deskripsi</th><td colspan="3">{!! nl2br(e($incident->description)) !!}</td></tr>
<tr><th>Tindakan Segera</th><td colspan="3">{!! nl2br(e($incident->immediate_action)) !!}</td></tr>
<tr><th>Akar Masalah</th><td colspan="3">{!! nl2br(e($incident->root_cause)) !!}</td></tr>
<tr><th>Tindakan Korektif</th><td colspan="3">{!! nl2br(e($incident->corrective_action)) !!}</td></tr>
</table>
</div></div>
@endsection
