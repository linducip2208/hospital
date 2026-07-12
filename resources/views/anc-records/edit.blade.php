@extends('layouts.admin')

@section('title', 'Edit ANC Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit ANC Record</h1>
    <a href="{{ route('anc-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('anc-records.update', $ancRecord) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $ancRecord->patient_id) == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bidan</label>
                    <select name="midwife_id" class="form-select @error('midwife_id') is-invalid @enderror">
                        <option value="">Pilih Bidan</option>
                        @foreach($midwives as $midwife)
                            <option value="{{ $midwife->id }}" @selected(old('midwife_id', $ancRecord->midwife_id) == $midwife->id)>{{ $midwife->name }}</option>
                        @endforeach
                    </select>
                    @error('midwife_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                    <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" value="{{ old('visit_date', $ancRecord->visit_date ? $ancRecord->visit_date->format('Y-m-d') : '') }}" required>
                    @error('visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Usia Kehamilan (minggu)</label>
                    <input type="number" name="gestational_age" class="form-control @error('gestational_age') is-invalid @enderror" value="{{ old('gestational_age', $ancRecord->gestational_age) }}" min="0" max="42" step="1">
                    @error('gestational_age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tinggi Fundus (cm)</label>
                    <input type="number" name="fundal_height" class="form-control @error('fundal_height') is-invalid @enderror" value="{{ old('fundal_height', $ancRecord->fundal_height) }}" min="0" step="0.1">
                    @error('fundal_height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Presentasi Janin</label>
                    <select name="fetal_presentation" class="form-select @error('fetal_presentation') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="cephalic" @selected(old('fetal_presentation', $ancRecord->fetal_presentation) === 'cephalic')>Cephalic</option>
                        <option value="breech" @selected(old('fetal_presentation', $ancRecord->fetal_presentation) === 'breech')>Breech</option>
                        <option value="transverse" @selected(old('fetal_presentation', $ancRecord->fetal_presentation) === 'transverse')>Transverse</option>
                    </select>
                    @error('fetal_presentation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Detak Jantung Janin (bpm)</label>
                    <input type="number" name="fetal_heart_rate" class="form-control @error('fetal_heart_rate') is-invalid @enderror" value="{{ old('fetal_heart_rate', $ancRecord->fetal_heart_rate) }}" min="0" step="1">
                    @error('fetal_heart_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Sistolik</label>
                    <input type="number" name="blood_pressure_systolic" class="form-control @error('blood_pressure_systolic') is-invalid @enderror" value="{{ old('blood_pressure_systolic', $ancRecord->blood_pressure_systolic) }}" min="0" step="1">
                    @error('blood_pressure_systolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Diastolik</label>
                    <input type="number" name="blood_pressure_diastolic" class="form-control @error('blood_pressure_diastolic') is-invalid @enderror" value="{{ old('blood_pressure_diastolic', $ancRecord->blood_pressure_diastolic) }}" min="0" step="1">
                    @error('blood_pressure_diastolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $ancRecord->weight) }}" min="0" step="0.1">
                    @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hemoglobin (g/dL)</label>
                    <input type="number" name="hemoglobin" class="form-control @error('hemoglobin') is-invalid @enderror" value="{{ old('hemoglobin', $ancRecord->hemoglobin) }}" min="0" step="0.1">
                    @error('hemoglobin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Protein Urine</label>
                    <select name="urine_protein" class="form-select @error('urine_protein') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="negative" @selected(old('urine_protein', $ancRecord->urine_protein) === 'negative')>Negatif</option>
                        <option value="+1" @selected(old('urine_protein', $ancRecord->urine_protein) === '+1')>+1</option>
                        <option value="+2" @selected(old('urine_protein', $ancRecord->urine_protein) === '+2')>+2</option>
                        <option value="+3" @selected(old('urine_protein', $ancRecord->urine_protein) === '+3')>+3</option>
                    </select>
                    @error('urine_protein') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Imunisasi TT</label>
                    <select name="tt_immunization" class="form-select @error('tt_immunization') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="TT1" @selected(old('tt_immunization', $ancRecord->tt_immunization) === 'TT1')>TT1</option>
                        <option value="TT2" @selected(old('tt_immunization', $ancRecord->tt_immunization) === 'TT2')>TT2</option>
                        <option value="TT3" @selected(old('tt_immunization', $ancRecord->tt_immunization) === 'TT3')>TT3</option>
                        <option value="TT4" @selected(old('tt_immunization', $ancRecord->tt_immunization) === 'TT4')>TT4</option>
                        <option value="TT5" @selected(old('tt_immunization', $ancRecord->tt_immunization) === 'TT5')>TT5</option>
                    </select>
                    @error('tt_immunization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Kunjungan Berikutnya</label>
                    <input type="date" name="next_visit_date" class="form-control @error('next_visit_date') is-invalid @enderror" value="{{ old('next_visit_date', $ancRecord->next_visit_date ? $ancRecord->next_visit_date->format('Y-m-d') : '') }}">
                    @error('next_visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="iron_folate" class="form-check-input" value="1" id="ironFolate" @checked(old('iron_folate', $ancRecord->iron_folate) == 1)>
                        <label class="form-check-label" for="ironFolate">Suplemen Zat Besi & Asam Folat</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Komplikasi</label>
                    <textarea name="complications" class="form-control @error('complications') is-invalid @enderror" rows="3">{{ old('complications', $ancRecord->complications) }}</textarea>
                    @error('complications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $ancRecord->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('anc-records.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
