@extends('layouts.admin')

@section('title', 'Tambah Postnatal Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Postnatal Record</h1>
    <a href="{{ route('postnatal-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('postnatal-records.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bidan</label>
                    <select name="midwife_id" class="form-select @error('midwife_id') is-invalid @enderror">
                        <option value="">Pilih Bidan</option>
                        @foreach($midwives as $midwife)
                            <option value="{{ $midwife->id }}" @selected(old('midwife_id') == $midwife->id)>{{ $midwife->name }}</option>
                        @endforeach
                    </select>
                    @error('midwife_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data Persalinan</label>
                    <select name="maternity_id" class="form-select @error('maternity_id') is-invalid @enderror">
                        <option value="">Pilih</option>
                        @foreach($maternities as $maternity)
                            <option value="{{ $maternity->id }}" @selected(old('maternity_id') == $maternity->id)>{{ $maternity->patient->name ?? $maternity->id }}</option>
                        @endforeach
                    </select>
                    @error('maternity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                    <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" value="{{ old('visit_date') }}" required>
                    @error('visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Involusi Uterus</label>
                    <select name="uterine_involution" class="form-select @error('uterine_involution') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="normal" @selected(old('uterine_involution') === 'normal')>Normal</option>
                        <option value="sub-involution" @selected(old('uterine_involution') === 'sub-involution')>Sub-Involusi</option>
                    </select>
                    @error('uterine_involution') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lochia</label>
                    <select name="lochia" class="form-select @error('lochia') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="normal" @selected(old('lochia') === 'normal')>Normal</option>
                        <option value="abnormal" @selected(old('lochia') === 'abnormal')>Abnormal</option>
                    </select>
                    @error('lochia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Luka Perineum</label>
                    <select name="perineum_wound" class="form-select @error('perineum_wound') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="healing" @selected(old('perineum_wound') === 'healing')>Menyembuh</option>
                        <option value="infected" @selected(old('perineum_wound') === 'infected')>Infeksi</option>
                    </select>
                    @error('perineum_wound') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Menyusui</label>
                    <select name="breastfeeding" class="form-select @error('breastfeeding') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="good" @selected(old('breastfeeding') === 'good')>Baik</option>
                        <option value="fair" @selected(old('breastfeeding') === 'fair')>Cukup</option>
                        <option value="poor" @selected(old('breastfeeding') === 'poor')>Kurang</option>
                    </select>
                    @error('breastfeeding') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Berat Bayi (kg)</label>
                    <input type="number" name="baby_weight" class="form-control @error('baby_weight') is-invalid @enderror" value="{{ old('baby_weight') }}" min="0" step="0.01">
                    @error('baby_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kondisi Bayi</label>
                    <input type="text" name="baby_condition" class="form-control @error('baby_condition') is-invalid @enderror" value="{{ old('baby_condition') }}">
                    @error('baby_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kunjungan Berikutnya</label>
                    <input type="date" name="next_visit_date" class="form-control @error('next_visit_date') is-invalid @enderror" value="{{ old('next_visit_date') }}">
                    @error('next_visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Keluarga Berencana</label>
                    <input type="text" name="family_planning" class="form-control @error('family_planning') is-invalid @enderror" value="{{ old('family_planning') }}" placeholder="e.g. KB Suntik 3 Bulan">
                    @error('family_planning') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
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
                <a href="{{ route('postnatal-records.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
