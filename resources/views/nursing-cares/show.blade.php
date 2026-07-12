@extends('layouts.admin')

@section('title', 'Detail Asuhan Keperawatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Asuhan Keperawatan</h1>
    <div>
        <a href="{{ route('nursing-cares.edit', $nursingCare) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('nursing-cares.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Pasien</h6>
                <h5>{{ $nursingCare->patient->name ?? '-' }}</h5>
                <hr>
                <h6 class="text-muted text-uppercase small">Perawat</h6>
                <p class="mb-0">{{ $nursingCare->nurse->name ?? '-' }}</p>
                <hr>
                <h6 class="text-muted text-uppercase small">Tanggal Perawatan</h6>
                <p class="mb-0">{{ $nursingCare->care_date ? $nursingCare->care_date->format('d/m/Y H:i') : '-' }}</p>
                <hr>
                <h6 class="text-muted text-uppercase small">Status</h6>
                <span class="badge bg-{{ $nursingCare->status === 'completed' ? 'success' : ($nursingCare->status === 'ongoing' ? 'warning' : 'secondary') }}">
                    {{ $nursingCare->status === 'completed' ? 'Selesai' : ($nursingCare->status === 'ongoing' ? 'Berjalan' : 'Dibatalkan') }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white"><strong>S - Subjective</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->subjective ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white"><strong>O - Objective</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->objective ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark"><strong>A - Assessment</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->assessment ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white"><strong>P - Plan</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->plan ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-danger text-white"><strong>I - Implementation</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->implementation ?: '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-secondary text-white"><strong>E - Evaluation</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->evaluation ?: '-' }}</p>
            </div>
        </div>
        @if($nursingCare->notes)
        <div class="card shadow-sm">
            <div class="card-header"><strong>Catatan</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $nursingCare->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
