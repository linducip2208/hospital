@extends('layouts.admin')

@section('title', 'Detail Treatment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Treatment</h1>
    <div>
        <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('treatments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-capsule"></i></div>
                <h5>{{ $treatment->name }}</h5>
                <span class="badge bg-{{ $treatment->is_active ? 'success' : 'secondary' }}">{{ $treatment->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Kategori:</strong> {{ $treatment->category ?? '-' }}</li>
                <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($treatment->price ?? 0, 0, ',', '.') }}</li>
                <li class="list-group-item"><strong>Durasi:</strong> {{ $treatment->duration_minutes ? $treatment->duration_minutes . ' menit' : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Deskripsi</div>
            <div class="card-body">
                <p>{{ $treatment->description ?? 'Tidak ada deskripsi' }}</p>
                @if($treatment->requirements)
                    <strong>Persyaratan:</strong>
                    <ul>
                        @foreach($treatment->requirements as $req)
                            <li>{{ $req }}</li>
                        @endforeach
                    </ul>
                @endif
                @if($treatment->notes)
                    <p><strong>Catatan:</strong><br>{{ $treatment->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Riwayat Appointment</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Pasien</th><th>Dokter</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($treatment->appointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $appt->patient->name ?? '-' }}</td>
                            <td>{{ $appt->doctor->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $appt->status === 'completed' ? 'success' : ($appt->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($appt->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada appointment</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
