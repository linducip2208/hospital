@extends('layouts.admin')

@section('title', 'Detail Pemeriksaan Lab')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pemeriksaan Lab</h1>
    <div>
        <a href="{{ route('lab-tests.edit', $labTest) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-droplet"></i></div>
                <h5>{{ $labTest->test_name }}</h5>
                @php
                    $statusColors = [
                        'requested' => 'secondary',
                        'sample_collected' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$labTest->status] ?? 'secondary' }}">
                    {{ str_replace('_', ' ', ucfirst($labTest->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $labTest->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $labTest->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Tipe:</strong> {{ $labTest->test_type ?? '-' }}</li>
                <li class="list-group-item"><strong>Sampel:</strong> {{ $labTest->sample_type ?? '-' }}</li>
                <li class="list-group-item"><strong>Tanggal Hasil:</strong> {{ $labTest->result_date ? $labTest->result_date->format('d/m/Y H:i') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($labTest->results)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Hasil Pemeriksaan</div>
            <div class="card-body">
                <pre class="mb-0">{{ $labTest->results }}</pre>
            </div>
        </div>
        @endif

        @if($labTest->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $labTest->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
