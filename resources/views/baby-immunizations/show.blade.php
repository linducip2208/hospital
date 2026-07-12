@extends('layouts.admin')

@section('title', 'Detail Imunisasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Imunisasi</h1>
    <div>
        <a href="{{ route('baby-immunizations.edit', $babyImmunization) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('baby-immunizations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-capsule"></i></div>
                <h5>{{ $babyImmunization->vaccine_name }}</h5>
                <span class="badge bg-{{ $babyImmunization->status === 'completed' ? 'success' : 'warning' }}">
                    {{ $babyImmunization->status === 'completed' ? 'Selesai' : 'Pending' }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Bayi:</strong> {{ $babyImmunization->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dosis Ke-:</strong> {{ $babyImmunization->dose_number ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Jadwal:</strong> {{ $babyImmunization->scheduled_date ? $babyImmunization->scheduled_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Tgl Pemberian:</strong> {{ $babyImmunization->administered_date ? $babyImmunization->administered_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>No. Batch:</strong> {{ $babyImmunization->batch_number ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td width="200"><strong>Diberikan Oleh</strong></td><td>{{ $babyImmunization->administered_by ?? '-' }}</td></tr>
                    <tr><td><strong>Data Persalinan</strong></td><td>{{ $babyImmunization->maternity->patient->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if($babyImmunization->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $babyImmunization->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
