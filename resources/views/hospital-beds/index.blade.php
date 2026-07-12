@extends('layouts.admin')
@section('title','Bed Management')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Manajemen Bed</h1>
    <a href="{{ route('hospital-beds.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Bed</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><select name="status" class="form-select"><option value="">Semua Status</option>@foreach($statuses as $k=>$l)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="room_id" class="form-select"><option value="">Semua Ruangan</option>@foreach($rooms as $r)<option value="{{ $r->id }}" @selected(request('room_id')==$r->id)>{{ $r->name }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i> Filter</button></div>
</form>
<div class="row g-2">
    @php $colors = ['available'=>'success','occupied'=>'danger','reserved'=>'warning','cleaning'=>'info','maintenance'=>'secondary','blocked'=>'dark']; @endphp
    @forelse($beds as $b)
    <div class="col-md-3">
        <div class="card border-{{ $colors[$b->status] ?? 'secondary' }}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $b->bed_code }}</strong>
                    <span class="badge bg-{{ $colors[$b->status] ?? 'secondary' }}">{{ $statuses[$b->status] }}</span>
                </div>
                <div class="small text-muted">{{ $b->room->name ?? '-' }} {{ $b->label ? '· '.$b->label : '' }}</div>
                @if($b->currentPatient)
                    <div class="mt-1 small">{{ $b->currentPatient->name }}</div>
                    <div class="text-muted small">Sejak {{ $b->occupied_since?->format('d/m H:i') }}</div>
                @endif
                <div class="mt-2">
                    <a href="{{ route('hospital-beds.edit', $b) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('hospital-beds.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-4">Belum ada bed</div>
    @endforelse
</div>
{{ $beds->links() }}
</div></div>
@endsection
