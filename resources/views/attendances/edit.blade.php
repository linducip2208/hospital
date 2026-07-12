@extends('layouts.admin')

@section('title', 'Edit Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Absensi</h1>
    <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('attendances.update', $attendance) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pengguna <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $attendance->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $attendance->date?->format('Y-m-d')) }}" required>
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="present" @selected(old('status', $attendance->status) === 'present')>Hadir</option>
                        <option value="late" @selected(old('status', $attendance->status) === 'late')>Terlambat</option>
                        <option value="sick" @selected(old('status', $attendance->status) === 'sick')>Sakit</option>
                        <option value="leave" @selected(old('status', $attendance->status) === 'leave')>Izin</option>
                        <option value="absent" @selected(old('status', $attendance->status) === 'absent')>Alfa</option>
                        <option value="half_day" @selected(old('status', $attendance->status) === 'half_day')>Setengah Hari</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jam Masuk</label>
                    <input type="time" name="check_in" class="form-control @error('check_in') is-invalid @enderror" value="{{ old('check_in', $attendance->check_in) }}">
                    @error('check_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jam Keluar</label>
                    <input type="time" name="check_out" class="form-control @error('check_out') is-invalid @enderror" value="{{ old('check_out', $attendance->check_out) }}">
                    @error('check_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $attendance->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
