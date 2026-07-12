@extends('layouts.admin')
@section('title','Maintenance Alat')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Maintenance Alat Medis</h1><a href="{{ route('equipment-maintenances.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Jadwal Baru</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>Alat</th><th>Jadwal</th><th>Tipe</th><th>Pelaksana</th><th>Hasil</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($maintenances as $m)
<tr>
    <td>{{ $m->asset->name ?? '-' }}</td>
    <td>{{ $m->scheduled_date?->format('d M Y') }}</td>
    <td>{{ $m->maintenance_type }}</td>
    <td>{{ $m->performer ?? '-' }}</td>
    <td>{{ $m->result ?? '-' }}</td>
    <td><span class="badge bg-{{ ['scheduled'=>'secondary','in_progress'=>'primary','done'=>'success','cancelled'=>'danger'][$m->status] }}">{{ $m->status }}</span></td>
    <td>
        <a href="{{ route('equipment-maintenances.show', $m) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('equipment-maintenances.edit', $m) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('equipment-maintenances.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $maintenances->links() }}</div></div>
@endsection
