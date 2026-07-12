@extends('layouts.admin')
@section('title', 'Estimasi Biaya')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Estimasi Biaya Tindakan</h1>
    <a href="{{ route('cost-estimates.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Estimasi</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Tanggal</th><th>Pasien</th><th>Tindakan</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($estimates as $e)
<tr>
    <td><code>{{ $e->estimate_no }}</code></td>
    <td>{{ $e->estimate_date?->format('d M Y') }}</td>
    <td>{{ $e->patient->name ?? '-' }}</td>
    <td>{{ $e->procedure_name }}</td>
    <td>Rp {{ number_format((float) $e->total_amount, 0, ',', '.') }}</td>
    <td><span class="badge bg-secondary">{{ $e->status }}</span></td>
    <td>
        <a href="{{ route('cost-estimates.print', $e) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('cost-estimates.show', $e) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('cost-estimates.edit', $e) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('cost-estimates.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>
{{ $estimates->links() }}
</div></div>
@endsection
