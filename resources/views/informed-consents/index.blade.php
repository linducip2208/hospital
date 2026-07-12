@extends('layouts.admin')
@section('title', 'Surat Persetujuan Tindakan')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Persetujuan Tindakan</h1>
    <a href="{{ route('informed-consents.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Surat</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="kind" class="form-select">
            <option value="">Semua Jenis</option>
            @foreach($kinds as $k => $label)<option value="{{ $k }}" @selected(request('kind')===$k)>{{ $label }}</option>@endforeach
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i> Filter</button></div>
</form>
<div class="table-responsive">
<table class="table table-hover">
    <thead class="table-light"><tr><th>No.</th><th>Jenis</th><th>Pasien</th><th>Tindakan</th><th>Penandatangan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($consents as $c)
    <tr>
        <td><code>{{ $c->consent_no }}</code></td>
        <td>{{ $kinds[$c->kind] ?? $c->kind }}</td>
        <td>{{ $c->patient->name ?? '-' }}</td>
        <td>{{ Str::limit($c->procedure_name, 35) }}</td>
        <td>{{ $c->signed_by_name ?? '-' }}</td>
        <td>{{ $c->signed_at?->format('d M Y H:i') }}</td>
        <td>
            <a href="{{ route('informed-consents.print', $c) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
            <a href="{{ route('informed-consents.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
            <a href="{{ route('informed-consents.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('informed-consents.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
        </td>
    </tr>
    @empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data</td></tr>@endforelse
    </tbody>
</table>
</div>
{{ $consents->links() }}
</div></div>
@endsection
