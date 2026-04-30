@extends('layouts.admin')

@section('title', 'Data Dokter')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Dokter</h1>
    <a href="{{ route('doctors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Dokter</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, spesialisasi..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Tidak Aktif</option>
                    <option value="on_leave" @selected(request('status') === 'on_leave')>Cuti</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Spesialisasi</th><th>STR</th><th>No. Telp</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr>
                        <td>{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                        <td><a href="{{ route('doctors.show', $doctor) }}" class="text-decoration-none">{{ $doctor->name }}</a></td>
                        <td>{{ $doctor->specialization ?? '-' }}</td>
                        <td>{{ $doctor->str_number ?? '-' }}</td>
                        <td>{{ $doctor->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $doctor->status === 'active' ? 'success' : ($doctor->status === 'on_leave' ? 'warning' : 'secondary') }}">
                                {{ $doctor->status === 'active' ? 'Aktif' : ($doctor->status === 'on_leave' ? 'Cuti' : 'Tidak Aktif') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokter ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data dokter</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $doctors->links() }}
    </div>
</div>
@endsection
