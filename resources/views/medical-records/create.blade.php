@extends('layouts.admin')

@section('title', 'Tambah Rekam Medis')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Rekam Medis</h1>
    <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('medical-records.store') }}" method="POST">
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
                    <label class="form-label">Dokter <span class="text-danger">*</span></label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Appointment</label>
                    <select name="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror">
                        <option value="">-- Optional --</option>
                        @foreach($appointments as $appt)
                            <option value="{{ $appt->id }}" @selected(old('appointment_id') == $appt->id)>{{ $appt->patient->name ?? '-' }} - {{ $appt->appointment_date->format('d/m/Y') }}</option>
                        @endforeach
                    </select>
                    @error('appointment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3" required>{{ old('diagnosis') }}</textarea>
                    @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode ICD-10 <small class="text-muted">(untuk klaim BPJS/INA-CBG)</small></label>
                    <input type="text" name="icd10_code" id="icd10Code" class="form-control" value="{{ old('icd10_code') }}" placeholder="mis. E11" autocomplete="off" list="icd10List">
                    <datalist id="icd10List"></datalist>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Diagnosis (ICD-10)</label>
                    <input type="text" name="icd10_name" id="icd10Name" class="form-control" value="{{ old('icd10_name') }}" placeholder="otomatis terisi saat pilih kode">
                </div>
                <div class="col-12">
                    <label class="form-label">Tindakan</label>
                    <textarea name="action" class="form-control @error('action') is-invalid @enderror" rows="3">{{ old('action') }}</textarea>
                    @error('action') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Obat / Resep</label>
                    <textarea name="medicine" class="form-control @error('medicine') is-invalid @enderror" rows="3">{{ old('medicine') }}</textarea>
                    @error('medicine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Hasil Lab</label>
                    <textarea name="lab_results" class="form-control @error('lab_results') is-invalid @enderror" rows="3">{{ old('lab_results') }}</textarea>
                    @error('lab_results') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const codeInput = document.getElementById('icd10Code');
    const nameInput = document.getElementById('icd10Name');
    const list = document.getElementById('icd10List');
    if (!codeInput) return;
    let timer, map = {};
    async function search(q) {
        const res = await fetch('{{ route('clinical.icd10') }}?q=' + encodeURIComponent(q));
        const data = await res.json();
        list.innerHTML = '';
        map = {};
        data.forEach(d => {
            map[d.code] = d.name;
            const opt = document.createElement('option');
            opt.value = d.code;
            opt.label = d.name;
            list.appendChild(opt);
        });
    }
    codeInput.addEventListener('input', function () {
        clearTimeout(timer);
        const v = this.value.trim();
        if (map[v]) nameInput.value = map[v];
        timer = setTimeout(() => search(v), 250);
    });
    search('');
})();
</script>
@endpush
