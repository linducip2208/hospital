@extends('layouts.admin')

@section('title', 'Detail Panggilan Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Panggilan Ambulans</h1>
    <div>
        <a href="{{ route('ambulance-calls.edit', $ambulanceCall) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('ambulance-calls.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 mb-2 text-danger">
                    <i class="bi bi-telephone-plus-fill"></i>
                </div>
                <h5>{{ $ambulanceCall->patient_name }}</h5>
                @php
                    $statusColors = [
                        'pending' => 'secondary',
                        'dispatched' => 'info',
                        'en_route' => 'warning',
                        'arrived' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$ambulanceCall->status] ?? 'secondary' }} fs-6">
                    {{ str_replace('_', ' ', ucfirst($ambulanceCall->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Ambulans:</strong> {{ $ambulanceCall->ambulance->vehicle_number ?? '-' }}</li>
                <li class="list-group-item"><strong>Supir:</strong> {{ $ambulanceCall->ambulance->driver_name ?? '-' }}</li>
                <li class="list-group-item"><strong>Lokasi Jemput:</strong> {{ $ambulanceCall->pickup_location }}</li>
                <li class="list-group-item"><strong>Tujuan:</strong> {{ $ambulanceCall->destination ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Panggil:</strong> {{ $ambulanceCall->call_date ? $ambulanceCall->call_date->format('d/m/Y H:i') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($ambulanceCall->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $ambulanceCall->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
