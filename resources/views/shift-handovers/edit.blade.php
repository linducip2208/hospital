@extends('layouts.admin')

@section('title', 'Edit Serah Terima Shift')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Serah Terima Shift</h1>
    <a href="{{ route('shift-handovers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('shift-handovers.update', $shiftHandover) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Dari Perawat <span class="text-danger">*</span></label>
                    <select name="from_nurse_id" class="form-select @error('from_nurse_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Perawat --</option>
                        @foreach($nurses as $nurse)
                            <option value="{{ $nurse->id }}" @selected(old('from_nurse_id', $shiftHandover->from_nurse_id) == $nurse->id)>{{ $nurse->name }}</option>
                        @endforeach
                    </select>
                    @error('from_nurse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ke Perawat <span class="text-danger">*</span></label>
                    <select name="to_nurse_id" class="form-select @error('to_nurse_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Perawat --</option>
                        @foreach($nurses as $nurse)
                            <option value="{{ $nurse->id }}" @selected(old('to_nurse_id', $shiftHandover->to_nurse_id) == $nurse->id)>{{ $nurse->name }}</option>
                        @endforeach
                    </select>
                    @error('to_nurse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Shift <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="shift_date" class="form-control @error('shift_date') is-invalid @enderror" value="{{ old('shift_date', $shiftHandover->shift_date?->format('Y-m-d\TH:i')) }}" required>
                    @error('shift_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe Shift <span class="text-danger">*</span></label>
                    <select name="shift_type" class="form-select @error('shift_type') is-invalid @enderror" required>
                        <option value="">-- Pilih Shift --</option>
                        <option value="pagi" @selected(old('shift_type', $shiftHandover->shift_type) === 'pagi')>Pagi</option>
                        <option value="siang" @selected(old('shift_type', $shiftHandover->shift_type) === 'siang')>Siang</option>
                        <option value="malam" @selected(old('shift_type', $shiftHandover->shift_type) === 'malam')>Malam</option>
                    </select>
                    @error('shift_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Ringkasan Pasien</label>
                    <textarea name="patient_summary" class="form-control @error('patient_summary') is-invalid @enderror" rows="3" placeholder="Kondisi pasien, jumlah pasien, hal penting...">{{ old('patient_summary', $shiftHandover->patient_summary) }}</textarea>
                    @error('patient_summary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Tugas Tertunda</label>
                    <textarea name="tasks_pending" class="form-control @error('tasks_pending') is-invalid @enderror" rows="3" placeholder="Tugas yang belum selesai, perlu ditindaklanjuti...">{{ old('tasks_pending', $shiftHandover->tasks_pending) }}</textarea>
                    @error('tasks_pending') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Insiden</label>
                    <textarea name="incidents" class="form-control @error('incidents') is-invalid @enderror" rows="3" placeholder="Kejadian penting, insiden, perubahan kondisi...">{{ old('incidents', $shiftHandover->incidents) }}</textarea>
                    @error('incidents') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Status Peralatan</label>
                    <textarea name="equipment_status" class="form-control @error('equipment_status') is-invalid @enderror" rows="3" placeholder="Kondisi alat, perbaikan, kebutuhan...">{{ old('equipment_status', $shiftHandover->equipment_status) }}</textarea>
                    @error('equipment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $shiftHandover->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('shift-handovers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
