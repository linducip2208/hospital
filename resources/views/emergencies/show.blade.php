@extends('layouts.admin')

@section('title', 'Detail Pasien IGD')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pasien IGD</h1>
    <div>
        <a href="{{ route('emergencies.edit', $emergency) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('emergencies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @php
                    $triageColors = [
                        'red' => 'danger',
                        'yellow' => 'warning',
                        'green' => 'success',
                        'black' => 'dark',
                    ];
                    $triageLabels = [
                        'red' => 'CRITICAL',
                        'yellow' => 'URGENT',
                        'green' => 'NON-URGENT',
                        'black' => 'DECEASED',
                    ];
                @endphp
                <div class="display-1 mb-2 text-{{ $triageColors[$emergency->triage] ?? 'secondary' }}">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5>
                    <span class="badge bg-{{ $triageColors[$emergency->triage] ?? 'secondary' }} fs-6">
                        {{ $triageLabels[$emergency->triage] ?? ucfirst($emergency->triage) }}
                    </span>
                </h5>
                @php
                    $statusColors = [
                        'waiting' => 'secondary',
                        'in_treatment' => 'warning',
                        'completed' => 'success',
                        'referred' => 'info',
                        'deceased' => 'dark',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$emergency->status] ?? 'secondary' }}">
                    {{ str_replace('_', ' ', ucfirst($emergency->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $emergency->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $emergency->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Cara Datang:</strong> {{ $emergency->arrival_mode ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Masuk:</strong> {{ $emergency->created_at->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Tgl Pulang:</strong> {{ $emergency->discharge_date ? $emergency->discharge_date->format('d/m/Y H:i') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Keluhan</div>
            <div class="card-body">
                <p class="mb-0">{{ $emergency->complaint }}</p>
            </div>
        </div>

        @if($emergency->diagnosis)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Diagnosis</div>
            <div class="card-body">
                <p class="mb-0">{{ $emergency->diagnosis }}</p>
            </div>
        </div>
        @endif

        @if($emergency->action_taken)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Tindakan</div>
            <div class="card-body">
                <p class="mb-0">{{ $emergency->action_taken }}</p>
            </div>
        </div>
        @endif

        @if($emergency->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $emergency->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
