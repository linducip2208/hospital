@extends('layouts.admin')

@section('title', 'Dashboard Perawat')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Selamat datang, {{ $user->name }}</h1>
    <p class="text-muted mb-0">Dashboard keperawatan — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #f43f5e!important">
            <div class="card-body"><div class="text-muted small">IGD Aktif</div><div class="h3 fw-bold mb-0">{{ $igdActive }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #ef4444!important">
            <div class="card-body"><div class="text-muted small">Triase Merah</div><div class="h3 fw-bold mb-0 text-danger">{{ $triageRed }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #ef4444!important">
            <div class="card-body"><div class="text-muted small">Bed Terisi</div><div class="h3 fw-bold mb-0">{{ $bedOccupied }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #10b981!important">
            <div class="card-body"><div class="text-muted small">Bed Kosong</div><div class="h3 fw-bold mb-0 text-success">{{ $bedAvailable }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi Cepat Keperawatan</h6>
                <div class="row g-2">
                    <div class="col-6"><a href="{{ route('vital-signs.create') }}" class="btn btn-outline-primary w-100"><i class="bi bi-thermometer-half"></i> Input Tanda Vital</a></div>
                    <div class="col-6"><a href="{{ route('medication-administrations.create') }}" class="btn btn-outline-primary w-100"><i class="bi bi-capsule"></i> Pemberian Obat</a></div>
                    <div class="col-6"><a href="{{ route('nursing-cares.create') }}" class="btn btn-outline-primary w-100"><i class="bi bi-journal-medical"></i> Asuhan SOAP</a></div>
                    <div class="col-6"><a href="{{ route('shift-handovers.create') }}" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-left-right"></i> Serah Terima</a></div>
                </div>
                <div class="mt-3 p-3 rounded bg-light">
                    <div class="d-flex justify-content-between"><span>Pemberian obat hari ini</span><b>{{ $medsDueToday }}</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold"><i class="bi bi-person-badge"></i> Rekan Sedang Bertugas</div>
            <div class="card-body p-0" style="max-height:280px;overflow-y:auto">
                <table class="table table-hover mb-0">
                    <tbody>
                        @forelse($onDutyNow as $s)
                        <tr><td>{{ $s->user?->name ?? '-' }}</td><td class="text-muted small">{{ $s->department }}</td><td class="text-end small">{{ $s->start_time }}–{{ $s->end_time }}</td></tr>
                        @empty
                        <tr><td class="text-center text-muted py-4">Tidak ada staf terjadwal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
