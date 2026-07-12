@extends('layouts.admin')
@section('title','Code Blue')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Aktivasi Code Blue</h1><a href="{{ route('code-blue-activations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Aktivasi Baru</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Lokasi</th><th>Aktivasi</th><th>Respon</th><th>Outcome</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($codes as $c)
<tr>
    <td><code>{{ $c->code_no }}</code></td>
    <td>{{ $c->patient->name ?? '-' }}</td>
    <td>{{ $c->location }}</td>
    <td>{{ $c->activation_time?->format('d M Y H:i') }}</td>
    <td>{{ $c->response_time !== null ? round($c->response_time/60,1).' menit' : '-' }}</td>
    <td><span class="badge bg-{{ ['rosc'=>'success','died'=>'danger','transferred'=>'info','ongoing'=>'warning'][$c->outcome] }}">{{ $c->outcome }}</span></td>
    <td>
        <a href="{{ route('code-blue-activations.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('code-blue-activations.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('code-blue-activations.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada aktivasi</td></tr>@endforelse
</tbody></table></div>{{ $codes->links() }}</div></div>
@endsection
