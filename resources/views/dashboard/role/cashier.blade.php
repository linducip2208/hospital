@extends('layouts.admin')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Selamat datang, {{ $user->name }}</h1>
    <p class="text-muted mb-0">Dashboard kasir & pembayaran — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #10b981!important">
            <div class="card-body"><div class="text-muted small">Pendapatan Hari Ini</div><div class="h4 fw-bold mb-0">Rp {{ number_format($paymentsToday,0,',','.') }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #2563eb!important">
            <div class="card-body"><div class="text-muted small">Transaksi Hari Ini</div><div class="h3 fw-bold mb-0">{{ $txCountToday }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #f59e0b!important">
            <div class="card-body"><div class="text-muted small">Pembayaran Pending</div><div class="h3 fw-bold mb-0">{{ $pendingPayments }}</div></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #f43f5e!important">
            <div class="card-body"><div class="text-muted small">Nilai Pending</div><div class="h4 fw-bold mb-0">Rp {{ number_format($pendingAmount,0,',','.') }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold d-flex justify-content-between">
                <span><i class="bi bi-receipt"></i> Transaksi Terbaru</span>
                <a href="{{ route('payments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Transaksi Baru</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Invoice</th><th>Pasien</th><th>Jumlah</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentPayments as $p)
                            <tr>
                                <td class="small fw-semibold">{{ $p->invoice_number }}</td>
                                <td>{{ $p->patient?->name ?? '-' }}</td>
                                <td>Rp {{ number_format($p->amount,0,',','.') }}</td>
                                <td><span class="badge bg-{{ $p->status==='completed'?'success':($p->status==='pending'?'warning':'secondary') }}">{{ $p->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                <a href="{{ route('payments.create') }}" class="btn btn-primary w-100 mb-2"><i class="bi bi-cash-coin"></i> Buat Pembayaran</a>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-list-ul"></i> Semua Transaksi</a>
                <a href="{{ route('cost-estimates.index') }}" class="btn btn-outline-primary w-100"><i class="bi bi-calculator"></i> Estimasi Biaya</a>
            </div>
        </div>
    </div>
</div>
@endsection
