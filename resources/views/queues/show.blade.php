@extends('layouts.admin')

@section('title', 'Detail Antrian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Antrian</h1>
    <div>
        <a href="{{ route('queues.edit', $queue) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('queues.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    @if($queue->status === 'waiting')
                        <i class="bi bi-hourglass-split text-warning"></i>
                    @elseif($queue->status === 'called')
                        <i class="bi bi-megaphone text-info"></i>
                    @elseif($queue->status === 'in_progress')
                        <i class="bi bi-activity text-primary"></i>
                    @elseif($queue->status === 'completed')
                        <i class="bi bi-check-circle text-success"></i>
                    @else
                        <i class="bi bi-x-circle text-danger"></i>
                    @endif
                </div>
                <h5>No. {{ $queue->queue_number }}</h5>
                <p class="text-muted mb-1">{{ $queue->polyclinic->name ?? '-' }}</p>
                <span class="badge bg-{{ $queue->status === 'waiting' ? 'warning' : ($queue->status === 'called' ? 'info' : ($queue->status === 'in_progress' ? 'primary' : ($queue->status === 'completed' ? 'success' : 'danger'))) }}">
                    {{ $queue->status === 'waiting' ? 'Menunggu' : ($queue->status === 'called' ? 'Dipanggil' : ($queue->status === 'in_progress' ? 'Diperiksa' : ($queue->status === 'completed' ? 'Selesai' : 'Batal'))) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $queue->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $queue->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Tanggal:</strong> {{ $queue->created_at->format('d/m/Y H:i') }}</li>
                @if($queue->called_at)
                <li class="list-group-item"><strong>Dipanggil:</strong> {{ $queue->called_at->format('d/m/Y H:i') }}</li>
                @endif
                @if($queue->completed_at)
                <li class="list-group-item"><strong>Selesai:</strong> {{ $queue->completed_at->format('d/m/Y H:i') }}</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Pasien</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $queue->patient->name ?? '-' }}</p>
                        <p><strong>NIK:</strong> {{ $queue->patient->nik ?? '-' }}</p>
                        <p><strong>Jenis Kelamin:</strong> {{ $queue->patient->gender === 'male' ? 'Laki-laki' : ($queue->patient->gender === 'female' ? 'Perempuan' : '-') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Telepon:</strong> {{ $queue->patient->phone ?? '-' }}</p>
                        <p><strong>Tgl Lahir:</strong> {{ $queue->patient->birth_date ? $queue->patient->birth_date->format('d/m/Y') : '-' }}</p>
                        <p><strong>Gol. Darah:</strong> {{ $queue->patient->blood_type ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($queue->notes)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $queue->notes }}</p>
            </div>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">Riwayat Pasien di Poli Ini</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>No Antrian</th><th>Tanggal</th><th>Dokter</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patientQueues ?? [] as $pastQueue)
                        <tr>
                            <td>{{ $pastQueue->queue_number }}</td>
                            <td>{{ $pastQueue->created_at->format('d/m/Y') }}</td>
                            <td>{{ $pastQueue->doctor->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $pastQueue->status === 'completed' ? 'success' : ($pastQueue->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ $pastQueue->status === 'completed' ? 'Selesai' : ($pastQueue->status === 'cancelled' ? 'Batal' : 'Menunggu') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
