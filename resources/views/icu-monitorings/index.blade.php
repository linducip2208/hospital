@extends('layouts.admin')
@section('title','Monitoring ICU')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Monitoring ICU</h1><a href="{{ route('icu-monitorings.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Vital</a></div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><select name="patient_id" class="form-select"><option value="">Semua Pasien</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(request('patient_id')==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i></button></div>
</form>
<div class="table-responsive"><table class="table table-hover small">
<thead class="table-light"><tr><th>Waktu</th><th>Pasien</th><th>Bed</th><th>Suhu</th><th>HR</th><th>RR</th><th>BP</th><th>SpO2</th><th>GCS</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($monitorings as $m)
<tr>
    <td>{{ $m->recorded_at?->format('d/m H:i') }}</td>
    <td>{{ $m->patient->name ?? '-' }}</td>
    <td>{{ $m->bed?->room?->name }}/{{ $m->bed?->bed_code }}</td>
    <td>{{ $m->temperature }}°C</td>
    <td>{{ $m->hr }}</td>
    <td>{{ $m->rr }}</td>
    <td>{{ $m->sbp }}/{{ $m->dbp }}</td>
    <td>{{ $m->spo2 }}%</td>
    <td>{{ $m->gcs }}</td>
    <td>
        <a href="{{ route('icu-monitorings.show', $m) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('icu-monitorings.edit', $m) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('icu-monitorings.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="10" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $monitorings->links() }}</div></div>
@endsection
