@extends('layouts.admin')

@section('title', 'Tambah Imunisasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Imunisasi</h1>
    <a href="{{ route('baby-immunizations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('baby-immunizations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Bayi / Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Bayi</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Data Persalinan</label>
                    <select name="maternity_id" class="form-select @error('maternity_id') is-invalid @enderror">
                        <option value="">Pilih</option>
                        @foreach($maternities as $maternity)
                            <option value="{{ $maternity->id }}" @selected(old('maternity_id') == $maternity->id)>{{ $maternity->patient->name ?? $maternity->id }}</option>
                        @endforeach
                    </select>
                    @error('maternity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Vaksin <span class="text-danger">*</span></label>
                    <input type="text" name="vaccine_name" class="form-control @error('vaccine_name') is-invalid @enderror" value="{{ old('vaccine_name') }}" required>
                    @error('vaccine_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dosis Ke-</label>
                    <input type="number" name="dose_number" class="form-control @error('dose_number') is-invalid @enderror" value="{{ old('dose_number') }}" min="1" step="1">
                    @error('dose_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Jadwal</label>
                    <input type="date" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" value="{{ old('scheduled_date') }}">
                    @error('scheduled_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pemberian</label>
                    <input type="date" name="administered_date" class="form-control @error('administered_date') is-invalid @enderror" value="{{ old('administered_date') }}">
                    @error('administered_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Diberikan Oleh</label>
                    <input type="text" name="administered_by" class="form-control @error('administered_by') is-invalid @enderror" value="{{ old('administered_by') }}">
                    @error('administered_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Batch</label>
                    <input type="text" name="batch_number" class="form-control @error('batch_number') is-invalid @enderror" value="{{ old('batch_number') }}">
                    @error('batch_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                        <option value="completed" @selected(old('status') === 'completed')>Selesai</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('baby-immunizations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
