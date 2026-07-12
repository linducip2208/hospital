@extends('layouts.admin')

@section('title', 'Edit Pasien IGD')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Pasien IGD</h1>
    <a href="{{ route('emergencies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('emergencies.update', $emergency) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $emergency->patient_id) == $patient->id)>{{ $patient->name }} ({{ $patient->medical_record_number ?? $patient->id }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter</label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                        <option value="">Pilih Dokter (opsional)</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $emergency->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Triase <span class="text-danger">*</span></label>
                    <select name="triage" class="form-select @error('triage') is-invalid @enderror" required>
                        <option value="">Pilih Triase</option>
                        <option value="red" @selected(old('triage', $emergency->triage) === 'red')>Red (CRITICAL - Harus ditangani segera)</option>
                        <option value="yellow" @selected(old('triage', $emergency->triage) === 'yellow')>Yellow (URGENT - Perlu penanganan cepat)</option>
                        <option value="green" @selected(old('triage', $emergency->triage) === 'green')>Green (NON-URGENT - Dapat menunggu)</option>
                        <option value="black" @selected(old('triage', $emergency->triage) === 'black')>Black (DECEASED - Meninggal)</option>
                    </select>
                    @error('triage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cara Kedatangan</label>
                    <select name="arrival_mode" class="form-select @error('arrival_mode') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="Ambulans" @selected(old('arrival_mode', $emergency->arrival_mode) === 'Ambulans')>Ambulans</option>
                        <option value="Sendiri" @selected(old('arrival_mode', $emergency->arrival_mode) === 'Sendiri')>Sendiri</option>
                        <option value="Rujukan" @selected(old('arrival_mode', $emergency->arrival_mode) === 'Rujukan')>Rujukan</option>
                    </select>
                    @error('arrival_mode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Keluhan <span class="text-danger">*</span></label>
                    <textarea name="complaint" class="form-control @error('complaint') is-invalid @enderror" rows="3" required>{{ old('complaint', $emergency->complaint) }}</textarea>
                    @error('complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnosis</label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3">{{ old('diagnosis', $emergency->diagnosis) }}</textarea>
                    @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Tindakan</label>
                    <textarea name="action_taken" class="form-control @error('action_taken') is-invalid @enderror" rows="3">{{ old('action_taken', $emergency->action_taken) }}</textarea>
                    @error('action_taken') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="waiting" @selected(old('status', $emergency->status) === 'waiting')>Waiting</option>
                        <option value="in_treatment" @selected(old('status', $emergency->status) === 'in_treatment')>In Treatment</option>
                        <option value="completed" @selected(old('status', $emergency->status) === 'completed')>Completed</option>
                        <option value="referred" @selected(old('status', $emergency->status) === 'referred')>Referred</option>
                        <option value="deceased" @selected(old('status', $emergency->status) === 'deceased')>Deceased</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pulang</label>
                    <input type="datetime-local" name="discharge_date" class="form-control @error('discharge_date') is-invalid @enderror" value="{{ old('discharge_date', $emergency->discharge_date ? $emergency->discharge_date->format('Y-m-d\TH:i') : '') }}">
                    @error('discharge_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $emergency->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('emergencies.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
