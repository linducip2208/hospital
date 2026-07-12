@extends('layouts.admin')

@section('title', 'Pemeriksaan Radiologi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Pemeriksaan Radiologi Baru</h1>
    <a href="{{ route('radiologies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('radiologies.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }} ({{ $patient->medical_record_number ?? $patient->id }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter <span class="text-danger">*</span></label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Pemeriksaan <span class="text-danger">*</span></label>
                    <select name="examination_name" class="form-select @error('examination_name') is-invalid @enderror" required>
                        <option value="">Pilih Pemeriksaan</option>
                        <option value="Thorax" @selected(old('examination_name') === 'Thorax')>Thorax</option>
                        <option value="Abdomen" @selected(old('examination_name') === 'Abdomen')>Abdomen</option>
                        <option value="CT Scan Kepala" @selected(old('examination_name') === 'CT Scan Kepala')>CT Scan Kepala</option>
                        <option value="CT Scan Abdomen" @selected(old('examination_name') === 'CT Scan Abdomen')>CT Scan Abdomen</option>
                        <option value="MRI" @selected(old('examination_name') === 'MRI')>MRI</option>
                        <option value="USG Abdomen" @selected(old('examination_name') === 'USG Abdomen')>USG Abdomen</option>
                        <option value="USG Kehamilan" @selected(old('examination_name') === 'USG Kehamilan')>USG Kehamilan</option>
                        <option value="Mamografi" @selected(old('examination_name') === 'Mamografi')>Mamografi</option>
                        <option value="Bone Survey" @selected(old('examination_name') === 'Bone Survey')>Bone Survey</option>
                        <option value="Lainnya" @selected(old('examination_name') === 'Lainnya')>Lainnya</option>
                    </select>
                    @error('examination_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bagian Tubuh</label>
                    <input type="text" name="body_part" class="form-control @error('body_part') is-invalid @enderror" value="{{ old('body_part') }}" placeholder="e.g. Thorax, Abdomen">
                    @error('body_part') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('radiologies.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
