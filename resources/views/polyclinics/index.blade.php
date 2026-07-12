@extends('layouts.admin')

@section('title', 'Poli / Klinik')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Poli / Klinik</h1>
    <a href="{{ route('polyclinics.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Poli</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari kode, nama poli..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="is_active" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                    <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('is_active') !== null && request('is_active') !== '')
                    <a href="{{ route('polyclinics.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Poli</th>
                        <th>Lantai</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polyclinics as $polyclinic)
                    <tr>
                        <td>{{ $loop->iteration + ($polyclinics->currentPage() - 1) * $polyclinics->perPage() }}</td>
                        <td>{{ $polyclinic->code ?? '-' }}</td>
                        <td><a href="{{ route('polyclinics.show', $polyclinic) }}" class="text-decoration-none">{{ $polyclinic->name }}</a></td>
                        <td>{{ $polyclinic->floor ?? '-' }}</td>
                        <td>{{ $polyclinic->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $polyclinic->is_active ? 'success' : 'danger' }}">
                                {{ $polyclinic->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('polyclinics.show', $polyclinic) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('polyclinics.edit', $polyclinic) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('polyclinics.destroy', $polyclinic) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus poli ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data poli</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $polyclinics->links() }}
    </div>
</div>
@endsection
