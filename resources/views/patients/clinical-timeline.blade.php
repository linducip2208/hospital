@extends('layouts.admin')
@section('title','Clinical Timeline · '.$patient->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1">Clinical Timeline</h1><p class="text-muted mb-0">{{ $patient->name }} · MRN {{ $patient->mr_number ?? '-' }} · {{ $patient->gender ?? '-' }}</p></div><a href="{{ route('patients.show',$patient) }}" class="btn btn-outline-secondary">Profil Pasien</a></div>
<div class="alert alert-warning"><strong>Allergy warning:</strong> {{ $patient->allergies ?: 'Tidak ada alergi tercatat' }} · Payer: {{ $patient->bpjs_number ? 'BPJS terdaftar' : 'Umum' }}</div>
<div class="card shadow-sm"><div class="card-body"><div class="timeline">@forelse($events as $event)<div class="border-start border-3 border-primary ps-3 pb-4 ms-2"><div class="small text-muted">{{ \Carbon\Carbon::parse($event['at'])->format('d/m/Y H:i') }} · {{ $event['type'] }}</div><div class="fw-semibold">{{ $event['label'] }}</div></div>@empty<p class="text-muted mb-0">Belum ada aktivitas klinis.</p>@endforelse</div></div></div>
@endsection
