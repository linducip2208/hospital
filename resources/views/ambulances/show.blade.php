@extends('layouts.admin')

@section('title', 'Detail Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Ambulans</h1>
    <div>
        <a href="{{ route('ambulances.edit', $ambulance) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('ambulances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 mb-2 text-danger">
                    <i class="bi bi-truck"></i>
                </div>
                <h5>{{ $ambulance->vehicle_number }}</h5>
                @php
                    $statusColors = [
                        'available' => 'success',
                        'on_duty' => 'warning',
                        'maintenance' => 'info',
                        'out_of_service' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$ambulance->status] ?? 'secondary' }} fs-6">
                    {{ str_replace('_', ' ', ucfirst($ambulance->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Model:</strong> {{ $ambulance->model ?? '-' }}</li>
                <li class="list-group-item"><strong>Tipe:</strong> {{ $ambulance->type ?? '-' }}</li>
                <li class="list-group-item"><strong>Supir:</strong> {{ $ambulance->driver_name ?? '-' }}</li>
                <li class="list-group-item"><strong>Telp Supir:</strong> {{ $ambulance->driver_phone ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($ambulance->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $ambulance->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
