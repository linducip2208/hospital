@extends('layouts.admin')
@section('title','Odontogram')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Odontogram</h1><a href="{{ route('odontograms.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Periksa Gigi</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>Tgl Periksa</th><th>Pasien</th><th>Dokter</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($odontograms as $o)
<tr>
    <td>{{ $o->exam_date?->format('d M Y') }}</td>
    <td>{{ $o->patient->name ?? '-' }}</td>
    <td>{{ $o->doctor->name ?? '-' }}</td>
    <td>
        <a href="{{ route('odontograms.show', $o) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('odontograms.edit', $o) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('odontograms.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="4" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $odontograms->links() }}</div></div>
@endsection
