@extends('layouts.admin')

@section('title', 'Data Pegawai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Pegawai</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pegawai</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, jabatan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="department" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" @selected(request('department') === $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="employment_status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="permanent" @selected(request('employment_status') === 'permanent')>Tetap</option>
                    <option value="contract" @selected(request('employment_status') === 'contract')>Kontrak</option>
                    <option value="probation" @selected(request('employment_status') === 'probation')>Percobaan</option>
                    <option value="intern" @selected(request('employment_status') === 'intern')>Magang</option>
                    <option value="resigned" @selected(request('employment_status') === 'resigned')>Resign</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('department') || request('employment_status'))
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kode Pegawai</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                        <td><a href="{{ route('employees.show', $employee) }}" class="text-decoration-none">{{ $employee->user->name ?? '-' }}</a></td>
                        <td>{{ $employee->employee_code ?? '-' }}</td>
                        <td>{{ $employee->position ?? '-' }}</td>
                        <td>{{ $employee->department ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = ['permanent' => 'success', 'contract' => 'info', 'probation' => 'warning', 'intern' => 'secondary', 'resigned' => 'danger'];
                                $statusLabels = ['permanent' => 'Tetap', 'contract' => 'Kontrak', 'probation' => 'Percobaan', 'intern' => 'Magang', 'resigned' => 'Resign'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$employee->employment_status] ?? 'secondary' }}">
                                {{ $statusLabels[$employee->employment_status] ?? $employee->employment_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pegawai</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->links() }}
    </div>
</div>
@endsection
