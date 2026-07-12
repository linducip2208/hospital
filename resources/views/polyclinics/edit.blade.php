@extends('layouts.admin')

@section('title', 'Edit Poli')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Poli</h1>
    <a href="{{ route('polyclinics.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('polyclinics.update', $polyclinic) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Poli <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $polyclinic->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror" value="{{ old('code', $polyclinic->code) }}" required style="text-transform:uppercase">
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lantai</label>
                    <input type="text" name="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor', $polyclinic->floor) }}" placeholder="e.g. Lantai 1">
                    @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $polyclinic->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActive" @checked(old('is_active', $polyclinic->is_active))>
                        <label class="form-check-label" for="isActive">Poliklinik Aktif</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $polyclinic->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @if(isset($doctors) && $doctors->count())
                <div class="col-12">
                    <label class="form-label">Dokter Poli</label>
                    <div class="card">
                        <div class="card-body" style="max-height:200px;overflow-y:auto">
                            @foreach($doctors as $doctor)
                            <div class="form-check">
                                <input type="checkbox" name="doctor_ids[]" class="form-check-input" value="{{ $doctor->id }}" id="doctor{{ $doctor->id }}"
                                    @checked(is_array(old('doctor_ids')) ? in_array($doctor->id, old('doctor_ids')) : $polyclinic->doctors->contains($doctor->id))>
                                <label class="form-check-label" for="doctor{{ $doctor->id }}">
                                    {{ $doctor->name }} <small class="text-muted">({{ $doctor->specialization ?? '-' }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('doctor_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                @endif
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('polyclinics.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
