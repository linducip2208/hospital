@extends('layouts.admin')
@section('title','Detail HAI')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">HAI <code>{{ $case->case_no }}</code></h1><div><a href="{{ route('infection-surveillances.edit', $case) }}" class="btn btn-warning">Edit</a><a href="{{ route('infection-surveillances.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Pasien</th><td>{{ $case->patient->name ?? '-' }}</td><th>Tipe</th><td>{{ \App\Models\InfectionSurveillance::TYPES[$case->infection_type] ?? $case->infection_type }}</td></tr>
<tr><th>Site</th><td>{{ $case->site }}</td><th>Organisme</th><td>{{ $case->organism }}</td></tr>
<tr><th>Tgl Deteksi</th><td>{{ $case->detection_date?->format('d M Y') }}</td><th>Onset</th><td>{{ $case->onset_date?->format('d M Y') }}</td></tr>
<tr><th>Outcome</th><td colspan="3">{{ $case->outcome }}</td></tr>
<tr><th>Gejala</th><td colspan="3">{!! nl2br(e($case->symptoms)) !!}</td></tr>
<tr><th>Antibiotik</th><td colspan="3">{!! nl2br(e($case->antibiotic_therapy)) !!}</td></tr>
<tr><th>Intervensi</th><td colspan="3">{!! nl2br(e($case->intervention)) !!}</td></tr>
</table></div></div>
@endsection
