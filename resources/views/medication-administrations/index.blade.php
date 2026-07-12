@extends('layouts.admin')

@section('title', 'Pemberian Obat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Pemberian Obat</h1>
    <a href="{{ route('medication-administrations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pemberian Obat</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari pasien, obat..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('medication-administrations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Nama Obat</th>
                        <th>Dosis</th>
                        <th>Rute</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medications as $med)
                    <tr>
                        <td>{{ $loop->iteration + ($medications->currentPage() - 1) * $medications->perPage() }}</td>
                        <td><a href="{{ route('medication-administrations.show', $med) }}" class="text-decoration-none">{{ $med->patient->name ?? '-' }}</a></td>
                        <td>{{ $med->drug_name ?? '-' }}</td>
                        <td>{{ $med->dosage ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $med->route ?? '-' }}</span>
                        </td>
                        <td>{{ $med->administered_at ? $med->administered_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('medication-administrations.show', $med) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('medication-administrations.edit', $med) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('medication-administrations.destroy', $med) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pemberian obat ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pemberian obat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $medications->links() }}
    </div>
</div>
@endsection
