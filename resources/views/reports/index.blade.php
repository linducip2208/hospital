@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Laporan Keuangan &amp; Operasional</h1>
    <span class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Dari Tanggal</label>
                <input type="date" name="from" class="form-control" value="{{ ($from ?? now()->startOfMonth())->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Sampai Tanggal</label>
                <input type="date" name="to" class="form-control" value="{{ ($to ?? now())->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Kelompok</label>
                <select name="group_by" class="form-select">
                    <option value="day" @selected(($groupBy ?? 'month') === 'day')>Harian</option>
                    <option value="month" @selected(($groupBy ?? 'month') === 'month')>Bulanan</option>
                    <option value="year" @selected(($groupBy ?? 'month') === 'year')>Tahunan</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> Terapkan</button>
                <a href="{{ route('reports.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
                <a href="{{ route('reports.export-csv', request()->query()) }}" class="btn btn-outline-success"><i class="bi bi-file-earmark-spreadsheet"></i> Excel/CSV</a>
            </div>
        </form>
    </div>
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
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">Grafik Pendapatan</div>
            <div class="card-body">
                <canvas id="revenueChart" height="90"></canvas>
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
            <div class="card-header">Pendapatan per {{ ['day' => 'Hari', 'month' => 'Bulan', 'year' => 'Tahun'][$groupBy ?? 'month'] }}</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Periode</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($revenue ?? [] as $rev)
                            <tr>
                                <td>
                                    @if(($groupBy ?? 'month') === 'day')
                                        {{ \Carbon\Carbon::parse($rev->period)->translatedFormat('d M Y') }}
                                    @elseif(($groupBy ?? 'month') === 'year')
                                        {{ $rev->year }}
                                    @else
                                        {{ \Carbon\Carbon::createFromDate($rev->year, $rev->month, 1)->translatedFormat('F Y') }}
                                    @endif
                                </td>
                                <td>Rp {{ number_format($rev->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data pendapatan</td></tr>
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
                                <td>{{ $payment->patient?->name ?? $payment->appointment?->patient?->name ?? '-' }}</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        const gb = @json($groupBy ?? 'month');
        const rows = @json($revenue ?? []);
        const labels = rows.map(r => {
            if (gb === 'day') return r.period;
            if (gb === 'year') return String(r.year);
            const m = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            return (m[(r.month || 1) - 1] || '') + ' ' + r.year;
        });
        const data = rows.map(r => Number(r.total || 0));
        new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Pendapatan (Rp)', data, backgroundColor: 'rgba(37,99,235,.6)', borderColor: '#2563eb', borderWidth: 1, borderRadius: 6 }] },
            options: { responsive: true, plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') } } } }
        });
    })();
</script>
@endpush
