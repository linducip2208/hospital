@extends('layouts.admin')
@section('title','Insiden Keselamatan Pasien')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Insiden Keselamatan Pasien (IKP)</h1>
    <a href="{{ route('patient-safety-incidents.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Lapor Insiden</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><select name="type" class="form-select"><option value="">Semua Jenis</option>@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(request('type')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">Semua Status</option>@foreach(['reported'=>'Dilaporkan','investigating'=>'Investigasi','closed'=>'Selesai'] as $k=>$l)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i></button></div>
</form>
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Jenis</th><th>Severity</th><th>Pasien</th><th>Lokasi</th><th>Terjadi</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($incidents as $i)
<tr>
    <td><code>{{ $i->incident_no }}</code></td>
    <td><span class="badge bg-{{ ['knc'=>'info','ktc'=>'success','ktd'=>'warning','kpc'=>'primary','sentinel'=>'danger'][$i->incident_type] }}">{{ strtoupper($i->incident_type) }}</span></td>
    <td>{{ $i->severity }}</td>
    <td>{{ $i->patient->name ?? '-' }}</td>
    <td>{{ $i->location }}</td>
    <td>{{ $i->occurred_at?->format('d M Y H:i') }}</td>
    <td>{{ $i->status }}</td>
    <td>
        <a href="{{ route('patient-safety-incidents.show', $i) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('patient-safety-incidents.edit', $i) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('patient-safety-incidents.destroy', $i) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="8" class="text-center text-muted py-4">Belum ada laporan</td></tr>@endforelse
</tbody></table></div>
{{ $incidents->links() }}
</div></div>
@endsection
