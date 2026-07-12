@extends('layouts.admin')
@section('title','Detail Feedback')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Feedback #{{ $feedback->id }}</h1><div><a href="{{ route('patient-feedbacks.edit', $feedback) }}" class="btn btn-warning">Edit</a><a href="{{ route('patient-feedbacks.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Pasien</th><td>{{ $feedback->is_anonymous ? '(anonim)' : ($feedback->patient->name ?? $feedback->respondent_name ?? '-') }}</td><th>Tgl Kunjungan</th><td>{{ $feedback->visit_date?->format('d M Y') }}</td></tr>
<tr><th>Layanan</th><td>{{ $feedback->service_type }}</td><th>Akan Rekomendasi</th><td>{{ $feedback->would_recommend === null ? '-' : ($feedback->would_recommend ? 'Ya' : 'Tidak') }}</td></tr>
@foreach(['Overall'=>'rating_overall','Dokter'=>'rating_doctor','Perawat'=>'rating_nurse','Fasilitas'=>'rating_facility','Kebersihan'=>'rating_cleanliness','Kecepatan'=>'rating_speed'] as $l => $k)
<tr><th>{{ $l }}</th><td colspan="3">{{ str_repeat('★', (int) $feedback->{$k}) }}{{ str_repeat('☆', 5 - (int) $feedback->{$k}) }} ({{ $feedback->{$k} ?? '-' }})</td></tr>
@endforeach
<tr><th>Disukai</th><td colspan="3">{!! nl2br(e($feedback->positive)) !!}</td></tr>
<tr><th>Perlu Diperbaiki</th><td colspan="3">{!! nl2br(e($feedback->negative)) !!}</td></tr>
<tr><th>Saran</th><td colspan="3">{!! nl2br(e($feedback->suggestion)) !!}</td></tr>
</table>
</div></div>
@endsection
