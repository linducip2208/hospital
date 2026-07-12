@extends('layouts.admin')

@section('title', 'Edit Tanda Vital')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Tanda Vital</h1>
    <a href="{{ route('vital-signs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('vital-signs.update', $vitalSignsRecord) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $vitalSignsRecord->patient_id) == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Perawat</label>
                    <select name="nurse_id" class="form-select @error('nurse_id') is-invalid @enderror">
                        <option value="">-- Pilih Perawat --</option>
                        @foreach($nurses as $nurse)
                            <option value="{{ $nurse->id }}" @selected(old('nurse_id', $vitalSignsRecord->nurse_id) == $nurse->id)>{{ $nurse->name }}</option>
                        @endforeach
                    </select>
                    @error('nurse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Pencatatan <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="recorded_at" class="form-control @error('recorded_at') is-invalid @enderror" value="{{ old('recorded_at', $vitalSignsRecord->recorded_at?->format('Y-m-d\TH:i')) }}" required>
                    @error('recorded_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Suhu Tubuh (°C)</label>
                    <input type="number" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature', $vitalSignsRecord->temperature) }}" step="0.1" min="30" max="45" placeholder="36.5">
                    @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Sistolik</label>
                    <input type="number" name="blood_pressure_systolic" class="form-control @error('blood_pressure_systolic') is-invalid @enderror" value="{{ old('blood_pressure_systolic', $vitalSignsRecord->blood_pressure_systolic) }}" placeholder="120">
                    @error('blood_pressure_systolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Diastolik</label>
                    <input type="number" name="blood_pressure_diastolic" class="form-control @error('blood_pressure_diastolic') is-invalid @enderror" value="{{ old('blood_pressure_diastolic', $vitalSignsRecord->blood_pressure_diastolic) }}" placeholder="80">
                    @error('blood_pressure_diastolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nadi (bpm)</label>
                    <input type="number" name="heart_rate" class="form-control @error('heart_rate') is-invalid @enderror" value="{{ old('heart_rate', $vitalSignsRecord->heart_rate) }}" placeholder="80">
                    @error('heart_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Respirasi (x/mnt)</label>
                    <input type="number" name="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" value="{{ old('respiratory_rate', $vitalSignsRecord->respiratory_rate) }}" placeholder="16">
                    @error('respiratory_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">SpO₂ (%)</label>
                    <input type="number" name="oxygen_saturation" class="form-control @error('oxygen_saturation') is-invalid @enderror" value="{{ old('oxygen_saturation', $vitalSignsRecord->oxygen_saturation) }}" min="0" max="100" placeholder="98">
                    @error('oxygen_saturation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gula Darah (mg/dL)</label>
                    <input type="number" name="blood_sugar" class="form-control @error('blood_sugar') is-invalid @enderror" value="{{ old('blood_sugar', $vitalSignsRecord->blood_sugar) }}" step="0.1" placeholder="100">
                    @error('blood_sugar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $vitalSignsRecord->weight) }}" step="0.1" placeholder="60">
                    @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" name="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height', $vitalSignsRecord->height) }}" step="0.1" placeholder="165">
                    @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Skala Nyeri (0-10)</label>
                    <input type="number" name="pain_level" class="form-control @error('pain_level') is-invalid @enderror" value="{{ old('pain_level', $vitalSignsRecord->pain_level) }}" min="0" max="10" placeholder="0">
                    @error('pain_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $vitalSignsRecord->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('vital-signs.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
