@extends('layouts.admin')

@section('title', 'Catat Persalinan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Catat Persalinan</h1>
    <a href="{{ route('maternities.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('maternities.store') }}" method="POST">
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
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="datetime-local" name="admission_date" class="form-control @error('admission_date') is-invalid @enderror" value="{{ old('admission_date') }}">
                    @error('admission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Metode Persalinan</label>
                    <select name="delivery_type" class="form-select @error('delivery_type') is-invalid @enderror">
                        <option value="">Pilih Metode</option>
                        <option value="normal" @selected(old('delivery_type') === 'normal')>Normal</option>
                        <option value="caesar" @selected(old('delivery_type') === 'caesar')>Caesar</option>
                        <option value="vacuum" @selected(old('delivery_type') === 'vacuum')>Vacuum</option>
                        <option value="forceps" @selected(old('delivery_type') === 'forceps')>Forceps</option>
                    </select>
                    @error('delivery_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Persalinan</label>
                    <input type="datetime-local" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date') }}">
                    @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="admitted" @selected(old('status') === 'admitted')>Admitted</option>
                        <option value="in_labor" @selected(old('status') === 'in_labor')>In Labor</option>
                        <option value="delivered" @selected(old('status') === 'delivered')>Delivered</option>
                        <option value="postpartum" @selected(old('status') === 'postpartum')>Postpartum</option>
                        <option value="discharged" @selected(old('status') === 'discharged')>Discharged</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Data Bayi</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin Bayi</label>
                    <select name="baby_gender" class="form-select @error('baby_gender') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" @selected(old('baby_gender') === 'Laki-laki')>Laki-laki</option>
                        <option value="Perempuan" @selected(old('baby_gender') === 'Perempuan')>Perempuan</option>
                    </select>
                    @error('baby_gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Berat Bayi (gram)</label>
                    <input type="number" name="baby_weight" class="form-control @error('baby_weight') is-invalid @enderror" value="{{ old('baby_weight') }}" min="0" step="1" placeholder="e.g. 3200">
                    @error('baby_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Panjang Bayi (cm)</label>
                    <input type="number" name="baby_length" class="form-control @error('baby_length') is-invalid @enderror" value="{{ old('baby_length') }}" min="0" step="0.1" placeholder="e.g. 50">
                    @error('baby_length') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Bayi</label>
                    <input type="text" name="baby_name" class="form-control @error('baby_name') is-invalid @enderror" value="{{ old('baby_name') }}">
                    @error('baby_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Komplikasi</label>
                    <textarea name="complications" class="form-control @error('complications') is-invalid @enderror" rows="3">{{ old('complications') }}</textarea>
                    @error('complications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('maternities.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
