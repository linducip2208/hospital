@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Pengguna</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pengguna</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="doctor" @selected(request('role') === 'doctor')>Dokter</option>
                    <option value="staff" @selected(request('role') === 'staff')>Staff</option>
                    <option value="nurse" @selected(request('role') === 'nurse')>Perawat</option>
                    <option value="pharmacist" @selected(request('role') === 'pharmacist')>Apoteker</option>
                    <option value="cashier" @selected(request('role') === 'cashier')>Kasir</option>
                    <option value="lab_technician" @selected(request('role') === 'lab_technician')>Lab Technician</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td><a href="{{ route('users.show', $user) }}" class="text-decoration-none">{{ $user->name }}</a></td>
                        <td>{{ $user->username ?? '-' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'doctor' => 'primary',
                                    'staff' => 'info',
                                    'nurse' => 'success',
                                    'pharmacist' => 'warning',
                                    'cashier' => 'secondary',
                                    'lab_technician' => 'dark',
                                ];
                                $roleLabels = [
                                    'admin' => 'Admin',
                                    'doctor' => 'Dokter',
                                    'staff' => 'Staff',
                                    'nurse' => 'Perawat',
                                    'pharmacist' => 'Apoteker',
                                    'cashier' => 'Kasir',
                                    'lab_technician' => 'Lab Tech',
                                ];
                            @endphp
                            <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
