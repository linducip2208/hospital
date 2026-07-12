@extends('layouts.admin')
@section('title','Detail Code Blue')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Code Blue <code>{{ $code->code_no }}</code></h1><div><a href="{{ route('code-blue-activations.edit', $code) }}" class="btn btn-warning">Edit</a><a href="{{ route('code-blue-activations.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Pasien</th><td>{{ $code->patient->name ?? '-' }}</td><th>Lokasi</th><td>{{ $code->location }}</td></tr>
<tr><th>Aktivasi</th><td>{{ $code->activation_time?->format('d M Y H:i:s') }}</td><th>Tim Tiba</th><td>{{ $code->team_arrival_time?->format('d M Y H:i:s') }}</td></tr>
<tr><th>ROSC</th><td>{{ $code->return_circulation_time?->format('d M Y H:i:s') }}</td><th>Selesai</th><td>{{ $code->end_time?->format('d M Y H:i:s') }}</td></tr>
<tr><th>Response Time</th><td>{{ $code->response_time !== null ? round($code->response_time, 0).' detik' : '-' }}</td><th>Outcome</th><td>{{ $code->outcome }}</td></tr>
<tr><th>Team Leader</th><td>{{ $code->team_leader }}</td><th>Initial Rhythm</th><td>{{ $code->initial_rhythm }}</td></tr>
<tr><th>Intervensi</th><td colspan="3">{!! nl2br(e($code->interventions)) !!}</td></tr>
<tr><th>Obat</th><td colspan="3">{!! nl2br(e($code->medications_given)) !!}</td></tr>
<tr><th>Catatan</th><td colspan="3">{!! nl2br(e($code->notes)) !!}</td></tr>
</table></div></div>
@endsection
