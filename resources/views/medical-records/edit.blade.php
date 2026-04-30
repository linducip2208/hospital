@extends('layouts.admin')

@section('title', 'Edit Rekam Medis')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Rekam Medis</h1>
    <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('medical-records.update', $medicalRecord) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $medicalRecord->patient_id) == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter <span class="text-danger">*</span></label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $medicalRecord->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Appointment</label>
                    <select name="appointment_id" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($appointments as $appt)
                            <option value="{{ $appt->id }}" @selected(old('appointment_id', $medicalRecord->appointment_id) == $appt->id)>{{ $appt->patient->name ?? '-' }} - {{ $appt->appointment_date->format('d/m/Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                    @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Tindakan</label>
                    <textarea name="action" class="form-control @error('action') is-invalid @enderror" rows="3">{{ old('action', $medicalRecord->action) }}</textarea>
                    @error('action') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Obat / Resep</label>
                    <textarea name="medicine" class="form-control @error('medicine') is-invalid @enderror" rows="3">{{ old('medicine', $medicalRecord->medicine) }}</textarea>
                    @error('medicine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Hasil Lab</label>
                    <textarea name="lab_results" class="form-control @error('lab_results') is-invalid @enderror" rows="3">{{ old('lab_results', $medicalRecord->lab_results) }}</textarea>
                    @error('lab_results') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $medicalRecord->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
