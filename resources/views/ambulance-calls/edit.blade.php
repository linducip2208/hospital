@extends('layouts.admin')

@section('title', 'Edit Panggilan Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Panggilan Ambulans</h1>
    <a href="{{ route('ambulance-calls.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('ambulance-calls.update', $ambulanceCall) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Ambulans <span class="text-danger">*</span></label>
                    <select name="ambulance_id" class="form-select @error('ambulance_id') is-invalid @enderror" required>
                        <option value="">Pilih Ambulans</option>
                        @foreach($ambulances as $ambulance)
                            <option value="{{ $ambulance->id }}" @selected(old('ambulance_id', $ambulanceCall->ambulance_id) == $ambulance->id)>{{ $ambulance->vehicle_number }} ({{ $ambulance->driver_name ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('ambulance_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                    <input type="text" name="patient_name" class="form-control @error('patient_name') is-invalid @enderror" value="{{ old('patient_name', $ambulanceCall->patient_name) }}" required>
                    @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi Jemput <span class="text-danger">*</span></label>
                    <input type="text" name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror" value="{{ old('pickup_location', $ambulanceCall->pickup_location) }}" required>
                    @error('pickup_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tujuan</label>
                    <input type="text" name="destination" class="form-control @error('destination') is-invalid @enderror" value="{{ old('destination', $ambulanceCall->destination) }}">
                    @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Panggil <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="call_date" class="form-control @error('call_date') is-invalid @enderror" value="{{ old('call_date', $ambulanceCall->call_date ? $ambulanceCall->call_date->format('Y-m-d\TH:i') : '') }}" required>
                    @error('call_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" @selected(old('status', $ambulanceCall->status) === 'pending')>Pending</option>
                        <option value="dispatched" @selected(old('status', $ambulanceCall->status) === 'dispatched')>Dispatched</option>
                        <option value="en_route" @selected(old('status', $ambulanceCall->status) === 'en_route')>En Route</option>
                        <option value="arrived" @selected(old('status', $ambulanceCall->status) === 'arrived')>Arrived</option>
                        <option value="completed" @selected(old('status', $ambulanceCall->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $ambulanceCall->status) === 'cancelled')>Cancelled</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $ambulanceCall->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('ambulance-calls.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
