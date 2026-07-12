@extends('layouts.admin')

@section('title', 'Detail ANC Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail ANC Record</h1>
    <div>
        <a href="{{ route('anc-records.edit', $ancRecord) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('anc-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-clipboard2-heart"></i></div>
                <h5>{{ $ancRecord->patient->name ?? '-' }}</h5>
                <p class="text-muted">{{ $ancRecord->visit_date ? $ancRecord->visit_date->format('d/m/Y') : '-' }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Bidan:</strong> {{ $ancRecord->midwife->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Usia Kehamilan:</strong> {{ $ancRecord->gestational_age ? $ancRecord->gestational_age . ' minggu' : '-' }}</li>
                <li class="list-group-item"><strong>Tinggi Fundus:</strong> {{ $ancRecord->fundal_height ? $ancRecord->fundal_height . ' cm' : '-' }}</li>
                <li class="list-group-item"><strong>Presentasi Janin:</strong> {{ $ancRecord->fetal_presentation ? ucfirst($ancRecord->fetal_presentation) : '-' }}</li>
                <li class="list-group-item"><strong>Kunjungan Berikutnya:</strong> {{ $ancRecord->next_visit_date ? $ancRecord->next_visit_date->format('d/m/Y') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Pemeriksaan</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td width="200"><strong>Detak Jantung Janin</strong></td><td>{{ $ancRecord->fetal_heart_rate ? $ancRecord->fetal_heart_rate . ' bpm' : '-' }}</td></tr>
                    <tr><td><strong>Tekanan Darah</strong></td><td>{{ $ancRecord->blood_pressure_systolic ? $ancRecord->blood_pressure_systolic . '/' . $ancRecord->blood_pressure_diastolic . ' mmHg' : '-' }}</td></tr>
                    <tr><td><strong>Berat Badan</strong></td><td>{{ $ancRecord->weight ? $ancRecord->weight . ' kg' : '-' }}</td></tr>
                    <tr><td><strong>Hemoglobin</strong></td><td>{{ $ancRecord->hemoglobin ? $ancRecord->hemoglobin . ' g/dL' : '-' }}</td></tr>
                    <tr><td><strong>Protein Urine</strong></td><td>{{ $ancRecord->urine_protein ?? '-' }}</td></tr>
                    <tr><td><strong>Imunisasi TT</strong></td><td>{{ $ancRecord->tt_immunization ?? '-' }}</td></tr>
                    <tr><td><strong>Zat Besi & Asam Folat</strong></td><td>{{ $ancRecord->iron_folate ? 'Ya' : 'Tidak' }}</td></tr>
                </table>
            </div>
        </div>

        @if($ancRecord->complications)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Komplikasi</div>
            <div class="card-body">
                <p class="mb-0">{{ $ancRecord->complications }}</p>
            </div>
        </div>
        @endif

        @if($ancRecord->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $ancRecord->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
