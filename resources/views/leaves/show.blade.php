@extends('layouts.admin')

@section('title', 'Detail Cuti')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Cuti</h1>
    <div>
        @if($leave->status === 'pending')
            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Setujui cuti ini?')"><i class="bi bi-check-lg"></i> Setujui</button>
            </form>
            <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak cuti ini?')"><i class="bi bi-x-lg"></i> Tolak</button>
            </form>
        @endif
        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <h5>{{ $leave->user->name ?? '-' }}</h5>
                <p class="text-muted mb-1">
                    @php
                        $leaveTypeLabels = ['tahunan' => 'Cuti Tahunan', 'sakit' => 'Cuti Sakit', 'melahirkan' => 'Cuti Melahirkan', 'alasan_penting' => 'Cuti Alasan Penting', 'cuti_besar' => 'Cuti Besar'];
                    @endphp
                    {{ $leaveTypeLabels[$leave->leave_type] ?? $leave->leave_type }}
                </p>
                <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ $leave->status === 'approved' ? 'Disetujui' : ($leave->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Tgl Mulai:</strong> {{ $leave->start_date ? $leave->start_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Tgl Selesai:</strong> {{ $leave->end_date ? $leave->end_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Total Hari:</strong> {{ $leave->total_days ?? '-' }}</li>
                @if($leave->approver)
                    <li class="list-group-item"><strong>Diproses Oleh:</strong> {{ $leave->approver->name ?? '-' }}</li>
                    <li class="list-group-item"><strong>Tgl Diproses:</strong> {{ $leave->approved_at ? $leave->approved_at->format('d/m/Y H:i') : '-' }}</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Alasan</div>
            <div class="card-body">
                <p>{{ $leave->reason ?? 'Tidak ada alasan.' }}</p>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Pengguna</div>
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $leave->user->name ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $leave->user->email ?? '-' }}</p>
                <p><strong>Role:</strong> {{ $leave->user->role ?? '-' }}</p>
            </div>
        </div>

        @if($leave->notes)
            <div class="card shadow-sm">
                <div class="card-header">Catatan</div>
                <div class="card-body">
                    <p>{{ $leave->notes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
