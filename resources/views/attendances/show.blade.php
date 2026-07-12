@extends('layouts.admin')

@section('title', 'Detail Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Absensi</h1>
    <div>
        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h5>{{ $attendance->user->name ?? '-' }}</h5>
                <p class="text-muted mb-1">{{ $attendance->date ? $attendance->date->format('d/m/Y') : '-' }}</p>
                @php
                    $attendanceColors = ['present' => 'success', 'late' => 'warning', 'sick' => 'info', 'leave' => 'primary', 'absent' => 'danger', 'half_day' => 'secondary'];
                    $attendanceLabels = ['present' => 'Hadir', 'late' => 'Terlambat', 'sick' => 'Sakit', 'leave' => 'Izin', 'absent' => 'Alfa', 'half_day' => 'Setengah Hari'];
                @endphp
                <span class="badge bg-{{ $attendanceColors[$attendance->status] ?? 'secondary' }}">
                    {{ $attendanceLabels[$attendance->status] ?? $attendance->status }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Jam Masuk:</strong> {{ $attendance->check_in ?? '-' }}</li>
                <li class="list-group-item"><strong>Jam Keluar:</strong> {{ $attendance->check_out ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Pengguna</div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $attendance->user->name ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $attendance->user->email ?? '-' }}</p>
                <p><strong>Role:</strong> {{ $attendance->user->role ?? '-' }}</p>
            </div>
        </div>
        @if($attendance->notes)
            <div class="card shadow-sm">
                <div class="card-header">Catatan</div>
                <div class="card-body">
                    <p>{{ $attendance->notes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
