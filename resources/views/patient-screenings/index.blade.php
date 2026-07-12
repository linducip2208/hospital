@extends('layouts.admin')
@section('title','Skrining Pasien')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Skrining Pasien</h1>
    <a href="{{ route('patient-screenings.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Skrining</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><select name="type" class="form-select"><option value="">Semua Jenis</option>@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(request('type')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i></button></div>
</form>
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Jenis</th><th>Pasien</th><th>Skor</th><th>Risiko</th><th>Tanggal</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($screenings as $s)
<tr>
    <td><code>{{ $s->screening_no }}</code></td>
    <td>{{ $types[$s->type] ?? $s->type }}</td>
    <td>{{ $s->patient->name ?? '-' }}</td>
    <td>{{ $s->score }}</td>
    <td><span class="badge bg-{{ ['low'=>'success','moderate'=>'warning','high'=>'danger'][$s->risk_level] ?? 'secondary' }}">{{ $s->risk_level }}</span></td>
    <td>{{ $s->screened_at?->format('d M Y H:i') }}</td>
    <td>
        <a href="{{ route('patient-screenings.print', $s) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('patient-screenings.show', $s) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('patient-screenings.edit', $s) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('patient-screenings.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>
{{ $screenings->links() }}
</div></div>
@endsection
