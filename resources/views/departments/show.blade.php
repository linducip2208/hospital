@extends('layouts.admin')

@section('title', 'Detail Departemen')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Departemen</h1>
    <div>
        <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-building"></i>
                </div>
                <h5>{{ $department->name }}</h5>
                <p class="text-muted mb-1">{{ $department->code ?? 'Kode: -' }}</p>
                <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                    {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Lantai:</strong> {{ $department->floor ?? '-' }}</li>
                <li class="list-group-item"><strong>Telepon:</strong> {{ $department->phone ?? '-' }}</li>
                <li class="list-group-item"><strong>Jumlah Pengguna:</strong> {{ $department->users->count() }} orang</li>
                <li class="list-group-item"><strong>Jumlah Aset:</strong> {{ $department->assets->count() }} aset</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Deskripsi</div>
            <div class="card-body">
                <p>{{ $department->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <span>Daftar Pengguna</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($department->users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengguna</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
