@extends('portal.layout')

@section('title', 'Detail Janji Temu')

@section('content')
<a href="{{ route('portal.appointments.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali</a>
<h1 class="h4 fw-bold my-3">Detail Janji Temu</h1>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-soft p-4">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted">Tanggal</dt><dd class="col-sm-8">{{ $appointment->appointment_date?->format('d F Y') }} · {{ $appointment->start_time }}</dd>
                <dt class="col-sm-4 text-muted">Dokter</dt><dd class="col-sm-8">dr. {{ $appointment->doctor?->name ?? '-' }}</dd>
                <dt class="col-sm-4 text-muted">Poliklinik</dt><dd class="col-sm-8">{{ $appointment->polyclinic?->name ?? '-' }}</dd>
                <dt class="col-sm-4 text-muted">Tindakan</dt><dd class="col-sm-8">{{ $appointment->treatment?->name ?? '-' }}</dd>
                <dt class="col-sm-4 text-muted">Status</dt><dd class="col-sm-8"><span class="badge bg-info">{{ $appointment->status }}</span></dd>
                <dt class="col-sm-4 text-muted">Keluhan</dt><dd class="col-sm-8">{{ $appointment->complaint ?? '-' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-soft p-4">
            <h6 class="fw-bold mb-3">Rekam Medis Terkait</h6>
            @if($appointment->medicalRecord)
                <p class="mb-2"><strong>Diagnosis:</strong> {{ $appointment->medicalRecord->diagnosis ?? '-' }}</p>
                <a href="{{ route('portal.medical-records.show', $appointment->medicalRecord) }}" class="btn btn-sm btn-outline-primary">Lihat Rekam Medis</a>
            @else
                <p class="text-muted mb-0">Belum ada rekam medis untuk kunjungan ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection
