@extends('portal.layout')

@section('title', 'Detail Rekam Medis')

@section('content')
<a href="{{ route('portal.medical-records.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali</a>
<h1 class="h4 fw-bold my-3">Rekam Medis</h1>

<div class="card-soft p-4" style="max-width:720px">
    <dl class="row mb-0">
        <dt class="col-sm-3 text-muted">Tanggal</dt><dd class="col-sm-9">{{ $medicalRecord->created_at?->format('d F Y H:i') }}</dd>
        <dt class="col-sm-3 text-muted">Dokter</dt><dd class="col-sm-9">dr. {{ $medicalRecord->doctor?->name ?? '-' }}</dd>
        <dt class="col-sm-3 text-muted">Diagnosis</dt><dd class="col-sm-9">{{ $medicalRecord->diagnosis ?? '-' }}</dd>
        <dt class="col-sm-3 text-muted">Tindakan</dt><dd class="col-sm-9">{{ $medicalRecord->action ?? '-' }}</dd>
        <dt class="col-sm-3 text-muted">Obat</dt><dd class="col-sm-9">{{ $medicalRecord->medicine ?? '-' }}</dd>
        <dt class="col-sm-3 text-muted">Catatan</dt><dd class="col-sm-9">{{ $medicalRecord->notes ?? '-' }}</dd>
    </dl>
</div>
<p class="text-muted small mt-3"><i class="bi bi-shield-lock"></i> Data rekam medis bersifat rahasia dan hanya dapat diakses oleh Anda.</p>
@endsection
