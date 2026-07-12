@extends('layouts.admin')

@section('title', 'Detail Operasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Operasi</h1>
    <div>
        <a href="{{ route('surgeries.edit', $surgery) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('surgeries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 mb-2 text-primary">
                    <i class="bi bi-scissors"></i>
                </div>
                <h5>{{ $surgery->name }}</h5>
                @php
                    $statusColors = [
                        'scheduled' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$surgery->status] ?? 'secondary' }} fs-6">
                    {{ str_replace('_', ' ', ucfirst($surgery->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $surgery->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $surgery->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Operasi:</strong> {{ $surgery->scheduled_date ? $surgery->scheduled_date->format('d/m/Y H:i') : '-' }}</li>
                <li class="list-group-item"><strong>Tgl Dibuat:</strong> {{ $surgery->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($surgery->description)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Deskripsi</div>
            <div class="card-body">
                <p class="mb-0">{{ $surgery->description }}</p>
            </div>
        </div>
        @endif

        @if($surgery->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $surgery->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
