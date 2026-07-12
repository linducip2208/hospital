@extends('layouts.admin')

@section('title', 'Detail Tanda Vital')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Tanda Vital</h1>
    <div>
        <a href="{{ route('vital-signs.edit', $vitalSignsRecord) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('vital-signs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-primary border-3">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Pasien</h6>
                <h5>{{ $vitalSignsRecord->patient->name ?? '-' }}</h5>
                <hr>
                <h6 class="text-muted text-uppercase small">Perawat</h6>
                <p class="mb-0">{{ $vitalSignsRecord->nurse->name ?? '-' }}</p>
                <hr>
                <h6 class="text-muted text-uppercase small">Waktu Pencatatan</h6>
                <p class="mb-0">{{ $vitalSignsRecord->recorded_at ? $vitalSignsRecord->recorded_at->format('d/m/Y H:i') : '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light"><strong>Tanda Vital</strong></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->temperature ?? '-' }}°C</h3>
                            <small class="text-muted">Suhu Tubuh</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->blood_pressure_systolic ?? '-' }}/{{ $vitalSignsRecord->blood_pressure_diastolic ?? '-' }}</h3>
                            <small class="text-muted">Tekanan Darah (mmHg)</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->heart_rate ?? '-' }} <small class="text-muted fs-6">bpm</small></h3>
                            <small class="text-muted">Nadi</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->respiratory_rate ?? '-' }} <small class="text-muted fs-6">x/mnt</small></h3>
                            <small class="text-muted">Respirasi</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->oxygen_saturation ?? '-' }}%</h3>
                            <small class="text-muted">SpO₂</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->blood_sugar ?? '-' }} <small class="text-muted fs-6">mg/dL</small></h3>
                            <small class="text-muted">Gula Darah</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->weight ?? '-' }} <small class="text-muted fs-6">kg</small></h3>
                            <small class="text-muted">Berat Badan</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-primary">{{ $vitalSignsRecord->height ?? '-' }} <small class="text-muted fs-6">cm</small></h3>
                            <small class="text-muted">Tinggi Badan</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-3 text-center">
                            <h3 class="mb-0 text-{{ ($vitalSignsRecord->pain_level ?? 0) >= 7 ? 'danger' : (($vitalSignsRecord->pain_level ?? 0) >= 4 ? 'warning' : 'success') }}">{{ $vitalSignsRecord->pain_level ?? '-' }}/10</h3>
                            <small class="text-muted">Skala Nyeri</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($vitalSignsRecord->notes)
        <div class="card shadow-sm">
            <div class="card-header bg-light"><strong>Catatan</strong></div>
            <div class="card-body">
                <p class="mb-0">{{ $vitalSignsRecord->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
