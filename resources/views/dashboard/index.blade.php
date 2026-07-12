@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .gradient-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .gradient-teal { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .gradient-emerald { background: linear-gradient(135deg, #10b981, #059669); }
    .gradient-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .gradient-rose { background: linear-gradient(135deg, #f43f5e, #e11d48); }
    .gradient-purple { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .gradient-cyan { background: linear-gradient(135deg, #06b6d4, #6366f1); }
    .gradient-slate { background: linear-gradient(135deg, #64748b, #475569); }

    .greeting { font-size: 1.45rem; font-weight: 800; letter-spacing: -0.01em; }
    .greeting-sub { font-size: 0.82rem; color: var(--text-muted); font-weight: 500; }

    .chart-card { padding: 1rem 0.5rem 0.5rem; }

    .kpi-icon-box {
        width: 44px; height: 44px; min-width: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #fff;
    }

    .live-queue-item {
        display: flex; align-items: center; gap: 12px;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--border);
        transition: all var(--transition);
    }
    .live-queue-item:last-child { border-bottom: none; }
    .live-queue-item:hover { background: rgba(37,99,235,0.02); }
    .live-queue-item .queue-num {
        width: 32px; height: 32px; min-width: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem; font-weight: 700; color: #fff;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .medical-wave {
        width: 100%; height: 48px;
        background: linear-gradient(90deg, transparent, rgba(6,182,212,0.08), transparent);
        border-radius: 24px;
        position: relative; overflow: hidden;
        margin: 0.5rem 0;
    }
    .medical-wave::after {
        content: '';
        position: absolute; inset: 0;
        background: repeating-linear-gradient(90deg, transparent, transparent 3px, rgba(6,182,212,0.15) 3px, rgba(6,182,212,0.15) 5px);
        mask: radial-gradient(ellipse at center, black 10%, transparent 70%);
        -webkit-mask: radial-gradient(ellipse at center, black 10%, transparent 70%);
        animation: waveSlide 8s linear infinite;
    }
    @keyframes waveSlide {
        from { transform: translateX(-100%); }
        to { transform: translateX(100%); }
    }

    .glow-ring {
        position: absolute; inset: -3px;
        border-radius: inherit;
        background: linear-gradient(135deg, rgba(37,99,235,0.3), rgba(6,182,212,0.3), rgba(37,99,235,0.3));
        opacity: 0; transition: opacity 0.3s ease;
        z-index: -1;
    }
    .card-premium:hover .glow-ring { opacity: 1; }

    .pulse-icon {
        animation: iconPulse 2s ease-in-out infinite;
    }
    @keyframes iconPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .stat-bar {
        height: 6px; border-radius: 10px; background: var(--border);
        overflow: hidden; margin-top: 6px;
    }
    .stat-bar-fill {
        height: 100%; border-radius: 10px;
        background: linear-gradient(90deg, #2563eb, #06b6d4);
        transition: width 1.5s ease-out;
    }
</style>
@endpush

@section('content')
{{-- Greeting + Date --}}
<div class="d-flex flex-wrap justify-content-between align-items-start mb-4 fade-up">
    <div>
        <div class="greeting">Selamat Datang, {{ explode(' ', Auth::user()?->name ?? 'Admin')[0] }} 👋</div>
        <div class="greeting-sub">Berikut ringkasan operasional rumah sakit hari ini — {{ now()->translatedFormat('l, d F Y') }}</div>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="{{ route('patients.create') }}" class="quick-action secondary">
            <i class="bi bi-person-plus"></i> <span>Tambah Pasien</span>
        </a>
        <a href="{{ route('appointments.create') }}" class="quick-action">
            <i class="bi bi-calendar-plus"></i> <span>Appointment</span>
        </a>
    </div>
</div>

{{-- Alert Banners --}}
@if($stats['critical_drugs'] > 0)
<div class="alert-banner warn fade-up">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><strong>{{ $stats['critical_drugs'] }}</strong> item stok obat di bawah threshold. <a href="{{ route('drugs.index') }}" style="color:inherit;font-weight:700;">Cek Farmasi →</a></span>
</div>
@endif
@if($occupancyPct >= 80)
<div class="alert-banner danger fade-up">
    <i class="bi bi-building-exclamation"></i>
    <span>Okupansi rawat inap <strong>{{ $occupancyPct }}%</strong> — hampir penuh. <a href="{{ route('rooms.index') }}" style="color:inherit;font-weight:700;">Kelola Kamar →</a></span>
</div>
@endif

{{-- KPI Cards Row 1 --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-blue pulse-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value" data-count="{{ $stats['patients_today'] }}">0</div>
                <div class="kpi-label">Pasien Hari Ini</div>
                <div class="kpi-change up"><i class="bi bi-arrow-up-short"></i> Total {{ number_format($stats['total_patients']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-emerald">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value">{{ $stats['total_doctors'] }}</div>
                <div class="kpi-label">Dokter Aktif</div>
                <div class="kpi-change up"><i class="bi bi-dot"></i> Siap melayani</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-cyan">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value" data-count="{{ $stats['appointments_today'] }}">0</div>
                <div class="kpi-label">Appointment Hari Ini</div>
                <div class="kpi-change up"><i class="bi bi-dot"></i> {{ $stats['appointments_month'] }} bulan ini</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-teal">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value" style="font-size:1.2rem;">Rp {{ number_format($stats['revenue_month'], 0, ',', '.') }}</div>
                <div class="kpi-label">Pendapatan Bulan Ini</div>
                <div class="kpi-change up"><i class="bi bi-arrow-up-short"></i> Completed payments</div>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards Row 2 --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start gap-3 mb-2">
                <div class="kpi-icon-box gradient-amber">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div>
                    <div class="kpi-value">{{ $stats['occupied_rooms'] }}/{{ $stats['total_rooms'] }}</div>
                    <div class="kpi-label">Kamar Terisi</div>
                </div>
            </div>
            <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ $occupancyPct }}%;"></div></div>
            <div style="font-size:0.68rem;color:var(--text-muted);margin-top:4px;">Okupansi {{ $occupancyPct }}%</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start gap-3">
                <div class="kpi-icon-box {{ $stats['critical_drugs'] > 0 ? 'gradient-rose' : 'gradient-purple' }}">
                    <i class="bi bi-capsule-pill"></i>
                </div>
                <div>
                    <div class="kpi-value">{{ number_format($stats['total_drugs']) }}</div>
                    <div class="kpi-label">Stok Obat</div>
                    @if($stats['critical_drugs'] > 0)
                        <div class="kpi-change down"><i class="bi bi-exclamation-triangle-fill"></i> {{ $stats['critical_drugs'] }} kritis</div>
                    @else
                        <div class="kpi-change up"><i class="bi bi-check-circle-fill"></i> Aman</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-rose">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value" data-count="{{ $stats['emergency_today'] }}">0</div>
                <div class="kpi-label">IGD Hari Ini</div>
                <div class="kpi-change down"><i class="bi bi-dot"></i> <span class="live-dot crit"></span> Emergency</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="kpi-card d-flex align-items-start gap-3">
            <div class="kpi-icon-box gradient-purple">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="kpi-value" style="font-size:0.95rem;">--</div>
                <div class="kpi-label">Kepuasan Pasien</div>
                <div class="kpi-change" style="background:rgba(100,116,139,0.1);color:#64748b;"><i class="bi bi-dot"></i> Modul survey belum aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- Medical Visual Element --}}
<div class="medical-wave fade-up mb-4"></div>

{{-- Analytics Section --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-xl-8">
        <div class="card-premium">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-graph-up me-2" style="color:#2563eb;"></i>Kunjungan Pasien 7 Hari Terakhir</span>
                <div class="tab-modern">
                    <button class="tab-btn active">7H</button>
                    <button class="tab-btn">30H</button>
                </div>
            </div>
            <div class="chart-card">
                <canvas id="patientChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card-premium h-100">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-pie-chart-fill me-2" style="color:#06b6d4;"></i>Poli Terpadat</span>
            </div>
            <div class="chart-card">
                <canvas id="polyChart" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-xl-6">
        <div class="card-premium">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-cash-stack me-2" style="color:#10b981;"></i>Pendapatan 6 Bulan Terakhir</span>
                <span style="font-size:0.7rem;color:var(--text-muted);">Completed payments</span>
            </div>
            <div class="chart-card">
                <canvas id="revenueChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card-premium">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-clock-history me-2" style="color:#6366f1;"></i>Jam Sibuk Kunjungan Hari Ini</span>
            </div>
            <div class="chart-card">
                <canvas id="hourlyChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Live Operations + Recent Tables --}}
<div class="row g-3 mb-4 fade-up">
    {{-- Live Queue --}}
    <div class="col-xl-4">
        <div class="card-premium h-100">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-person-lines-fill me-2" style="color:#2563eb;"></i>Antrian Live <span class="live-dot"></span></span>
                <a href="{{ route('queues.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.7rem;">Lihat Semua</a>
            </div>
            <div style="padding:0.25rem 1.25rem 1rem;">
                @forelse($liveQueues as $q)
                <div class="live-queue-item">
                    <div class="queue-num">{{ $q->queue_number ?? '#' }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div style="font-weight:600;font-size:0.82rem;">{{ $q->patient->name ?? 'Pasien' }}</div>
                        <div style="font-size:0.7rem;color:var(--text-muted);">{{ $q->polyclinic->name ?? 'Poli' }} — Dr. {{ $q->doctor->name ?? '-' }}</div>
                    </div>
                    <span class="badge-modern warning">Menunggu</span>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--text-muted);">
                    <i class="bi bi-check-circle" style="font-size:1.5rem;opacity:0.3;"></i>
                    <p style="font-size:0.8rem;margin:0.5rem 0 0;">Tidak ada antrian</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ambulance + IGD --}}
    <div class="col-xl-4">
        <div class="card-premium mb-3">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-truck me-2" style="color:#f59e0b;"></i>Ambulans Standby</span>
                <a href="{{ route('ambulances.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.7rem;">Kelola</a>
            </div>
            <div style="padding:0.5rem 1.25rem 1rem;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:0.82rem;font-weight:600;">
                        <span class="live-dot" style="background:#10b981;"></span> {{ $stats['ambulance_active'] }} Unit Siap
                    </span>
                    <span class="badge-modern success">Ready</span>
                </div>
                @forelse($ambulanceCalls as $call)
                @php $callStatus = is_object($call) ? ($call->status ?? 'pending') : 'pending'; @endphp
                <div style="font-size:0.78rem;padding:0.3rem 0;border-bottom:1px solid var(--border);">
                    <span style="font-weight:600;">{{ is_object($call) ? ($call->patient_name ?? 'Pasien') : 'Pasien' }}</span>
                    <span style="color:var(--text-muted);"> — {{ is_object($call) ? ($call->pickup_location ?? '-') : '-' }}</span>
                    <span class="badge-modern {{ $callStatus === 'completed' ? 'success' : 'warning' }}" style="float:right;">{{ $callStatus }}</span>
                </div>
                @empty
                <div style="font-size:0.78rem;color:var(--text-muted);text-align:center;padding:0.5rem 0;">Belum ada panggilan ambulans hari ini</div>
                @endforelse
            </div>
        </div>

        <div class="card-premium">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-building-exclamation me-2" style="color:#ef4444;"></i>Alert Kamar</span>
            </div>
            <div style="padding:0.5rem 1.25rem 1rem;">
                @if($stats['total_rooms'] > 0 && $occupancyPct >= 80)
                <div class="d-flex align-items-center gap-2 mb-2" style="color:#ef4444;">
                    <span class="live-dot crit"></span>
                    <span style="font-size:0.8rem;font-weight:600;">Kamar hampir penuh! {{ $stats['occupied_rooms'] }}/{{ $stats['total_rooms'] }} terisi</span>
                </div>
                @else
                <div class="d-flex align-items-center gap-2 mb-2" style="color:#10b981;">
                    <span class="live-dot"></span>
                    <span style="font-size:0.8rem;font-weight:600;">Okupansi normal — {{ $stats['occupied_rooms'] }}/{{ $stats['total_rooms'] }} terisi</span>
                </div>
                @endif
                @if($stats['critical_drugs'] > 0)
                <div class="d-flex align-items-center gap-2" style="color:#f59e0b;">
                    <span class="live-dot warn"></span>
                    <span style="font-size:0.8rem;">Stok obat kritis: {{ $stats['critical_drugs'] }} item</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="col-xl-4">
        <div class="card-premium h-100">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-credit-card me-2" style="color:#10b981;"></i>Pembayaran Terbaru</span>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.7rem;">Lihat Semua</a>
            </div>
            <div style="padding:0;overflow:auto;">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $p)
                        <tr>
                            <td style="font-weight:600;">{{ $p->appointment?->patient?->name ?? '-' }}</td>
                            <td>Rp {{ number_format((float) ($p->amount ?? 0), 0, ',', '.') }}</td>
                            <td>
                                @if($p->status === 'completed')
                                    <span class="badge-modern success"><span class="badge-dot"></span> Lunas</span>
                                @elseif($p->status === 'pending')
                                    <span class="badge-modern warning"><span class="badge-dot"></span> Pending</span>
                                @elseif($p->status === 'cancelled')
                                    <span class="badge-modern danger"><span class="badge-dot"></span> Batal</span>
                                @else
                                    <span class="badge-modern info">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem 0;">Belum ada pembayaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Recent Appointments + Patients --}}
<div class="row g-3 fade-up">
    <div class="col-xl-7">
        <div class="card-premium">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-calendar-event me-2" style="color:#2563eb;"></i>Appointment Terbaru</span>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.7rem;">Lihat Semua</a>
            </div>
            <div style="overflow:auto;">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments as $app)
                        <tr>
                            <td style="font-weight:600;">{{ $app->patient?->name ?? '-' }}</td>
                            <td>{{ $app->doctor?->name ?? '-' }}</td>
                            <td>{{ $app->appointment_date?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>
                                @if($app->status === 'completed')
                                    <span class="badge-modern success">Selesai</span>
                                @elseif($app->status === 'confirmed')
                                    <span class="badge-modern primary">Dikonfirmasi</span>
                                @elseif($app->status === 'cancelled')
                                    <span class="badge-modern danger">Dibatalkan</span>
                                @else
                                    <span class="badge-modern warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem 0;">Belum ada appointment</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card-premium h-100">
            <div class="glow-ring"></div>
            <div class="card-header-premium">
                <span><i class="bi bi-people-fill me-2" style="color:#6366f1;"></i>Pasien Terbaru</span>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.7rem;">Lihat Semua</a>
            </div>
            <div style="overflow:auto;">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. RM</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPatients as $pat)
                        <tr>
                            <td style="font-weight:600;">{{ $pat->name }}</td>
                            <td>{{ $pat->medical_record_number ?? '-' }}</td>
                            <td style="color:var(--text-muted);">{{ $pat->created_at?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem 0;">Belum ada pasien</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const primaryBlue = '#2563eb';
    const teal = '#06b6d4';
    const emerald = '#10b981';
    const purple = '#6366f1';
    const amber = '#f59e0b';
    const gridColor = 'rgba(203,213,225,0.25)';
    const gridColorDark = 'rgba(100,116,139,0.1)';
    const textColor = '#64748b';
    const isDark = document.body.classList.contains('dark');

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = textColor;
    Chart.defaults.plugins.tooltip.backgroundColor = isDark ? '#1e293b' : '#fff';
    Chart.defaults.plugins.tooltip.titleColor = isDark ? '#e2e8f0' : '#1e293b';
    Chart.defaults.plugins.tooltip.bodyColor = isDark ? '#cbd5e1' : '#475569';
    Chart.defaults.plugins.tooltip.borderColor = isDark ? 'rgba(255,255,255,0.1)' : '#e2e8f0';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.cornerRadius = 10;
    Chart.defaults.plugins.tooltip.padding = 10;

    function createGradient(ctx, c1, c2) {
        var g = ctx.createLinearGradient(0, 0, 0, 300);
        g.addColorStop(0, c1); g.addColorStop(1, c2);
        return g;
    }

    // Patient Chart
    var pCtx = document.getElementById('patientChart')?.getContext('2d');
    if (pCtx) {
        new Chart(pCtx, {
            type: 'line',
            data: {
                labels: @json($patientChart['labels']),
                datasets: [{
                    label: 'Pasien',
                    data: @json($patientChart['data']),
                    borderColor: primaryBlue,
                    backgroundColor: createGradient(pCtx, 'rgba(37,99,235,0.15)', 'rgba(37,99,235,0)'),
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: primaryBlue,
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: isDark ? gridColorDark : gridColor }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Poly Chart
    var polyCtx = document.getElementById('polyChart')?.getContext('2d');
    if (polyCtx) {
        new Chart(polyCtx, {
            type: 'doughnut',
            data: {
                labels: @json($polyclinicChart['labels']),
                datasets: [{
                    data: @json($polyclinicChart['data']),
                    backgroundColor: [primaryBlue, teal, emerald, purple, amber, '#f43f5e', '#64748b'],
                    borderWidth: 2,
                    borderColor: isDark ? '#111c35' : '#fff',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            usePointStyle: true,
                            pointStyleWidth: 8,
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    }

    // Revenue Chart
    var rCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (rCtx) {
        new Chart(rCtx, {
            type: 'bar',
            data: {
                labels: @json($revenueChart['labels']),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($revenueChart['data']),
                    backgroundColor: createGradient(rCtx, 'rgba(16,185,129,0.7)', 'rgba(16,185,129,0.2)'),
                    borderColor: emerald,
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: isDark ? gridColorDark : gridColor },
                        ticks: { callback: function(v) { return v >= 1000000 ? (v/1000000).toFixed(1)+'M' : v >= 1000 ? (v/1000).toFixed(0)+'K' : v; } }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Hourly Chart
    var hCtx = document.getElementById('hourlyChart')?.getContext('2d');
    if (hCtx) {
        new Chart(hCtx, {
            type: 'bar',
            data: {
                labels: @json($hourlyChart['labels']),
                datasets: [{
                    label: 'Visit',
                    data: @json($hourlyChart['data']),
                    backgroundColor: createGradient(hCtx, 'rgba(99,102,241,0.6)', 'rgba(99,102,241,0.05)'),
                    borderColor: purple,
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: isDark ? gridColorDark : gridColor } },
                    x: {
                        grid: { display: false },
                        ticks: { maxTicksLimit: 8, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Counter Animation
    document.querySelectorAll('[data-count]').forEach(function(el) {
        var count = parseInt(el.getAttribute('data-count'));
        if (!count) { el.textContent = '0'; return; }
        var duration = 1200;
        var start = 0;
        var startTime = null;

        function animate(ts) {
            if (!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * count);
            if (progress < 1) requestAnimationFrame(animate);
            else el.textContent = count;
        }
        requestAnimationFrame(animate);
    });
});
</script>
@endpush
