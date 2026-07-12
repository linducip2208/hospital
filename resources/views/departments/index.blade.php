@extends('layouts.admin')

@section('title', 'Data Departemen')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Departemen</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Departemen</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, deskripsi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                    <option value="0" @selected(request('is_active') === '0')>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('is_active') !== null && request('is_active') !== '')
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Lantai</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}</td>
                        <td><a href="{{ route('departments.show', $department) }}" class="text-decoration-none">{{ $department->name }}</a></td>
                        <td>{{ $department->code ?? '-' }}</td>
                        <td>{{ $department->floor ?? '-' }}</td>
                        <td>{{ $department->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                                {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus departemen ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data departemen</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $departments->links() }}
    </div>
</div>
@endsection
