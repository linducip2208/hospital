@extends('layouts.admin')

@section('title', 'Edit Pegawai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Pegawai</h1>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pengguna <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $employee->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kode Pegawai</label>
                    <input type="text" name="employee_code" class="form-control @error('employee_code') is-invalid @enderror" value="{{ old('employee_code', $employee->employee_code) }}" maxlength="20">
                    @error('employee_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $employee->position) }}">
                    @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $employee->department) }}">
                    @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror" value="{{ old('join_date', $employee->join_date?->format('Y-m-d')) }}">
                    @error('join_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gaji Pokok</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $employee->base_salary) }}" min="0" step="0">
                    </div>
                    @error('base_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Bank</label>
                    <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $employee->bank_name) }}">
                    @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. Rekening</label>
                    <input type="text" name="bank_account" class="form-control @error('bank_account') is-invalid @enderror" value="{{ old('bank_account', $employee->bank_account) }}" maxlength="50">
                    @error('bank_account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">BPJS TK</label>
                    <input type="text" name="bpjs_tk" class="form-control @error('bpjs_tk') is-invalid @enderror" value="{{ old('bpjs_tk', $employee->bpjs_tk) }}" maxlength="50">
                    @error('bpjs_tk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="tax_number" class="form-control @error('tax_number') is-invalid @enderror" value="{{ old('tax_number', $employee->tax_number) }}" maxlength="30">
                    @error('tax_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Kepegawaian</label>
                    <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="permanent" @selected(old('employment_status', $employee->employment_status) === 'permanent')>Tetap</option>
                        <option value="contract" @selected(old('employment_status', $employee->employment_status) === 'contract')>Kontrak</option>
                        <option value="probation" @selected(old('employment_status', $employee->employment_status) === 'probation')>Percobaan</option>
                        <option value="intern" @selected(old('employment_status', $employee->employment_status) === 'intern')>Magang</option>
                        <option value="resigned" @selected(old('employment_status', $employee->employment_status) === 'resigned')>Resign</option>
                    </select>
                    @error('employment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pendidikan</label>
                    <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" value="{{ old('education_level', $employee->education_level) }}">
                    @error('education_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kontak Darurat</label>
                    <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact', $employee->emergency_contact) }}" maxlength="30">
                    @error('emergency_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $employee->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
