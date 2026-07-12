@extends('layouts.admin')

@section('title', 'Detail Postnatal Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Postnatal Record</h1>
    <div>
        <a href="{{ route('postnatal-records.edit', $postnatalRecord) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('postnatal-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-heart-pulse"></i></div>
                <h5>{{ $postnatalRecord->patient->name ?? '-' }}</h5>
                <p class="text-muted">{{ $postnatalRecord->visit_date ? $postnatalRecord->visit_date->format('d/m/Y') : '-' }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Bidan:</strong> {{ $postnatalRecord->midwife->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Involusi Uterus:</strong> {{ $postnatalRecord->uterine_involution ? ucfirst($postnatalRecord->uterine_involution) : '-' }}</li>
                <li class="list-group-item"><strong>Lochia:</strong> {{ $postnatalRecord->lochia ? ucfirst($postnatalRecord->lochia) : '-' }}</li>
                <li class="list-group-item"><strong>Luka Perineum:</strong> {{ $postnatalRecord->perineum_wound ? ucfirst($postnatalRecord->perineum_wound) : '-' }}</li>
                <li class="list-group-item"><strong>Kunjungan Berikutnya:</strong> {{ $postnatalRecord->next_visit_date ? $postnatalRecord->next_visit_date->format('d/m/Y') : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Kondisi Bayi & Ibu</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td width="200"><strong>Menyusui</strong></td><td>{{ $postnatalRecord->breastfeeding ? ucfirst($postnatalRecord->breastfeeding) : '-' }}</td></tr>
                    <tr><td><strong>Berat Bayi</strong></td><td>{{ $postnatalRecord->baby_weight ? $postnatalRecord->baby_weight . ' kg' : '-' }}</td></tr>
                    <tr><td><strong>Kondisi Bayi</strong></td><td>{{ $postnatalRecord->baby_condition ?? '-' }}</td></tr>
                    <tr><td><strong>Keluarga Berencana</strong></td><td>{{ $postnatalRecord->family_planning ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if($postnatalRecord->complications)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Komplikasi</div>
            <div class="card-body">
                <p class="mb-0">{{ $postnatalRecord->complications }}</p>
            </div>
        </div>
        @endif

        @if($postnatalRecord->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $postnatalRecord->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
