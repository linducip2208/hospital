@extends('layouts.admin')

@section('title', 'Detail Pasien')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pasien</h1>
    <div>
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h5>{{ $patient->name }}</h5>
                <p class="text-muted mb-1">{{ $patient->nik ?? 'NIK: -' }}</p>
                <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }}">
                    {{ $patient->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Email:</strong> {{ $patient->email ?? '-' }}</li>
                <li class="list-group-item"><strong>Telp:</strong> {{ $patient->phone ?? '-' }}</li>
                <li class="list-group-item"><strong>Gender:</strong> {{ $patient->gender === 'male' ? 'Laki-laki' : ($patient->gender === 'female' ? 'Perempuan' : '-') }}</li>
                <li class="list-group-item"><strong>Tgl Lahir:</strong> {{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Gol. Darah:</strong> {{ $patient->blood_type ?? '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Tambahan</div>
            <div class="card-body">
                <p><strong>Alamat:</strong><br>{{ $patient->address ?? '-' }}</p>
                <p><strong>Alergi:</strong><br>{{ $patient->allergies ?? '-' }}</p>
                <p><strong>Riwayat Medis:</strong><br>{{ $patient->medical_history ?? '-' }}</p>
                <p><strong>Kontak Darurat:</strong> {{ $patient->emergency_contact_name ?? '-' }} ({{ $patient->emergency_contact_phone ?? '-' }})</p>
                @if($patient->notes)
                    <p><strong>Catatan:</strong><br>{{ $patient->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between">
                <span>Riwayat Appointment</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Dokter</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->appointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $appt->doctor->name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $appt->status === 'completed' ? 'success' : ($appt->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($appt->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada appointment</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <span>Rekam Medis</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Diagnosis</th><th>Tindakan</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->medicalRecords as $mr)
                        <tr>
                            <td>{{ $mr->created_at->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($mr->diagnosis, 50) }}</td>
                            <td>{{ Str::limit($mr->action ?? '-', 50) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada rekam medis</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
