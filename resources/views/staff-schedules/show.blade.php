@extends('layouts.admin')

@section('title', 'Detail Jadwal Staff')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Jadwal Staff</h1>
    <div>
        <a href="{{ route('staff-schedules.edit', $staffSchedule) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('staff-schedules.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 mb-2 text-primary">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h5>{{ $staffSchedule->user->name ?? '-' }}</h5>
                @php
                    $shiftColors = [
                        'morning' => 'warning',
                        'afternoon' => 'info',
                        'night' => 'dark',
                        'on_call' => 'danger',
                        'off' => 'secondary',
                    ];
                    $shiftLabels = [
                        'morning' => 'Pagi',
                        'afternoon' => 'Siang',
                        'night' => 'Malam',
                        'on_call' => 'On Call',
                        'off' => 'Off',
                    ];
                @endphp
                <span class="badge bg-{{ $shiftColors[$staffSchedule->shift_type] ?? 'secondary' }} fs-6">
                    {{ $shiftLabels[$staffSchedule->shift_type] ?? ucfirst($staffSchedule->shift_type) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Tanggal:</strong> {{ $staffSchedule->shift_date ? $staffSchedule->shift_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Jam:</strong> {{ $staffSchedule->start_time && $staffSchedule->end_time ? $staffSchedule->start_time . ' - ' . $staffSchedule->end_time : '-' }}</li>
                <li class="list-group-item"><strong>Departemen:</strong> {{ $staffSchedule->department ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($staffSchedule->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $staffSchedule->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
