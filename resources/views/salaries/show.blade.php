@extends('layouts.admin')

@section('title', 'Detail Penggajian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Penggajian</h1>
    <div>
        @if($salary->status === 'draft')
            <form action="{{ route('salaries.approve', $salary) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info" onclick="return confirm('Setujui penggajian ini?')"><i class="bi bi-check-lg"></i> Setujui</button>
            </form>
        @endif
        @if($salary->status === 'approved')
            <form action="{{ route('salaries.pay', $salary) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pembayaran penggajian ini?')"><i class="bi bi-cash"></i> Bayar</button>
            </form>
        @endif
        <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h5>{{ $salary->user->name ?? $salary->employee->user->name ?? '-' }}</h5>
                <p class="text-muted mb-1">Periode: {{ $salary->period_month }}/{{ $salary->period_year }}</p>
                <span class="badge bg-{{ $salary->status === 'paid' ? 'success' : ($salary->status === 'approved' ? 'info' : 'warning') }}">
                    {{ $salary->status === 'paid' ? 'Dibayar' : ($salary->status === 'approved' ? 'Disetujui' : 'Draft') }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Gaji Pokok:</strong> Rp {{ number_format($salary->base_salary ?? 0, 0, ',', '.') }}</li>
                <li class="list-group-item"><strong>Total Gaji:</strong> Rp {{ number_format($salary->total_salary ?? 0, 0, ',', '.') }}</li>
                @if($salary->paid_at)
                    <li class="list-group-item"><strong>Tgl Dibayar:</strong> {{ $salary->paid_at->format('d/m/Y H:i') }}</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Rincian Penggajian</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td width="200"><strong>Jam Lembur</strong></td><td>{{ $salary->overtime_hours ?? '0' }} jam</td></tr>
                    <tr><td><strong>Upah Lembur</strong></td><td>Rp {{ number_format($salary->overtime_pay ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><td><strong>Bonus</strong></td><td>Rp {{ number_format($salary->bonus ?? 0, 0, ',', '.') }}</td></tr>
                    <tr><td><strong>Potongan</strong></td><td>Rp {{ number_format($salary->deduction ?? 0, 0, ',', '.') }}</td></tr>
                    @if($salary->deduction_note)
                        <tr><td><strong>Keterangan Potongan</strong></td><td>{{ $salary->deduction_note }}</td></tr>
                    @endif
                    <tr class="table-light"><td><strong>Total Gaji</strong></td><td><strong>Rp {{ number_format($salary->total_salary ?? 0, 0, ',', '.') }}</strong></td></tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Pegawai</div>
            <div class="card-body">
                @php $emp = $salary->employee; @endphp
                @if($emp)
                    <p><strong>Nama:</strong> {{ $emp->user->name ?? '-' }}</p>
                    <p><strong>Kode Pegawai:</strong> {{ $emp->employee_code ?? '-' }}</p>
                    <p><strong>Jabatan:</strong> {{ $emp->position ?? '-' }}</p>
                    <p><strong>Departemen:</strong> {{ $emp->department ?? '-' }}</p>
                    <p><strong>Status:</strong> 
                        @php
                            $statusLabels = ['permanent' => 'Tetap', 'contract' => 'Kontrak', 'probation' => 'Percobaan', 'intern' => 'Magang', 'resigned' => 'Resign'];
                        @endphp
                        {{ $statusLabels[$emp->employment_status] ?? $emp->employment_status ?? '-' }}
                    </p>
                @else
                    <p class="text-muted">Data pegawai tidak tersedia.</p>
                @endif
            </div>
        </div>

        @if($salary->notes)
            <div class="card shadow-sm">
                <div class="card-header">Catatan</div>
                <div class="card-body">
                    <p>{{ $salary->notes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
