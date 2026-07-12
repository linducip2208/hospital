@extends('layouts.admin')

@section('title', 'Laporan Keuangan Lanjutan')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header">
    <h1 class="h2"><i class="bi bi-graph-up-arrow"></i> Keuangan Lanjutan</h1>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Laporan Utama</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label small mb-1">Dari</label><input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}"></div>
            <div class="col-md-4"><label class="form-label small mb-1">Sampai</label><input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}"></div>
            <div class="col-md-4"><button class="btn btn-primary"><i class="bi bi-funnel"></i> Terapkan</button></div>
        </form>
    </div>
</div>

<div class="row g-3">
    {{-- Cost Center per Departemen --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold"><i class="bi bi-diagram-3"></i> Cost Center (Pendapatan per Unit)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Departemen</th><th class="text-end">Transaksi</th><th class="text-end">Pendapatan</th></tr></thead>
                        <tbody>
                            @forelse($costCenter as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td class="text-end">{{ number_format($c->tx) }}</td>
                                <td class="text-end">Rp {{ number_format($c->revenue,0,',','.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data. Kaitkan pembayaran ke departemen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Billing Breakdown per Payer --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold"><i class="bi bi-pie-chart"></i> Billing Breakdown (BPJS / Asuransi / Umum)</div>
            <div class="card-body">
                <canvas id="payerChart" height="150"></canvas>
                <table class="table table-sm mt-3 mb-0">
                    <thead><tr><th>Penjamin</th><th class="text-end">Transaksi</th><th class="text-end">Pendapatan</th></tr></thead>
                    <tbody>
                        @forelse($billingBreakdown as $b)
                        <tr>
                            <td><span class="badge bg-{{ $b->payer_type==='bpjs'?'primary':($b->payer_type==='asuransi'?'info':'secondary') }}">{{ ucfirst($b->payer_type) }}</span></td>
                            <td class="text-end">{{ number_format($b->tx) }}</td>
                            <td class="text-end">Rp {{ number_format($b->revenue,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Case-Mix INA-CBG --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold"><i class="bi bi-clipboard2-pulse"></i> Case-Mix / INA-CBG (Distribusi Klaim per Diagnosis)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Kode INA-CBG / ICD</th><th class="text-end">Jumlah Kasus</th><th class="text-end">Klaim Diajukan</th><th class="text-end">Klaim Disetujui</th><th class="text-end">% Disetujui</th></tr></thead>
                        <tbody>
                            @forelse($caseMix as $c)
                            <tr>
                                <td><code>{{ $c->grp ?: '(tanpa kode)' }}</code></td>
                                <td class="text-end">{{ number_format($c->cases) }}</td>
                                <td class="text-end">Rp {{ number_format($c->claimed,0,',','.') }}</td>
                                <td class="text-end">Rp {{ number_format($c->approved,0,',','.') }}</td>
                                <td class="text-end">{{ $c->claimed > 0 ? round($c->approved/$c->claimed*100) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data klaim pada periode ini</td></tr>
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
    const ctx = document.getElementById('payerChart');
    if (!ctx) return;
    const rows = @json($billingBreakdown);
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: rows.map(r => (r.payer_type || 'umum').toUpperCase()),
            datasets: [{ data: rows.map(r => Number(r.revenue || 0)), backgroundColor: ['#64748b','#2563eb','#06b6d4'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
})();
</script>
@endpush
