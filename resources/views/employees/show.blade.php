@extends('layouts.admin')

@section('title', 'Detail Pegawai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pegawai</h1>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h5>{{ $employee->user->name ?? '-' }}</h5>
                <p class="text-muted mb-1">{{ $employee->employee_code ?? 'Kode: -' }}</p>
                <span class="badge bg-{{ match($employee->employment_status) { 'permanent' => 'success', 'contract' => 'info', 'probation' => 'warning', 'intern' => 'secondary', 'resigned' => 'danger', default => 'secondary' } }}">
                    {{ match($employee->employment_status) { 'permanent' => 'Tetap', 'contract' => 'Kontrak', 'probation' => 'Percobaan', 'intern' => 'Magang', 'resigned' => 'Resign', default => $employee->employment_status ?? '-' } }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Email:</strong> {{ $employee->user->email ?? '-' }}</li>
                <li class="list-group-item"><strong>Jabatan:</strong> {{ $employee->position ?? '-' }}</li>
                <li class="list-group-item"><strong>Departemen:</strong> {{ $employee->department ?? '-' }}</li>
                <li class="list-group-item"><strong>Tgl Bergabung:</strong> {{ $employee->join_date ? $employee->join_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Gaji Pokok:</strong> Rp {{ number_format($employee->base_salary ?? 0, 0, ',', '.') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Perbankan & Administrasi</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Bank:</strong> {{ $employee->bank_name ?? '-' }}</p>
                        <p><strong>No. Rekening:</strong> {{ $employee->bank_account ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>BPJS TK:</strong> {{ $employee->bpjs_tk ?? '-' }}</p>
                        <p><strong>NPWP:</strong> {{ $employee->tax_number ?? '-' }}</p>
                    </div>
                </div>
                <p><strong>Pendidikan:</strong> {{ $employee->education_level ?? '-' }}</p>
                <p><strong>Kontak Darurat:</strong> {{ $employee->emergency_contact ?? '-' }}</p>
                @if($employee->notes)
                    <p><strong>Catatan:</strong><br>{{ $employee->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <span>Riwayat Penggajian</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->salaries as $salary)
                        <tr>
                            <td>{{ $salary->period_month }}/{{ $salary->period_year }}</td>
                            <td>Rp {{ number_format($salary->base_salary ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($salary->total_salary ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $salary->status === 'paid' ? 'success' : ($salary->status === 'approved' ? 'info' : 'warning') }}">
                                    {{ $salary->status === 'paid' ? 'Dibayar' : ($salary->status === 'approved' ? 'Disetujui' : 'Draft') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data penggajian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
