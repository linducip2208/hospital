@extends('layouts.admin')

@section('title', 'Edit Operasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Operasi</h1>
    <a href="{{ route('surgeries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('surgeries.update', $surgery) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $surgery->patient_id) == $patient->id)>{{ $patient->name }} ({{ $patient->medical_record_number ?? $patient->id }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter</label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                        <option value="">Pilih Dokter (opsional)</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $surgery->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Operasi <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $surgery->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Operasi</label>
                    <input type="datetime-local" name="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" value="{{ old('scheduled_date', $surgery->scheduled_date ? $surgery->scheduled_date->format('Y-m-d\TH:i') : '') }}">
                    @error('scheduled_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="scheduled" @selected(old('status', $surgery->status) === 'scheduled')>Scheduled</option>
                        <option value="in_progress" @selected(old('status', $surgery->status) === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status', $surgery->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $surgery->status) === 'cancelled')>Cancelled</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $surgery->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $surgery->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('surgeries.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
