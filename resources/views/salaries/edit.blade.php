@extends('layouts.admin')

@section('title', 'Edit Penggajian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Penggajian</h1>
    <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

@php $isLocked = in_array($salary->status, ['approved', 'paid']); @endphp

<div class="card shadow-sm">
    <div class="card-body">
        @if($isLocked)
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle"></i> Penggajian sudah {{ $salary->status === 'paid' ? 'dibayar' : 'disetujui' }}. Beberapa field tidak dapat diubah.
        </div>
        @endif
        <form action="{{ route('salaries.update', $salary) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pegawai <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" data-user-id="{{ $emp->user_id }}" data-salary="{{ $emp->base_salary }}" @selected(old('employee_id', $salary->employee_id) == $emp->id)>{{ $emp->user->name ?? '-' }} ({{ $emp->employee_code ?? '-' }})</option>
                        @endforeach
                    </select>
                    @if($isLocked) <input type="hidden" name="employee_id" value="{{ $salary->employee_id }}"> @endif
                    @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <input type="hidden" name="user_id" value="{{ old('user_id', $salary->user_id) }}" {{ $isLocked ? '' : '' }}>
                <div class="col-md-2">
                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select name="period_month" class="form-select @error('period_month') is-invalid @enderror" required {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">-- Pilih --</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(old('period_month', $salary->period_month) == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endforeach
                    </select>
                    @if($isLocked) <input type="hidden" name="period_month" value="{{ $salary->period_month }}"> @endif
                    @error('period_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select name="period_year" class="form-select @error('period_year') is-invalid @enderror" required {{ $isLocked ? 'disabled' : '' }}>
                        <option value="">-- Pilih --</option>
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{ $y }}" @selected(old('period_year', $salary->period_year) == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                    @if($isLocked) <input type="hidden" name="period_year" value="{{ $salary->period_year }}"> @endif
                    @error('period_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $salary->base_salary) }}" min="0" step="0" required {{ $isLocked ? 'readonly' : '' }}>
                    </div>
                    @error('base_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jam Lembur</label>
                    <input type="number" name="overtime_hours" class="form-control @error('overtime_hours') is-invalid @enderror" value="{{ old('overtime_hours', $salary->overtime_hours) }}" min="0" step="0.5" {{ $isLocked ? 'readonly' : '' }}>
                    @error('overtime_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Upah Lembur</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="overtime_pay" class="form-control @error('overtime_pay') is-invalid @enderror" value="{{ old('overtime_pay', $salary->overtime_pay) }}" min="0" step="0" {{ $isLocked ? 'readonly' : '' }}>
                    </div>
                    @error('overtime_pay') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bonus</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="bonus" class="form-control @error('bonus') is-invalid @enderror" value="{{ old('bonus', $salary->bonus) }}" min="0" step="0" {{ $isLocked ? 'readonly' : '' }}>
                    </div>
                    @error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Potongan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="deduction" class="form-control @error('deduction') is-invalid @enderror" value="{{ old('deduction', $salary->deduction) }}" min="0" step="0" {{ $isLocked ? 'readonly' : '' }}>
                    </div>
                    @error('deduction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Keterangan Potongan</label>
                    <input type="text" name="deduction_note" class="form-control @error('deduction_note') is-invalid @enderror" value="{{ old('deduction_note', $salary->deduction_note) }}" {{ $isLocked ? 'readonly' : '' }}>
                    @error('deduction_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $salary->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
