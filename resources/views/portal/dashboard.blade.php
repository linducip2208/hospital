@extends('portal.layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 fw-bold mb-1">Halo, {{ $patient->name }} 👋</h1>
<p class="text-muted mb-4">Selamat datang di portal pasien. Berikut ringkasan aktivitas Anda.</p>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="text-muted small">Total Janji Temu</div><div class="val text-primary">{{ number_format($stats['appointments']) }}</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="text-muted small">Janji Mendatang</div><div class="val text-success">{{ number_format($stats['upcoming']) }}</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="text-muted small">Rekam Medis</div><div class="val">{{ number_format($stats['records']) }}</div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card"><div class="text-muted small">Tagihan Belum Lunas</div><div class="val text-danger">Rp {{ number_format($stats['unpaid'], 0, ',', '.') }}</div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-soft p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check text-primary"></i> Janji Temu Mendatang</h5>
            @forelse($upcomingAppointments as $appt)
                <a href="{{ route('portal.appointments.show', $appt) }}" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark">
                    <div>
                        <div class="fw-semibold">dr. {{ $appt->doctor?->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $appt->polyclinic?->name ?? '-' }}</div>
                    </div>
                    <div class="text-end">
                        <div class="small fw-semibold">{{ $appt->appointment_date?->format('d M Y') }}</div>
                        <div class="small text-muted">{{ $appt->start_time }}</div>
                    </div>
                </a>
            @empty
                <p class="text-muted mb-0">Tidak ada janji temu mendatang.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-soft p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-primary"></i> Tagihan Terbaru</h5>
            @forelse($recentPayments as $pay)
                <a href="{{ route('portal.invoices.show', $pay) }}" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark">
                    <div>
                        <div class="fw-semibold small">{{ $pay->invoice_number }}</div>
                        <div class="small text-muted">{{ $pay->created_at?->format('d M Y') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">Rp {{ number_format($pay->amount, 0, ',', '.') }}</div>
                        <span class="badge bg-{{ $pay->status === 'completed' ? 'success' : ($pay->status === 'pending' ? 'warning' : 'secondary') }}">{{ $pay->status }}</span>
                    </div>
                </a>
            @empty
                <p class="text-muted mb-0">Belum ada tagihan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
