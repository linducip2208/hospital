@extends('layouts.admin')

@section('title', 'Edit Partograph')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Partograph</h1>
    <a href="{{ route('partographs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('partographs.update', $partograph) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Ibu Bersalin <span class="text-danger">*</span></label>
                    <select name="maternity_id" class="form-select @error('maternity_id') is-invalid @enderror" required>
                        <option value="">Pilih Ibu Bersalin</option>
                        @foreach($maternities as $maternity)
                            <option value="{{ $maternity->id }}" @selected(old('maternity_id', $partograph->maternity_id) == $maternity->id)>{{ $maternity->patient->name ?? $maternity->id }}</option>
                        @endforeach
                    </select>
                    @error('maternity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu Pencatatan <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="recorded_at" class="form-control @error('recorded_at') is-invalid @enderror" value="{{ old('recorded_at', $partograph->recorded_at ? $partograph->recorded_at->format('Y-m-d\TH:i') : '') }}" required>
                    @error('recorded_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pembukaan Serviks (cm)</label>
                    <input type="number" name="cervical_dilation" class="form-control @error('cervical_dilation') is-invalid @enderror" value="{{ old('cervical_dilation', $partograph->cervical_dilation) }}" min="0" max="10" step="0.1">
                    @error('cervical_dilation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Detak Jantung Janin (bpm)</label>
                    <input type="number" name="fetal_heart_rate" class="form-control @error('fetal_heart_rate') is-invalid @enderror" value="{{ old('fetal_heart_rate', $partograph->fetal_heart_rate) }}" min="0" step="1">
                    @error('fetal_heart_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kontraksi per 10 Menit</label>
                    <input type="number" name="contractions_per_10min" class="form-control @error('contractions_per_10min') is-invalid @enderror" value="{{ old('contractions_per_10min', $partograph->contractions_per_10min) }}" min="0" step="1">
                    @error('contractions_per_10min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cairan Amnion</label>
                    <select name="amniotic_fluid" class="form-select @error('amniotic_fluid') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="intact" @selected(old('amniotic_fluid', $partograph->amniotic_fluid) === 'intact')>Utuh</option>
                        <option value="ruptured" @selected(old('amniotic_fluid', $partograph->amniotic_fluid) === 'ruptured')>Pecah</option>
                        <option value="meconium" @selected(old('amniotic_fluid', $partograph->amniotic_fluid) === 'meconium')>Mekonium</option>
                    </select>
                    @error('amniotic_fluid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Moulding</label>
                    <select name="moulding" class="form-select @error('moulding') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="0" @selected(old('moulding', $partograph->moulding) === '0')>0</option>
                        <option value="+" @selected(old('moulding', $partograph->moulding) === '+')>+</option>
                        <option value="++" @selected(old('moulding', $partograph->moulding) === '++')>++</option>
                        <option value="+++" @selected(old('moulding', $partograph->moulding) === '+++')>+++</option>
                    </select>
                    @error('moulding') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Oksitosin (tetes/menit)</label>
                    <input type="text" name="oxytocin" class="form-control @error('oxytocin') is-invalid @enderror" value="{{ old('oxytocin', $partograph->oxytocin) }}" placeholder="e.g. 10">
                    @error('oxytocin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Sistolik</label>
                    <input type="number" name="blood_pressure_systolic" class="form-control @error('blood_pressure_systolic') is-invalid @enderror" value="{{ old('blood_pressure_systolic', $partograph->blood_pressure_systolic) }}" min="0" step="1">
                    @error('blood_pressure_systolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">TD Diastolik</label>
                    <input type="number" name="blood_pressure_diastolic" class="form-control @error('blood_pressure_diastolic') is-invalid @enderror" value="{{ old('blood_pressure_diastolic', $partograph->blood_pressure_diastolic) }}" min="0" step="1">
                    @error('blood_pressure_diastolic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nadi (bpm)</label>
                    <input type="number" name="pulse" class="form-control @error('pulse') is-invalid @enderror" value="{{ old('pulse', $partograph->pulse) }}" min="0" step="1">
                    @error('pulse') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Suhu (°C)</label>
                    <input type="number" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature', $partograph->temperature) }}" min="0" step="0.1" placeholder="e.g. 36.5">
                    @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Output Urine (ml)</label>
                    <input type="number" name="urine_output" class="form-control @error('urine_output') is-invalid @enderror" value="{{ old('urine_output', $partograph->urine_output) }}" min="0" step="1">
                    @error('urine_output') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $partograph->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('partographs.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
