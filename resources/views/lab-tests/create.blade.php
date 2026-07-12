@extends('layouts.admin')

@section('title', 'Pemeriksaan Lab Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Pemeriksaan Lab Baru</h1>
    <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('lab-tests.store') }}" method="POST">
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
                    <label class="form-label">Nama Pemeriksaan <span class="text-danger">*</span></label>
                    <input type="text" name="test_name" class="form-control @error('test_name') is-invalid @enderror" value="{{ old('test_name') }}" required>
                    @error('test_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Pemeriksaan <span class="text-danger">*</span></label>
                    <select name="test_type" class="form-select @error('test_type') is-invalid @enderror" required>
                        <option value="">Pilih Tipe</option>
                        <option value="Hematologi" @selected(old('test_type') === 'Hematologi')>Hematologi</option>
                        <option value="Kimia Darah" @selected(old('test_type') === 'Kimia Darah')>Kimia Darah</option>
                        <option value="Urinalisis" @selected(old('test_type') === 'Urinalisis')>Urinalisis</option>
                        <option value="Serologi" @selected(old('test_type') === 'Serologi')>Serologi</option>
                        <option value="Mikrobiologi" @selected(old('test_type') === 'Mikrobiologi')>Mikrobiologi</option>
                        <option value="Imunologi" @selected(old('test_type') === 'Imunologi')>Imunologi</option>
                        <option value="Lainnya" @selected(old('test_type') === 'Lainnya')>Lainnya</option>
                    </select>
                    @error('test_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Sampel <span class="text-danger">*</span></label>
                    <select name="sample_type" class="form-select @error('sample_type') is-invalid @enderror" required>
                        <option value="">Pilih Jenis Sampel</option>
                        <option value="Darah" @selected(old('sample_type') === 'Darah')>Darah</option>
                        <option value="Urine" @selected(old('sample_type') === 'Urine')>Urine</option>
                        <option value="Swab" @selected(old('sample_type') === 'Swab')>Swab</option>
                        <option value="Feses" @selected(old('sample_type') === 'Feses')>Feses</option>
                        <option value="Lainnya" @selected(old('sample_type') === 'Lainnya')>Lainnya</option>
                    </select>
                    @error('sample_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
