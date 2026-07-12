@extends('layouts.admin')

@section('title', 'Edit Antrian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Antrian</h1>
    <a href="{{ route('queues.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('queues.update', $queue) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Poli <span class="text-danger">*</span></label>
                    <select name="polyclinic_id" class="form-select @error('polyclinic_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polyclinics as $polyclinic)
                            <option value="{{ $polyclinic->id }}" @selected(old('polyclinic_id', $queue->polyclinic_id) == $polyclinic->id)>{{ $polyclinic->name }}</option>
                        @endforeach
                    </select>
                    @error('polyclinic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $queue->patient_id) == $patient->id)>{{ $patient->name }} ({{ $patient->nik ?? 'NIK: -' }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dokter</label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                        <option value="">-- Pilih Dokter (opsional) --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $queue->doctor_id) == $doctor->id)>{{ $doctor->name }} ({{ $doctor->specialization ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $queue->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('queues.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
