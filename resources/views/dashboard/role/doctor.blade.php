@extends('layouts.admin')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Selamat datang, dr. {{ $user->name }}</h1>
    <p class="text-muted mb-0">Dashboard klinis Anda hari ini — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #2563eb!important">
            <div class="card-body"><div class="text-muted small">Appointment Hari Ini</div><div class="h3 fw-bold mb-0">{{ $myAppointmentsToday->count() }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #f59e0b!important">
            <div class="card-body"><div class="text-muted small">Antrian Menunggu</div><div class="h3 fw-bold mb-0">{{ $myQueueWaiting }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #10b981!important">
            <div class="card-body"><div class="text-muted small">Pasien Bulan Ini</div><div class="h3 fw-bold mb-0">{{ $myPatientsMonth }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #06b6d4!important">
            <div class="card-body"><div class="text-muted small">Hasil Lab Pending</div><div class="h3 fw-bold mb-0">{{ $myLabPending }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold"><i class="bi bi-calendar-check"></i> Jadwal Appointment Hari Ini</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Jam</th><th>Pasien</th><th>Poli</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse($myAppointmentsToday as $a)
                            <tr>
                                <td>{{ $a->start_time }}</td>
                                <td>{{ $a->patient?->name ?? '-' }}</td>
                                <td>{{ $a->polyclinic?->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ in_array($a->status,['completed','confirmed'])?'success':'warning' }}">{{ $a->status }}</span></td>
                                <td><a href="{{ route('appointments.show', $a) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada appointment hari ini</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-signpost-split text-primary"></i> Rujukan Pending</h6>
                <div class="display-6 fw-bold">{{ $myReferrals }}</div>
                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-2">Kelola Rujukan</a>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                <a href="{{ route('medical-records.create') }}" class="btn btn-primary w-100 mb-2"><i class="bi bi-file-earmark-plus"></i> Rekam Medis Baru</a>
                <a href="{{ route('prescriptions.create') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-prescription2"></i> Tulis Resep</a>
                <a href="{{ route('lab-tests.create') }}" class="btn btn-outline-primary w-100"><i class="bi bi-eyedropper"></i> Order Lab</a>
            </div>
        </div>
    </div>
</div>
@endsection
