@extends('layouts.admin')
@section('title','Encounter / Kunjungan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><h1 class="h3 mb-0">Encounter / Kunjungan</h1><a href="{{ route('encounters.create') }}" class="btn btn-primary">+ Encounter Baru</a></div>
<div class="card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>No</th><th>Pasien</th><th>Unit</th><th>Tipe</th><th>Status</th><th></th></tr></thead><tbody>@forelse($encounters as $encounter)<tr><td><a href="{{ route('encounters.show',$encounter) }}">{{ $encounter->encounter_no }}</a></td><td>{{ $encounter->patient->name }}</td><td>{{ $encounter->polyclinic?->name ?? '-' }}</td><td>{{ $encounter->encounter_type }}</td><td><span class="badge bg-secondary">{{ $encounter->status }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('patients.timeline',$encounter->patient) }}">Timeline</a></td></tr>@empty<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada encounter.</td></tr>@endforelse</tbody></table></div></div>{{ $encounters->links() }}
@endsection
