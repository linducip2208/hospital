@extends('layouts.admin')

@section('title', 'Detail Dokter')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Dokter</h1>
    <div>
        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-person-badge"></i></div>
                <h5>{{ $doctor->name }}</h5>
                <p class="text-muted">{{ $doctor->specialization ?? '-' }}</p>
                <span class="badge bg-{{ $doctor->status === 'active' ? 'success' : ($doctor->status === 'on_leave' ? 'warning' : 'secondary') }}">
                    {{ $doctor->status === 'active' ? 'Aktif' : ($doctor->status === 'on_leave' ? 'Cuti' : 'Tidak Aktif') }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Email:</strong> {{ $doctor->email ?? '-' }}</li>
                <li class="list-group-item"><strong>Telp:</strong> {{ $doctor->phone ?? '-' }}</li>
                <li class="list-group-item"><strong>STR:</strong> {{ $doctor->str_number ?? '-' }}</li>
                <li class="list-group-item"><strong>Biaya Konsultasi:</strong> Rp {{ number_format($doctor->consultation_fee ?? 0, 0, ',', '.') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Tambahan</div>
            <div class="card-body">
                <p><strong>Alamat:</strong><br>{{ $doctor->address ?? '-' }}</p>
                @if($doctor->notes)
                    <p><strong>Catatan:</strong><br>{{ $doctor->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Riwayat Appointment</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Pasien</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($doctor->appointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $appt->patient->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $appt->status === 'completed' ? 'success' : ($appt->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($appt->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada appointment</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
