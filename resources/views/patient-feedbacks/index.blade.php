@extends('layouts.admin')
@section('title','Feedback Pasien')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Feedback Pasien</h1><a href="{{ route('patient-feedbacks.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a></div>
<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h5>{{ $stats['count'] }}</h5><div class="text-muted small">Total Respon</div></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h5>{{ number_format((float) ($stats['avg_overall'] ?? 0), 2) }}/5</h5><div class="text-muted small">Rating Overall</div></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h5>{{ number_format((float) ($stats['avg_doctor'] ?? 0), 2) }}/5</h5><div class="text-muted small">Rating Dokter</div></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><h5>{{ $stats['recommend_pct'] }}%</h5><div class="text-muted small">Akan Merekomendasikan</div></div></div></div>
</div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>Tgl</th><th>Pasien</th><th>Layanan</th><th>Overall</th><th>Komentar</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($feedbacks as $f)
<tr>
    <td>{{ $f->created_at?->format('d M Y') }}</td>
    <td>{{ $f->is_anonymous ? '(anonim)' : ($f->patient->name ?? $f->respondent_name ?? '-') }}</td>
    <td>{{ $f->service_type }}</td>
    <td>{{ str_repeat('★', (int) $f->rating_overall) }}{{ str_repeat('☆', 5 - (int) $f->rating_overall) }}</td>
    <td>{{ Str::limit($f->positive ?: $f->negative ?: $f->suggestion, 50) }}</td>
    <td><span class="badge bg-secondary">{{ $f->status }}</span></td>
    <td>
        <a href="{{ route('patient-feedbacks.show', $f) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('patient-feedbacks.edit', $f) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('patient-feedbacks.destroy', $f) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $feedbacks->links() }}</div></div>
@endsection
