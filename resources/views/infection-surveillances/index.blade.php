@extends('layouts.admin')
@section('title','Surveilans Infeksi (HAIs)')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Surveilans Infeksi</h1><a href="{{ route('infection-surveillances.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Kasus</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Tipe</th><th>Lokasi</th><th>Organisme</th><th>Outcome</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($cases as $c)
<tr>
    <td><code>{{ $c->case_no }}</code></td>
    <td>{{ $c->patient->name ?? '-' }}</td>
    <td>{{ strtoupper($c->infection_type) }}</td>
    <td>{{ $c->site }}</td>
    <td>{{ $c->organism ?? '-' }}</td>
    <td>{{ $c->outcome }}</td>
    <td>
        <a href="{{ route('infection-surveillances.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('infection-surveillances.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('infection-surveillances.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $cases->links() }}</div></div>
@endsection
