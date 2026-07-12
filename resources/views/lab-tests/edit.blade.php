@extends('layouts.admin')

@section('title', 'Edit Pemeriksaan Lab')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Pemeriksaan Lab</h1>
    <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('lab-tests.update', $labTest) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $labTest->patient_id) == $patient->id)>{{ $patient->name }} ({{ $patient->medical_record_number ?? $patient->id }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter <span class="text-danger">*</span></label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $labTest->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Pemeriksaan <span class="text-danger">*</span></label>
                    <input type="text" name="test_name" class="form-control @error('test_name') is-invalid @enderror" value="{{ old('test_name', $labTest->test_name) }}" required>
                    @error('test_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Pemeriksaan <span class="text-danger">*</span></label>
                    <select name="test_type" class="form-select @error('test_type') is-invalid @enderror" required>
                        <option value="">Pilih Tipe</option>
                        <option value="Hematologi" @selected(old('test_type', $labTest->test_type) === 'Hematologi')>Hematologi</option>
                        <option value="Kimia Darah" @selected(old('test_type', $labTest->test_type) === 'Kimia Darah')>Kimia Darah</option>
                        <option value="Urinalisis" @selected(old('test_type', $labTest->test_type) === 'Urinalisis')>Urinalisis</option>
                        <option value="Serologi" @selected(old('test_type', $labTest->test_type) === 'Serologi')>Serologi</option>
                        <option value="Mikrobiologi" @selected(old('test_type', $labTest->test_type) === 'Mikrobiologi')>Mikrobiologi</option>
                        <option value="Imunologi" @selected(old('test_type', $labTest->test_type) === 'Imunologi')>Imunologi</option>
                        <option value="Lainnya" @selected(old('test_type', $labTest->test_type) === 'Lainnya')>Lainnya</option>
                    </select>
                    @error('test_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Sampel <span class="text-danger">*</span></label>
                    <select name="sample_type" class="form-select @error('sample_type') is-invalid @enderror" required>
                        <option value="">Pilih Jenis Sampel</option>
                        <option value="Darah" @selected(old('sample_type', $labTest->sample_type) === 'Darah')>Darah</option>
                        <option value="Urine" @selected(old('sample_type', $labTest->sample_type) === 'Urine')>Urine</option>
                        <option value="Swab" @selected(old('sample_type', $labTest->sample_type) === 'Swab')>Swab</option>
                        <option value="Feses" @selected(old('sample_type', $labTest->sample_type) === 'Feses')>Feses</option>
                        <option value="Lainnya" @selected(old('sample_type', $labTest->sample_type) === 'Lainnya')>Lainnya</option>
                    </select>
                    @error('sample_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="requested" @selected(old('status', $labTest->status) === 'requested')>Requested</option>
                        <option value="sample_collected" @selected(old('status', $labTest->status) === 'sample_collected')>Sample Collected</option>
                        <option value="in_progress" @selected(old('status', $labTest->status) === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status', $labTest->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $labTest->status) === 'cancelled')>Cancelled</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Hasil</label>
                    <textarea name="results" class="form-control @error('results') is-invalid @enderror" rows="5">{{ old('results', $labTest->results) }}</textarea>
                    @error('results') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Hasil</label>
                    <input type="datetime-local" name="result_date" class="form-control @error('result_date') is-invalid @enderror" value="{{ old('result_date', $labTest->result_date ? $labTest->result_date->format('Y-m-d\TH:i') : '') }}">
                    @error('result_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $labTest->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
