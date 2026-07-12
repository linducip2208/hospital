@extends('layouts.admin')

@section('title', 'Detail Poli')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Poli</h1>
    <div>
        <a href="{{ route('polyclinics.edit', $polyclinic) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('polyclinics.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-hospital"></i></div>
                <h5>{{ $polyclinic->name }}</h5>
                <p class="text-muted">{{ $polyclinic->code ?? '-' }}</p>
                <span class="badge bg-{{ $polyclinic->is_active ? 'success' : 'danger' }}">
                    {{ $polyclinic->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Lantai:</strong> {{ $polyclinic->floor ?? '-' }}</li>
                <li class="list-group-item"><strong>Telepon:</strong> {{ $polyclinic->phone ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($polyclinic->description)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Deskripsi</div>
            <div class="card-body">
                <p class="mb-0">{{ $polyclinic->description }}</p>
            </div>
        </div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-header">Dokter Poli</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>Spesialisasi</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($polyclinic->doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->specialization ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $doctor->status === 'active' ? 'success' : ($doctor->status === 'on_leave' ? 'warning' : 'secondary') }}">
                                    {{ $doctor->status === 'active' ? 'Aktif' : ($doctor->status === 'on_leave' ? 'Cuti' : 'Tidak Aktif') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada dokter</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Antrian Terkini</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>No Antrian</th><th>Pasien</th><th>Dokter</th><th>Status</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        @forelse($polyclinic->queues ?? [] as $queue)
                        <tr>
                            <td>{{ $queue->queue_number }}</td>
                            <td>{{ $queue->patient->name ?? '-' }}</td>
                            <td>{{ $queue->doctor->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $queue->status === 'waiting' ? 'warning' : ($queue->status === 'called' ? 'info' : ($queue->status === 'in_progress' ? 'primary' : ($queue->status === 'completed' ? 'success' : 'danger'))) }}">
                                    {{ $queue->status === 'waiting' ? 'Menunggu' : ($queue->status === 'called' ? 'Dipanggil' : ($queue->status === 'in_progress' ? 'Diperiksa' : ($queue->status === 'completed' ? 'Selesai' : 'Batal'))) }}
                                </span>
                            </td>
                            <td>{{ $queue->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada antrian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
