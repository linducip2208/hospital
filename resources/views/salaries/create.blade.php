@extends('layouts.admin')

@section('title', 'Tambah Penggajian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Penggajian</h1>
    <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('salaries.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pegawai <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required id="employeeSelect">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" data-user-id="{{ $emp->user_id }}" data-salary="{{ $emp->base_salary }}" @selected(old('employee_id') == $emp->id)>{{ $emp->user->name ?? '-' }} ({{ $emp->employee_code ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <input type="hidden" name="user_id" id="userIdInput" value="{{ old('user_id') }}">
                <div class="col-md-2">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="period_month" class="form-select @error('period_month') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(old('period_month', date('n')) == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endforeach
                    </select>
                    @error('period_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select name="period_year" class="form-select @error('period_year') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" @selected(old('period_year', date('Y')) == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                    @error('period_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="base_salary" id="baseSalaryInput" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary') }}" min="0" step="0" required>
                    </div>
                    @error('base_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jam Lembur</label>
                    <input type="number" name="overtime_hours" class="form-control @error('overtime_hours') is-invalid @enderror" value="{{ old('overtime_hours') }}" min="0" step="0.5">
                    @error('overtime_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Upah Lembur</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="overtime_pay" class="form-control @error('overtime_pay') is-invalid @enderror" value="{{ old('overtime_pay') }}" min="0" step="0">
                    </div>
                    @error('overtime_pay') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bonus</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" value="{{ old('bonus') }}" min="0" step="0">
                    </div>
                    @error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Potongan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="deduction" class="form-control @error('deduction') is-invalid @enderror" value="{{ old('deduction') }}" min="0" step="0">
                    </div>
                    @error('deduction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Keterangan Potongan</label>
                    <input type="text" name="deduction_note" class="form-control @error('deduction_note') is-invalid @enderror" value="{{ old('deduction_note') }}">
                    @error('deduction_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('employeeSelect').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var userId = selectedOption.getAttribute('data-user-id');
        var baseSalary = selectedOption.getAttribute('data-salary');
        document.getElementById('userIdInput').value = userId || '';
        document.getElementById('baseSalaryInput').value = baseSalary || '';
    });
</script>
@endpush
