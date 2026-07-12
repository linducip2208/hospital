@extends('layouts.admin')

@section('title', 'Detail Pemeriksaan Radiologi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pemeriksaan Radiologi</h1>
    <div>
        <a href="{{ route('radiologies.edit', $radiology) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('radiologies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-image"></i></div>
                <h5>{{ $radiology->examination_name }}</h5>
                @php
                    $statusColors = [
                        'requested' => 'secondary',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$radiology->status] ?? 'secondary' }}">
                    {{ str_replace('_', ' ', ucfirst($radiology->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $radiology->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $radiology->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Bagian Tubuh:</strong> {{ $radiology->body_part ?? '-' }}</li>
                <li class="list-group-item"><strong>Tanggal:</strong> {{ $radiology->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($radiology->findings)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Hasil / Temuan</div>
            <div class="card-body">
                <pre class="mb-0">{{ $radiology->findings }}</pre>
            </div>
        </div>
        @endif

        @if($radiology->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $radiology->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
