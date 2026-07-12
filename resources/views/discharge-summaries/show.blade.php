@extends('layouts.admin')
@section('title','Detail Resume')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Resume <code>{{ $summary->summary_no }}</code></h1>
    <div>
        <a href="{{ route('discharge-summaries.print', $summary) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('discharge-summaries.edit', $summary) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('discharge-summaries.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Pasien</th><td>{{ $summary->patient->name ?? '-' }}</td><th>DPJP</th><td>{{ $summary->doctor->name ?? '-' }}</td></tr>
<tr><th>MRS</th><td>{{ $summary->admission_date?->format('d M Y H:i') }}</td><th>KRS</th><td>{{ $summary->discharge_date?->format('d M Y H:i') }}</td></tr>
<tr><th>Diagnosis Masuk</th><td colspan="3">{{ $summary->admission_diagnosis }}</td></tr>
<tr><th>Diagnosis Pulang</th><td colspan="3">{{ $summary->discharge_diagnosis }}</td></tr>
<tr><th>Kondisi Pulang</th><td colspan="3">{{ \App\Models\DischargeSummary::CONDITIONS[$summary->discharge_condition] ?? $summary->discharge_condition }}</td></tr>
</table>
</div></div>
@endsection
