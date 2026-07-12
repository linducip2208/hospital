@extends('layouts.admin')
@section('title','Detail Vital ICU')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Vital ICU #{{ $monitoring->id }}</h1><div><a href="{{ route('icu-monitorings.edit', $monitoring) }}" class="btn btn-warning">Edit</a><a href="{{ route('icu-monitorings.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Pasien</th><td>{{ $monitoring->patient->name ?? '-' }}</td><th>Bed</th><td>{{ $monitoring->bed?->room?->name }} / {{ $monitoring->bed?->bed_code }}</td></tr>
<tr><th>Waktu</th><td colspan="3">{{ $monitoring->recorded_at?->format('d M Y H:i:s') }}</td></tr>
<tr><th>Suhu</th><td>{{ $monitoring->temperature }}°C</td><th>HR</th><td>{{ $monitoring->hr }}</td></tr>
<tr><th>RR</th><td>{{ $monitoring->rr }}</td><th>BP</th><td>{{ $monitoring->sbp }}/{{ $monitoring->dbp }} (MAP {{ $monitoring->map }})</td></tr>
<tr><th>SpO2</th><td>{{ $monitoring->spo2 }}%</td><th>GCS</th><td>{{ $monitoring->gcs }}</td></tr>
<tr><th>CVP</th><td colspan="3">{{ $monitoring->cvp }}</td></tr>
<tr><th>Catatan</th><td colspan="3">{!! nl2br(e($monitoring->notes)) !!}</td></tr>
</table></div></div>
@endsection
