@extends('layouts.admin')
@section('title','Detail Pathway')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Pathway: {{ $pathway->code }} — {{ $pathway->name }}</h1><div><a href="{{ route('clinical-pathways.edit', $pathway) }}" class="btn btn-warning">Edit</a><a href="{{ route('clinical-pathways.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Diagnosis</th><td>{{ $pathway->diagnosis_code }} - {{ $pathway->diagnosis }}</td><th>LOS</th><td>{{ $pathway->expected_los_days }} hari</td></tr>
<tr><th>Inklusi</th><td colspan="3">{!! nl2br(e($pathway->inclusion_criteria)) !!}</td></tr>
<tr><th>Eksklusi</th><td colspan="3">{!! nl2br(e($pathway->exclusion_criteria)) !!}</td></tr>
</table>
@if($pathway->phases)<h6>Fase Perawatan</h6><pre class="bg-light p-3 small">{{ json_encode($pathway->phases, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>@endif
</div></div>
@endsection
