@extends('layouts.admin')
@section('title', 'Surat Keterangan Medis')
@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header">
    <h1 class="h2">Surat Keterangan Medis</h1>
    <a href="{{ route('medical-certificates.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Surat</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">Semua Jenis</option>
                    @foreach($types as $k => $label)
                        <option value="{{ $k }}" @selected(request('type') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="No. Surat">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-filter"></i> Filter</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>No. Surat</th><th>Jenis</th><th>Pasien</th><th>Dokter</th><th>Tgl Terbit</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($certificates as $c)
                    <tr>
                        <td><code>{{ $c->cert_no }}</code></td>
                        <td>{{ $types[$c->type] ?? $c->type }}</td>
                        <td>{{ $c->patient->name ?? '-' }}</td>
                        <td>{{ $c->doctor->name ?? '-' }}</td>
                        <td>{{ $c->issue_date?->format('d M Y') }}</td>
                        <td><span class="badge bg-{{ $c->status === 'issued' ? 'success' : ($c->status === 'cancelled' ? 'danger' : 'secondary') }}">{{ $c->status }}</span></td>
                        <td>
                            <a href="{{ route('medical-certificates.print', $c) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
                            <a href="{{ route('medical-certificates.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('medical-certificates.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('medical-certificates.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus surat?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada surat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $certificates->links() }}
    </div>
</div>
@endsection
