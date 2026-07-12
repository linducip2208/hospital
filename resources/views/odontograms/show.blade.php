@extends('layouts.admin')
@section('title','Detail Odontogram')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Odontogram #{{ $odontogram->id }}</h1><div><a href="{{ route('odontograms.edit', $odontogram) }}" class="btn btn-warning">Edit</a><a href="{{ route('odontograms.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Pasien</th><td>{{ $odontogram->patient->name ?? '-' }}</td><th>Dokter</th><td>{{ $odontogram->doctor->name ?? '-' }}</td></tr>
<tr><th>Tgl Periksa</th><td colspan="3">{{ $odontogram->exam_date?->format('d M Y') }}</td></tr>
<tr><th>Temuan</th><td colspan="3">{!! nl2br(e($odontogram->general_findings)) !!}</td></tr>
<tr><th>Rencana</th><td colspan="3">{!! nl2br(e($odontogram->treatment_plan)) !!}</td></tr>
</table>
@if($odontogram->teeth_state)
<h6>State Gigi</h6>
<div class="row">
@foreach($odontogram->teeth_state as $tooth => $state)
    @if($state)<div class="col-md-2"><strong>{{ $tooth }}</strong>: {{ $state }}</div>@endif
@endforeach
</div>
@endif
</div></div>
@endsection
