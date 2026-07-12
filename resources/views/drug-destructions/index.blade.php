@extends('layouts.admin')
@section('title', 'Berita Acara Pemusnahan Obat')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Pemusnahan Obat</h1>
    <a href="{{ route('drug-destructions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat BA</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Tanggal</th><th>Lokasi</th><th>Metode</th><th>APJ</th><th>Item</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($destructions as $d)
<tr>
    <td><code>{{ $d->destruction_no }}</code></td>
    <td>{{ $d->destruction_date?->format('d M Y') }}</td>
    <td>{{ $d->location }}</td>
    <td>{{ $d->method }}</td>
    <td>{{ $d->responsible_pharmacist }}</td>
    <td>{{ $d->items->count() }}</td>
    <td>
        <a href="{{ route('drug-destructions.print', $d) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('drug-destructions.show', $d) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('drug-destructions.edit', $d) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('drug-destructions.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada BA</td></tr>@endforelse
</tbody></table></div>
{{ $destructions->links() }}
</div></div>
@endsection
