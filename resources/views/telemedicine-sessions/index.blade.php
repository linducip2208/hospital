@extends('layouts.admin')
@section('title','Telemedicine')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Sesi Telemedicine</h1><a href="{{ route('telemedicine-sessions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Jadwalkan</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Dokter</th><th>Jadwal</th><th>Platform</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($sessions as $s)
<tr>
    <td><code>{{ $s->session_no }}</code></td>
    <td>{{ $s->patient->name ?? '-' }}</td>
    <td>{{ $s->doctor->name ?? '-' }}</td>
    <td>{{ $s->scheduled_at?->format('d M Y H:i') }}</td>
    <td>{{ $s->platform }}</td>
    <td><span class="badge bg-{{ ['scheduled'=>'primary','ongoing'=>'success','completed'=>'info','no_show'=>'warning','cancelled'=>'danger'][$s->status] }}">{{ $s->status }}</span></td>
    <td>
        @if($s->meeting_url)<a href="{{ $s->meeting_url }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-camera-video"></i></a>@endif
        <a href="{{ route('telemedicine-sessions.show', $s) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('telemedicine-sessions.edit', $s) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('telemedicine-sessions.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada sesi</td></tr>@endforelse
</tbody></table></div>{{ $sessions->links() }}</div></div>
@endsection
