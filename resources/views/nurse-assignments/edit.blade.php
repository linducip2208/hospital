@extends('layouts.admin')

@section('title', 'Edit Tugas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Tugas</h1>
    <a href="{{ route('nurse-assignments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('nurse-assignments.update', $nurseAssignment) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $nurseAssignment->patient_id) == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Petugas <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Pilih Petugas</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $nurseAssignment->user_id) == $user->id)>{{ $user->name }} ({{ $user->role === 'midwife' ? 'Bidan' : 'Perawat' }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Tugas <span class="text-danger">*</span></label>
                    <select name="assignment_type" class="form-select @error('assignment_type') is-invalid @enderror" required>
                        <option value="">Pilih Tipe</option>
                        <option value="nurse" @selected(old('assignment_type', $nurseAssignment->assignment_type) === 'nurse')>Perawat</option>
                        <option value="midwife" @selected(old('assignment_type', $nurseAssignment->assignment_type) === 'midwife')>Bidan</option>
                    </select>
                    @error('assignment_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Shift</label>
                    <select name="shift" class="form-select @error('shift') is-invalid @enderror">
                        <option value="pagi" @selected(old('shift', $nurseAssignment->shift) === 'pagi')>Pagi</option>
                        <option value="siang" @selected(old('shift', $nurseAssignment->shift) === 'siang')>Siang</option>
                        <option value="malam" @selected(old('shift', $nurseAssignment->shift) === 'malam')>Malam</option>
                    </select>
                    @error('shift') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" @selected(old('status', $nurseAssignment->status) === 'pending')>Pending</option>
                        <option value="in_progress" @selected(old('status', $nurseAssignment->status) === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status', $nurseAssignment->status) === 'completed')>Completed</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Tugas <span class="text-danger">*</span></label>
                    <textarea name="task_description" class="form-control @error('task_description') is-invalid @enderror" rows="3" required>{{ old('task_description', $nurseAssignment->task_description) }}</textarea>
                    @error('task_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $nurseAssignment->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('nurse-assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
