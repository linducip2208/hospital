@extends('layouts.admin')

@section('title', 'Edit Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Ambulans</h1>
    <a href="{{ route('ambulances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('ambulances.update', $ambulance) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">No. Kendaraan <span class="text-danger">*</span></label>
                    <input type="text" name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" value="{{ old('vehicle_number', $ambulance->vehicle_number) }}" required>
                    @error('vehicle_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $ambulance->model) }}">
                    @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">Pilih Tipe</option>
                        <option value="Basic" @selected(old('type', $ambulance->type) === 'Basic')>Basic</option>
                        <option value="Advanced" @selected(old('type', $ambulance->type) === 'Advanced')>Advanced</option>
                        <option value="Mobile ICU" @selected(old('type', $ambulance->type) === 'Mobile ICU')>Mobile ICU</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status', $ambulance->status) === 'available')>Available</option>
                        <option value="on_duty" @selected(old('status', $ambulance->status) === 'on_duty')>On Duty</option>
                        <option value="maintenance" @selected(old('status', $ambulance->status) === 'maintenance')>Maintenance</option>
                        <option value="out_of_service" @selected(old('status', $ambulance->status) === 'out_of_service')>Out of Service</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Supir</label>
                    <input type="text" name="driver_name" class="form-control @error('driver_name') is-invalid @enderror" value="{{ old('driver_name', $ambulance->driver_name) }}">
                    @error('driver_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telp Supir</label>
                    <input type="text" name="driver_phone" class="form-control @error('driver_phone') is-invalid @enderror" value="{{ old('driver_phone', $ambulance->driver_phone) }}">
                    @error('driver_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $ambulance->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('ambulances.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
