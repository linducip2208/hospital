@extends('layouts.admin')

@section('title', 'Detail Persalinan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Persalinan</h1>
    <div>
        <a href="{{ route('maternities.edit', $maternity) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('maternities.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-heart-pulse"></i></div>
                <h5>{{ $maternity->patient->name ?? '-' }}</h5>
                @php
                    $statusColors = [
                        'admitted' => 'info',
                        'in_labor' => 'warning',
                        'delivered' => 'success',
                        'postpartum' => 'primary',
                        'discharged' => 'secondary',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$maternity->status] ?? 'secondary' }}">
                    {{ str_replace('_', ' ', ucfirst($maternity->status)) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Dokter:</strong> {{ $maternity->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Masuk:</strong> {{ $maternity->admission_date ? $maternity->admission_date->format('d/m/Y H:i') : '-' }}</li>
                <li class="list-group-item"><strong>Tgl Lahir:</strong> {{ $maternity->delivery_date ? $maternity->delivery_date->format('d/m/Y H:i') : '-' }}</li>
                <li class="list-group-item"><strong>Metode:</strong> {{ ucfirst($maternity->delivery_type ?? '-') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($maternity->status === 'delivered' || $maternity->baby_gender || $maternity->baby_weight)
        <div class="card shadow-sm mb-3">
            <div class="card-header"><i class="bi bi-person-heart me-2"></i>Data Bayi</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td width="180"><strong>Jenis Kelamin</strong></td><td>{{ $maternity->baby_gender ?? '-' }}</td></tr>
                    <tr><td><strong>Nama</strong></td><td>{{ $maternity->baby_name ?? '-' }}</td></tr>
                    <tr><td><strong>Berat</strong></td><td>{{ $maternity->baby_weight ? $maternity->baby_weight . ' gram' : '-' }}</td></tr>
                    <tr><td><strong>Panjang</strong></td><td>{{ $maternity->baby_length ? $maternity->baby_length . ' cm' : '-' }}</td></tr>
                </table>
            </div>
        </div>
        @endif

        @if($maternity->complications)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Komplikasi</div>
            <div class="card-body">
                <p class="mb-0">{{ $maternity->complications }}</p>
            </div>
        </div>
        @endif

        @if($maternity->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $maternity->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
