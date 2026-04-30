@extends('layouts.admin')

@section('title', 'Detail Appointment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Appointment</h1>
    <div>
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">Informasi Appointment</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Pasien</th><td><a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name ?? '-' }}</a></td></tr>
                    <tr><th>Dokter</th><td><a href="{{ route('doctors.show', $appointment->doctor) }}">{{ $appointment->doctor->name ?? '-' }}</a></td></tr>
                    <tr><th>Treatment</th><td>{{ $appointment->treatment->name ?? '-' }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Status</th>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' || $appointment->status === 'no_show' ? 'danger' : ($appointment->status === 'confirmed' ? 'primary' : ($appointment->status === 'in_progress' ? 'info' : 'warning'))) }}">
                                {{ str_replace('_', ' ', ucfirst($appointment->status)) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    @if($appointment->status === 'scheduled')
                        <form action="{{ route('appointments.status', [$appointment, 'confirmed']) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-primary">Konfirmasi</button>
                        </form>
                    @endif
                    @if($appointment->status === 'confirmed')
                        <form action="{{ route('appointments.status', [$appointment, 'in_progress']) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-info">Mulai Periksa</button>
                        </form>
                    @endif
                    @if($appointment->status === 'in_progress')
                        <form action="{{ route('appointments.status', [$appointment, 'completed']) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success">Selesai</button>
                        </form>
                    @endif
                    @if(!in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
                        <form action="{{ route('appointments.status', [$appointment, 'cancelled']) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Batalkan appointment?')">Batalkan</button>
                        </form>
                        <form action="{{ route('appointments.status', [$appointment, 'no_show']) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-secondary" onclick="return confirm('Tandai no show?')">No Show</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        @if($appointment->complaint)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Keluhan</div>
            <div class="card-body">{{ $appointment->complaint }}</div>
        </div>
        @endif

        @if($appointment->notes)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Catatan</div>
            <div class="card-body">{{ $appointment->notes }}</div>
        </div>
        @endif

        @if($appointment->medicalRecord)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Rekam Medis</div>
            <div class="card-body">
                <p><strong>Diagnosis:</strong> {{ $appointment->medicalRecord->diagnosis ?? '-' }}</p>
                <p><strong>Tindakan:</strong> {{ $appointment->medicalRecord->action ?? '-' }}</p>
                <p><strong>Obat:</strong> {{ $appointment->medicalRecord->medicine ?? '-' }}</p>
            </div>
        </div>
        @endif

        @if($appointment->payments->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header">Pembayaran</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Jumlah</th><th>Metode</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($appointment->payments as $payment)
                        <tr>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td><span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($payment->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection