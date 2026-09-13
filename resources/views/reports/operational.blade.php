@extends('layouts.admin')

@section('title', 'Laporan Operasional')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header gap-2">
    <h1 class="h2"><i class="bi bi-heart-pulse"></i> Laporan Operasional</h1>
    <div class="d-flex gap-2"><a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-bar-chart"></i> Bisnis</a><a href="{{ route('reports.operational.pdf', request()->query()) }}" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a></div>
</div>

<div class="card shadow-sm mb-4"><div class="card-body"><form method="GET" class="row g-2 align-items-end">
    <div class="col-sm-4"><label class="form-label small mb-1" for="from">Dari</label><input id="from" type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}"></div>
    <div class="col-sm-4"><label class="form-label small mb-1" for="to">Sampai</label><input id="to" type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}"></div>
    <div class="col-sm-4 d-flex gap-2"><button class="btn btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Terapkan</button><a class="btn btn-outline-secondary" href="{{ route('reports.operational') }}"><i class="bi bi-arrow-counterclockwise"></i></a></div>
</form></div></div>

<div class="row g-3 mb-4">
    @foreach([
        ['icon' => 'calendar2-check', 'label' => 'Appointment', 'value' => $stats['appointments'], 'tone' => 'primary'],
        ['icon' => 'check2-circle', 'label' => 'Appointment selesai', 'value' => $stats['completed_appointments'], 'tone' => 'success'],
        ['icon' => 'lightning-charge', 'label' => 'Kasus IGD', 'value' => $stats['emergencies'], 'tone' => 'danger'],
        ['icon' => 'clipboard-pulse', 'label' => 'Lab menunggu', 'value' => $stats['pending_lab_tests'], 'tone' => 'warning'],
        ['icon' => 'hospital', 'label' => 'Bed terisi', 'value' => $stats['occupied_beds'], 'tone' => 'info'],
        ['icon' => 'capsule', 'label' => 'Obat stok rendah', 'value' => $stats['low_stock_drugs'], 'tone' => 'secondary'],
    ] as $card)
    <div class="col-6 col-xl-2"><div class="card shadow-sm h-100"><div class="card-body"><div class="text-{{ $card['tone'] }} mb-2"><i class="bi bi-{{ $card['icon'] }} fs-4"></i></div><div class="fs-3 fw-bold">{{ number_format($card['value']) }}</div><div class="small text-muted">{{ $card['label'] }}</div></div></div></div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    @foreach([['id' => 'appointmentChart', 'title' => 'Status Appointment', 'rows' => $appointmentStatus], ['id' => 'emergencyChart', 'title' => 'Status IGD', 'rows' => $emergencyStatus], ['id' => 'labChart', 'title' => 'Status Laboratorium', 'rows' => $labStatus]] as $chart)
    <div class="col-lg-4"><div class="card shadow-sm h-100"><div class="card-header fw-semibold"><i class="bi bi-pie-chart"></i> {{ $chart['title'] }}</div><div class="card-body"><canvas id="{{ $chart['id'] }}" height="190"></canvas><div class="small text-muted mt-2">Klik filter periode untuk memperbarui indikator.</div></div></div></div>
    @endforeach
</div>

<div class="card shadow-sm"><div class="card-header fw-semibold"><i class="bi bi-list-check"></i> Appointment Terbaru pada Periode</div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>Tanggal</th><th>Pasien</th><th>Dokter</th><th>Status</th></tr></thead><tbody>
    @forelse($recentAppointments as $appointment)
    <tr><td>{{ optional($appointment->appointment_date)->format('d M Y') }}</td><td>{{ $appointment->patient->name ?? '-' }}</td><td>{{ $appointment->doctor->name ?? '-' }}</td><td><span class="badge text-bg-light">{{ str_replace('_', ' ', ucfirst($appointment->status)) }}</span></td></tr>
    @empty <tr><td colspan="4" class="text-center text-muted py-4">Belum ada appointment pada periode ini.</td></tr>@endforelse
</tbody></table></div></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(() => {
    const palette = ['#2563eb', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#6366f1'];
    const charts = [
        ['appointmentChart', @json($appointmentStatus)],
        ['emergencyChart', @json($emergencyStatus)],
        ['labChart', @json($labStatus)],
    ];
    charts.forEach(([id, rows]) => {
        const el = document.getElementById(id); if (!el) return;
        new Chart(el, { type: 'doughnut', data: { labels: rows.map(r => String(r.status || 'lainnya').replaceAll('_', ' ')), datasets: [{ data: rows.map(r => Number(r.total)), backgroundColor: palette }] }, options: { responsive: true, plugins: { legend: { position: 'bottom' } } } });
    });
})();
</script>
@endpush
