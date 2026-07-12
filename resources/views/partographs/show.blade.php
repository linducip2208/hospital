@extends('layouts.admin')

@section('title', 'Detail Partograph')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Partograph</h1>
    <div>
        <a href="{{ route('partographs.edit', $partograph) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('partographs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-activity"></i></div>
                <h5>{{ $partograph->maternity->patient->name ?? '-' }}</h5>
                <p class="text-muted">{{ $partograph->recorded_at ? $partograph->recorded_at->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pembukaan:</strong> {{ $partograph->cervical_dilation ? $partograph->cervical_dilation . ' cm' : '-' }}</li>
                <li class="list-group-item"><strong>DJJ:</strong> {{ $partograph->fetal_heart_rate ? $partograph->fetal_heart_rate . ' bpm' : '-' }}</li>
                <li class="list-group-item"><strong>Kontraksi:</strong> {{ $partograph->contractions_per_10min ? $partograph->contractions_per_10min . '/10 menit' : '-' }}</li>
                <li class="list-group-item"><strong>Cairan Amnion:</strong> {{ $partograph->amniotic_fluid ? ucfirst($partograph->amniotic_fluid) : '-' }}</li>
                <li class="list-group-item"><strong>Moulding:</strong> {{ $partograph->moulding ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Tanda Vital Ibu</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td width="200"><strong>Tekanan Darah</strong></td><td>{{ $partograph->blood_pressure_systolic ? $partograph->blood_pressure_systolic . '/' . $partograph->blood_pressure_diastolic . ' mmHg' : '-' }}</td></tr>
                    <tr><td><strong>Nadi</strong></td><td>{{ $partograph->pulse ? $partograph->pulse . ' bpm' : '-' }}</td></tr>
                    <tr><td><strong>Suhu</strong></td><td>{{ $partograph->temperature ? $partograph->temperature . ' °C' : '-' }}</td></tr>
                    <tr><td><strong>Output Urine</strong></td><td>{{ $partograph->urine_output ? $partograph->urine_output . ' ml' : '-' }}</td></tr>
                    <tr><td><strong>Oksitosin</strong></td><td>{{ $partograph->oxytocin ? $partograph->oxytocin . ' tetes/menit' : '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if($partograph->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $partograph->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
