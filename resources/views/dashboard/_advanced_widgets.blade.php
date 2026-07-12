{{-- ═══════════════ WIDGET KLINIS & OPERASIONAL LANJUTAN ═══════════════ --}}

{{-- Triase IGD + Klaim BPJS + Kepuasan --}}
<div class="row g-3 mb-4 fade-up">
    {{-- Triase IGD --}}
    <div class="col-xl-4 col-md-6">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-exclamation-octagon-fill me-2" style="color:#f43f5e;"></i>Triase IGD Aktif</span>
                <a href="{{ route('emergencies.index') }}" class="small text-decoration-none">Lihat →</a>
            </div>
            <div class="p-3">
                <div class="row g-2 text-center mb-3">
                    <div class="col-3"><div class="p-2 rounded" style="background:rgba(239,68,68,.12)"><div class="fw-bold fs-4 text-danger">{{ $triage['red'] }}</div><small class="text-danger">Merah</small></div></div>
                    <div class="col-3"><div class="p-2 rounded" style="background:rgba(245,158,11,.12)"><div class="fw-bold fs-4 text-warning">{{ $triage['yellow'] }}</div><small class="text-warning">Kuning</small></div></div>
                    <div class="col-3"><div class="p-2 rounded" style="background:rgba(16,185,129,.12)"><div class="fw-bold fs-4 text-success">{{ $triage['green'] }}</div><small class="text-success">Hijau</small></div></div>
                    <div class="col-3"><div class="p-2 rounded" style="background:rgba(100,116,139,.12)"><div class="fw-bold fs-4 text-secondary">{{ $triage['black'] }}</div><small class="text-secondary">Hitam</small></div></div>
                </div>
                @forelse($triageQueue as $e)
                    <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                        <span class="badge bg-{{ ['red'=>'danger','yellow'=>'warning','green'=>'success','black'=>'secondary'][$e->triage] ?? 'secondary' }}">&nbsp;</span>
                        <span class="small flex-grow-1">{{ $e->patient?->name ?? 'Pasien' }}</span>
                        <span class="small text-muted">{{ \Illuminate\Support\Str::limit($e->complaint, 20) }}</span>
                    </div>
                @empty
                    <p class="text-muted small text-center mb-0">Tidak ada pasien IGD aktif</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Klaim BPJS/Asuransi --}}
    <div class="col-xl-4 col-md-6">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-shield-shaded me-2" style="color:#2563eb;"></i>Status Klaim BPJS/Asuransi</span>
                <a href="{{ route('insurance-claims.index') }}" class="small text-decoration-none">Lihat →</a>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between py-1 border-bottom"><span class="small"><i class="bi bi-hourglass-split text-warning"></i> Diajukan</span><span class="fw-bold">{{ $claimStats['submitted'] }}</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span class="small"><i class="bi bi-check-circle text-success"></i> Disetujui</span><span class="fw-bold text-success">{{ $claimStats['approved'] }}</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span class="small"><i class="bi bi-x-circle text-danger"></i> Ditolak</span><span class="fw-bold text-danger">{{ $claimStats['rejected'] }}</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span class="small"><i class="bi bi-cash-coin text-primary"></i> Dibayar</span><span class="fw-bold text-primary">{{ $claimStats['paid'] }}</span></div>
                <div class="mt-2 p-2 rounded" style="background:rgba(245,158,11,.1)">
                    <small class="text-muted d-block">Nilai klaim pending</small>
                    <span class="fw-bold text-warning">Rp {{ number_format($claimStats['pending_amount'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Kepuasan Pasien --}}
    <div class="col-xl-4 col-md-12">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-emoji-smile-fill me-2" style="color:#10b981;"></i>Kepuasan Pasien (Bulan Ini)</span>
                <a href="{{ route('patient-feedbacks.index') }}" class="small text-decoration-none">Lihat →</a>
            </div>
            <div class="p-3 text-center">
                @if($satisfaction['count'] > 0)
                    <div class="display-4 fw-bold text-success mb-0">{{ number_format($satisfaction['avg'], 1) }}<small class="fs-6 text-muted">/5</small></div>
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($satisfaction['avg']) ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold">{{ $satisfaction['count'] }}</div><small class="text-muted">Responden</small></div></div>
                        <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold text-success">{{ $satisfaction['nps'] ?? 0 }}%</div><small class="text-muted">Merekomendasikan</small></div></div>
                    </div>
                @else
                    <div class="py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Belum ada survey kepuasan bulan ini
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TAT Lab/Radiologi + Rujukan + Roster --}}
<div class="row g-3 mb-4 fade-up">
    {{-- Turnaround Time Penunjang --}}
    <div class="col-xl-4 col-md-6">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-clipboard2-data me-2" style="color:#06b6d4;"></i>Penunjang Diagnostik (TAT)</span>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span><i class="bi bi-eyedropper text-info"></i> Lab</span>
                    <span class="small">Pending: <b>{{ $labTat['pending'] }}</b> · Selesai hari ini: <b>{{ $labTat['completed_today'] }}</b></span>
                </div>
                <div class="text-center py-2 border-bottom">
                    <small class="text-muted">Rata-rata waktu hasil lab</small>
                    <div class="fw-bold fs-5 text-info">{{ $labTat['avg_hours'] ?: '—' }} jam</div>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span><i class="bi bi-radioactive text-warning"></i> Radiologi</span>
                    <span class="small">Pending: <b>{{ $radioTat['pending'] }}</b> · Selesai hari ini: <b>{{ $radioTat['completed_today'] }}</b></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Rujukan In/Out --}}
    <div class="col-xl-4 col-md-6">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-signpost-split me-2" style="color:#6366f1;"></i>Rujukan (Referral)</span>
                <a href="{{ route('referrals.index') }}" class="small text-decoration-none">Lihat →</a>
            </div>
            <div class="p-3">
                <div class="row g-2 text-center">
                    <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold fs-4 text-warning">{{ $referralStats['pending'] }}</div><small class="text-muted">Pending</small></div></div>
                    <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold fs-4 text-primary">{{ $referralStats['approved'] }}</div><small class="text-muted">Disetujui</small></div></div>
                    <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold fs-4 text-success">{{ $referralStats['completed'] }}</div><small class="text-muted">Selesai</small></div></div>
                    <div class="col-6"><div class="p-2 rounded bg-light"><div class="fw-bold fs-4">{{ $referralStats['today'] }}</div><small class="text-muted">Hari Ini</small></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Roster: siapa shift sekarang --}}
    <div class="col-xl-4 col-md-12">
        <div class="card-premium h-100">
            <div class="card-header-premium">
                <span><i class="bi bi-person-badge me-2" style="color:#f59e0b;"></i>Sedang Bertugas ({{ $onDutyCount }})</span>
                <a href="{{ route('staff-schedules.index') }}" class="small text-decoration-none">Jadwal →</a>
            </div>
            <div class="p-3" style="max-height:220px;overflow-y:auto">
                @forelse($onDutyNow as $s)
                    <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                        <span class="queue-num" style="width:28px;height:28px;">{{ strtoupper(substr($s->user?->name ?? 'U', 0, 1)) }}</span>
                        <div class="flex-grow-1">
                            <div class="small fw-semibold">{{ $s->user?->name ?? '-' }}</div>
                            <div class="small text-muted">{{ $s->department }} · {{ $s->start_time }}–{{ $s->end_time }}</div>
                        </div>
                        <span class="badge bg-success">On</span>
                    </div>
                @empty
                    <p class="text-muted small text-center mb-0">Tidak ada staf terjadwal aktif saat ini</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Denah Bed Real-time --}}
<div class="row g-3 mb-4 fade-up">
    <div class="col-12">
        <div class="card-premium">
            <div class="card-header-premium">
                <span><i class="bi bi-hospital me-2" style="color:#2563eb;"></i>Denah Bed Real-time</span>
                <span class="small">
                    <span class="badge bg-success">Kosong {{ $bedSummary['available'] }}</span>
                    <span class="badge bg-danger">Terisi {{ $bedSummary['occupied'] }}</span>
                    <span class="badge bg-info">Cleaning {{ $bedSummary['cleaning'] }}</span>
                    <span class="badge bg-warning">Reserved {{ $bedSummary['reserved'] }}</span>
                    <span class="badge bg-secondary">Maintenance {{ $bedSummary['maintenance'] }}</span>
                </span>
            </div>
            <div class="p-3">
                @php
                    $bedColors = ['available'=>'#10b981','occupied'=>'#ef4444','cleaning'=>'#06b6d4','reserved'=>'#f59e0b','maintenance'=>'#64748b','blocked'=>'#475569'];
                @endphp
                @forelse($bedBoard as $roomName => $beds)
                    <div class="mb-2">
                        <div class="small fw-semibold text-muted mb-1">{{ $roomName }}</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($beds as $bed)
                                <div title="{{ $bed->label }} — {{ ucfirst($bed->status) }}{{ $bed->currentPatient ? ' · '.$bed->currentPatient->name : '' }}"
                                     style="width:52px;height:52px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:700;background:{{ $bedColors[$bed->status] ?? '#64748b' }}">
                                    <i class="bi bi-hospital"></i>
                                    <span>{{ $bed->bed_code }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">Belum ada data bed. Tambahkan di menu Rawat Inap → Bed Management.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
