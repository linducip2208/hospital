@extends('layouts.admin')

@section('title', 'Detail Pemberian Obat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pemberian Obat</h1>
    <div>
        <a href="{{ route('medication-administrations.edit', $medicationAdministration) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('medication-administrations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <div class="display-1 text-info mb-2">
                    <i class="bi bi-capsule"></i>
                </div>
                <h5>{{ $medicationAdministration->drug_name ?? '-' }}</h5>
                <p class="text-muted mb-1">{{ $medicationAdministration->dosage ?? '-' }}</p>
                <span class="badge bg-info">{{ $medicationAdministration->route ?? '-' }}</span>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">Informasi</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $medicationAdministration->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Perawat:</strong> {{ $medicationAdministration->nurse->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Waktu Pemberian:</strong> {{ $medicationAdministration->administered_at ? $medicationAdministration->administered_at->format('d/m/Y H:i') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">Detail Obat</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th class="table-light" width="200">Obat (Drug ID)</th><td>{{ $medicationAdministration->drug_id ? $medicationAdministration->drug->name ?? $medicationAdministration->drug_id : '-' }}</td></tr>
                    <tr><th class="table-light">Nama Obat</th><td>{{ $medicationAdministration->drug_name ?? '-' }}</td></tr>
                    <tr><th class="table-light">Dosis</th><td>{{ $medicationAdministration->dosage ?? '-' }}</td></tr>
                    <tr><th class="table-light">Rute Pemberian</th><td><span class="badge bg-info">{{ $medicationAdministration->route ?? '-' }}</span></td></tr>
                    <tr><th class="table-light">Waktu Pemberian</th><td>{{ $medicationAdministration->administered_at ? $medicationAdministration->administered_at->format('d/m/Y H:i') : '-' }}</td></tr>
                    @if($medicationAdministration->notes)
                    <tr><th class="table-light">Catatan</th><td>{{ $medicationAdministration->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
