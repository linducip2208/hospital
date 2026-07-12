@extends('layouts.admin')

@section('title', 'Edit Cuti')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Cuti</h1>
    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('leaves.update', $leave) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pengguna <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $leave->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                    <select name="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="tahunan" @selected(old('leave_type', $leave->leave_type) === 'tahunan')>Tahunan</option>
                        <option value="sakit" @selected(old('leave_type', $leave->leave_type) === 'sakit')>Sakit</option>
                        <option value="melahirkan" @selected(old('leave_type', $leave->leave_type) === 'melahirkan')>Melahirkan</option>
                        <option value="alasan_penting" @selected(old('leave_type', $leave->leave_type) === 'alasan_penting')>Alasan Penting</option>
                        <option value="cuti_besar" @selected(old('leave_type', $leave->leave_type) === 'cuti_besar')>Cuti Besar</option>
                    </select>
                    @error('leave_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" @selected(old('status', $leave->status) === 'pending')>Pending</option>
                        <option value="approved" @selected(old('status', $leave->status) === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(old('status', $leave->status) === 'rejected')>Ditolak</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $leave->start_date?->format('Y-m-d')) }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $leave->end_date?->format('Y-m-d')) }}" required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Total Hari</label>
                    <input type="number" name="total_days" class="form-control @error('total_days') is-invalid @enderror" value="{{ old('total_days', $leave->total_days) }}" min="1">
                    @error('total_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Alasan</label>
                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3">{{ old('reason', $leave->reason) }}</textarea>
                    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $leave->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
