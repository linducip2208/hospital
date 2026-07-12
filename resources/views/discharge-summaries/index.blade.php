@extends('layouts.admin')
@section('title','Resume Medis Pulang')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Resume Medis Pulang</h1>
    <a href="{{ route('discharge-summaries.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Resume</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Dokter</th><th>MRS</th><th>KRS</th><th>Kondisi</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($summaries as $s)
<tr>
    <td><code>{{ $s->summary_no }}</code></td>
    <td>{{ $s->patient->name ?? '-' }}</td>
    <td>{{ $s->doctor->name ?? '-' }}</td>
    <td>{{ $s->admission_date?->format('d M Y') }}</td>
    <td>{{ $s->discharge_date?->format('d M Y') }}</td>
    <td>{{ \App\Models\DischargeSummary::CONDITIONS[$s->discharge_condition] ?? $s->discharge_condition }}</td>
    <td>
        <a href="{{ route('discharge-summaries.print', $s) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('discharge-summaries.show', $s) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('discharge-summaries.edit', $s) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('discharge-summaries.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>
{{ $summaries->links() }}
</div></div>
@endsection
