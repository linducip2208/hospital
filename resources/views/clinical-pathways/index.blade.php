@extends('layouts.admin')
@section('title','Clinical Pathway')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Clinical Pathway</h1><a href="{{ route('clinical-pathways.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Pathway</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>Kode</th><th>Nama</th><th>Diagnosis</th><th>LOS</th><th>Aktif</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($pathways as $p)
<tr>
    <td><code>{{ $p->code }}</code></td>
    <td>{{ $p->name }}</td>
    <td>{{ $p->diagnosis_code }} {{ $p->diagnosis }}</td>
    <td>{{ $p->expected_los_days }}h</td>
    <td>@if($p->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
    <td>
        <a href="{{ route('clinical-pathways.show', $p) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('clinical-pathways.edit', $p) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('clinical-pathways.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $pathways->links() }}</div></div>
@endsection
