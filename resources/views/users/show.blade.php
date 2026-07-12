@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pengguna</h1>
    <div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-person-circle"></i></div>
                <h5>{{ $user->name }}</h5>
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
                <p class="text-muted mb-1">{{ $user->username ?? '-' }}</p>
                <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                    {{ $roleLabels[$user->role] ?? $user->role }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>Username:</strong> {{ $user->username ?? '-' }}</li>
                <li class="list-group-item"><strong>Terdaftar:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">Detail Pengguna</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr><td style="width:200px"><strong>Nama</strong></td><td>{{ $user->name }}</td></tr>
                        <tr><td><strong>Username</strong></td><td>{{ $user->username ?? '-' }}</td></tr>
                        <tr><td><strong>Email</strong></td><td>{{ $user->email }}</td></tr>
                        <tr><td><strong>Role</strong></td>
                            <td>
                                <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                    {{ $roleLabels[$user->role] ?? $user->role }}
                                </span>
                            </td>
                        </tr>
                        <tr><td><strong>Terdaftar</strong></td><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
