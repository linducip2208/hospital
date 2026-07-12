@extends('layouts.admin')

@section('title', 'Asuhan Keperawatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Asuhan Keperawatan</h1>
    <a href="{{ route('nursing-cares.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Asuhan Keperawatan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('nursing-cares.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pasien</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nursingCares as $care)
                    <tr>
                        <td>{{ $loop->iteration + ($nursingCares->currentPage() - 1) * $nursingCares->perPage() }}</td>
                        <td><a href="{{ route('nursing-cares.show', $care) }}" class="text-decoration-none">{{ $care->patient->name ?? '-' }}</a></td>
                        <td>{{ $care->care_date ? $care->care_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $care->status === 'completed' ? 'success' : ($care->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                {{ $care->status === 'completed' ? 'Selesai' : ($care->status === 'ongoing' ? 'Berjalan' : 'Dibatalkan') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('nursing-cares.show', $care) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('nursing-cares.edit', $care) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('nursing-cares.destroy', $care) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data asuhan keperawatan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data asuhan keperawatan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $nursingCares->links() }}
    </div>
</div>
@endsection
