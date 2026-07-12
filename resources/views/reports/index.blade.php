@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Laporan</h1>
    <span class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #0d6efd;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Total Pasien</h6>
                        <h2 class="mb-0">{{ number_format($stats['total_patients'] ?? 0) }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 text-primary opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #198754;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Total Dokter</h6>
                        <h2 class="mb-0">{{ $stats['total_doctors'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-person-badge fs-1 text-success opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Appointment</h6>
                        <h2 class="mb-0">{{ $stats['total_appointments'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-calendar-check fs-1 text-warning opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #6f42c1;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Pembayaran</h6>
                        <h2 class="mb-0">{{ $stats['total_payments'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-cash-stack fs-1 text-purple opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #fd7e14;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Total Obat</h6>
                        <h2 class="mb-0">{{ $stats['total_drugs'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-capsule-pill fs-1 text-warning opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-dashboard shadow-sm" style="border-left-color: #20c997;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle text-muted">Total Kamar</h6>
                        <h2 class="mb-0">{{ $stats['total_rooms'] ?? 0 }}</h2>
                    </div>
                    <i class="bi bi-building fs-1 text-info opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">Appointment by Status</div>
            <div class="card-body">
                @forelse($appointmentStatus ?? [] as $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ ucfirst($item->status) }}</span>
                    <span class="badge bg-{{ $item->status === 'completed' ? 'success' : ($item->status === 'cancelled' ? 'danger' : ($item->status === 'confirmed' ? 'primary' : 'warning')) }}">{{ $item->total }}</span>
                </div>
                @empty
                <p class="text-muted text-center py-3">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">Pendapatan per Bulan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Bulan</th><th>Tahun</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($revenue ?? [] as $rev)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromDate(null, $rev->month, 1)->translatedFormat('F') }}</td>
                                <td>{{ $rev->year }}</td>
                                <td>Rp {{ number_format($rev->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data pendapatan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">Top 5 Treatment</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Treatment</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topTreatments ?? [] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->total ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">10 Pembayaran Terbaru</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Tanggal</th><th>Pasien</th><th>Jumlah</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td>{{ $payment->appointment?->patient?->name ?? '-' }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pembayaran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
