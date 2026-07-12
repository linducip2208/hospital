@extends('layouts.admin')
@section('title','Detail Telemedicine')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Sesi <code>{{ $session->session_no }}</code></h1><div>@if($session->meeting_url)<a href="{{ $session->meeting_url }}" target="_blank" class="btn btn-success"><i class="bi bi-camera-video"></i> Mulai</a>@endif <a href="{{ route('telemedicine-sessions.edit', $session) }}" class="btn btn-warning">Edit</a> <a href="{{ route('telemedicine-sessions.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Pasien</th><td>{{ $session->patient->name ?? '-' }}</td><th>Dokter</th><td>{{ $session->doctor->name ?? '-' }}</td></tr>
<tr><th>Jadwal</th><td>{{ $session->scheduled_at?->format('d M Y H:i') }}</td><th>Status</th><td>{{ $session->status }}</td></tr>
<tr><th>Mulai</th><td>{{ $session->started_at?->format('d M Y H:i') }}</td><th>Selesai</th><td>{{ $session->ended_at?->format('d M Y H:i') }}</td></tr>
<tr><th>Platform</th><td>{{ $session->platform }}</td><th>Meeting ID</th><td>{{ $session->meeting_id }}</td></tr>
<tr><th>URL</th><td colspan="3">@if($session->meeting_url)<a href="{{ $session->meeting_url }}" target="_blank">{{ $session->meeting_url }}</a>@endif</td></tr>
<tr><th>Tarif</th><td colspan="3">Rp {{ number_format((float) $session->fee, 0, ',', '.') }}</td></tr>
<tr><th>Keluhan</th><td colspan="3">{!! nl2br(e($session->chief_complaint)) !!}</td></tr>
<tr><th>Asesmen</th><td colspan="3">{!! nl2br(e($session->assessment)) !!}</td></tr>
<tr><th>Plan</th><td colspan="3">{!! nl2br(e($session->plan)) !!}</td></tr>
</table></div></div>
@endsection
