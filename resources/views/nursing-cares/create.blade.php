@extends('layouts.admin')

@section('title', 'Tambah Asuhan Keperawatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Asuhan Keperawatan</h1>
    <a href="{{ route('nursing-cares.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('nursing-cares.store') }}" method="POST">
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
                <div class="col-md-4">
                    <label class="form-label">Tanggal Perawatan <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="care_date" class="form-control @error('care_date') is-invalid @enderror" value="{{ old('care_date') }}" required>
                    @error('care_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="">-- Pilih Status --</option>
                        <option value="ongoing" @selected(old('status') === 'ongoing')>Berjalan</option>
                        <option value="completed" @selected(old('status') === 'completed')>Selesai</option>
                        <option value="cancelled" @selected(old('status') === 'cancelled')>Dibatalkan</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">S - Subjective (Data Subjektif)</label>
                    <textarea name="subjective" class="form-control @error('subjective') is-invalid @enderror" rows="3" placeholder="Keluhan pasien, riwayat...">{{ old('subjective') }}</textarea>
                    @error('subjective') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">O - Objective (Data Objektif)</label>
                    <textarea name="objective" class="form-control @error('objective') is-invalid @enderror" rows="3" placeholder="Hasil pemeriksaan, tanda vital...">{{ old('objective') }}</textarea>
                    @error('objective') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">A - Assessment (Diagnosa Keperawatan)</label>
                    <textarea name="assessment" class="form-control @error('assessment') is-invalid @enderror" rows="3" placeholder="Analisa data, diagnosa keperawatan...">{{ old('assessment') }}</textarea>
                    @error('assessment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">P - Plan (Rencana Tindakan)</label>
                    <textarea name="plan" class="form-control @error('plan') is-invalid @enderror" rows="3" placeholder="Rencana intervensi keperawatan...">{{ old('plan') }}</textarea>
                    @error('plan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">I - Implementation (Implementasi)</label>
                    <textarea name="implementation" class="form-control @error('implementation') is-invalid @enderror" rows="3" placeholder="Tindakan yang dilakukan...">{{ old('implementation') }}</textarea>
                    @error('implementation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">E - Evaluation (Evaluasi)</label>
                    <textarea name="evaluation" class="form-control @error('evaluation') is-invalid @enderror" rows="3" placeholder="Hasil evaluasi tindakan...">{{ old('evaluation') }}</textarea>
                    @error('evaluation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('nursing-cares.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
