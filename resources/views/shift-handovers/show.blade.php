@extends('layouts.admin')

@section('title', 'Detail Serah Terima Shift')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Serah Terima Shift</h1>
    <div>
        <a href="{{ route('shift-handovers.edit', $shiftHandover) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('shift-handovers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <div class="display-1 text-success mb-2">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <h5>Serah Terima Shift</h5>
                <span class="badge bg-{{ $shiftHandover->shift_type === 'pagi' ? 'info' : ($shiftHandover->shift_type === 'siang' ? 'warning' : 'secondary') }}">
                    Shift {{ ucfirst($shiftHandover->shift_type) }}
                </span>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Informasi Shift</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tanggal</span>
                    <strong>{{ $shiftHandover->shift_date ? $shiftHandover->shift_date->format('d/m/Y H:i') : '-' }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tipe Shift</span>
                    <strong>{{ ucfirst($shiftHandover->shift_type) }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Dari</span>
                    <strong>{{ $shiftHandover->fromNurse->name ?? '-' }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Ke</span>
                    <strong>{{ $shiftHandover->toNurse->name ?? '-' }}</strong>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header"><strong>Ringkasan Pasien</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $shiftHandover->patient_summary ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark"><strong>Tugas Tertunda</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $shiftHandover->tasks_pending ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-danger text-white"><strong>Insiden</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $shiftHandover->incidents ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white"><strong>Status Peralatan</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $shiftHandover->equipment_status ?: '-' }}</p>
            </div>
        </div>
        @if($shiftHandover->notes)
        <div class="card shadow-sm">
            <div class="card-header"><strong>Catatan</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $shiftHandover->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
