@extends('layouts.admin')

@section('title', 'Tambah Pemberian Obat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Pemberian Obat</h1>
    <a href="{{ route('medication-administrations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('medication-administrations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Perawat</label>
                    <select name="nurse_id" class="form-select @error('nurse_id') is-invalid @enderror">
                        <option value="">-- Pilih Perawat --</option>
                        @foreach($nurses as $nurse)
                            <option value="{{ $nurse->id }}" @selected(old('nurse_id', auth()->id()) == $nurse->id)>{{ $nurse->name }}</option>
                        @endforeach
                    </select>
                    @error('nurse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Obat</label>
                    <select name="drug_id" class="form-select @error('drug_id') is-invalid @enderror">
                        <option value="">-- Pilih Obat --</option>
                        @foreach($drugs as $drug)
                            <option value="{{ $drug->id }}" @selected(old('drug_id') == $drug->id)>{{ $drug->name }}</option>
                        @endforeach
                    </select>
                    @error('drug_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" name="drug_name" class="form-control @error('drug_name') is-invalid @enderror" value="{{ old('drug_name') }}" required>
                    @error('drug_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Dosis</label>
                    <input type="text" name="dosage" class="form-control @error('dosage') is-invalid @enderror" value="{{ old('dosage') }}" placeholder="500 mg">
                    @error('dosage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rute Pemberian</label>
                    <select name="route" class="form-select @error('route') is-invalid @enderror">
                        <option value="">-- Pilih Rute --</option>
                        @foreach(['Oral','IV','IM','SC','Topikal','Inhalasi','Lainnya'] as $r)
                            <option value="{{ $r }}" @selected(old('route') === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                    @error('route') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Pemberian <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="administered_at" class="form-control @error('administered_at') is-invalid @enderror" value="{{ old('administered_at') }}" required>
                    @error('administered_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('medication-administrations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
