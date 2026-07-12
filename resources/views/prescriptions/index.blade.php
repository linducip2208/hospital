@extends('layouts.admin')
@section('title', 'Resep')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Resep Dokter</h1>
    <a href="{{ route('prescriptions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Resep</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<div class="table-responsive">
<table class="table table-hover">
    <thead class="table-light"><tr><th>No. R/</th><th>Tanggal</th><th>Pasien</th><th>Dokter</th><th>Item</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($prescriptions as $r)
    <tr>
        <td><code>{{ $r->rx_no }}</code></td>
        <td>{{ $r->prescribed_at?->format('d M Y') }}</td>
        <td>{{ $r->patient->name ?? '-' }}</td>
        <td>{{ $r->doctor->name ?? '-' }}</td>
        <td>{{ $r->items->count() }} obat</td>
        <td><span class="badge bg-{{ ['draft'=>'secondary','issued'=>'success','dispensed'=>'info','cancelled'=>'danger'][$r->status] ?? 'secondary' }}">{{ $r->status }}</span></td>
        <td>
            <a href="{{ route('prescriptions.print', $r) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak Resep"><i class="bi bi-printer"></i></a>
            <a href="{{ route('prescriptions.print-labels', $r) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Cetak Etiket"><i class="bi bi-tag"></i></a>
            <a href="{{ route('prescriptions.show', $r) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
            <a href="{{ route('prescriptions.edit', $r) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('prescriptions.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
        </td>
    </tr>
    @empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada resep</td></tr>@endforelse
    </tbody>
</table>
</div>
{{ $prescriptions->links() }}
</div></div>
@endsection
