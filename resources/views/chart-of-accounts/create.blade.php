@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Akun</h1>
    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('chart-of-accounts.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kode Akun <span class="text-danger">*</span></label>
                    <input type="text" name="account_code" class="form-control @error('account_code') is-invalid @enderror" value="{{ old('account_code') }}" required>
                    @error('account_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Akun <span class="text-danger">*</span></label>
                    <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name') }}" required>
                    @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe Akun <span class="text-danger">*</span></label>
                    <select name="account_type" class="form-select @error('account_type') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="asset" @selected(old('account_type') === 'asset')>Aset</option>
                        <option value="liability" @selected(old('account_type') === 'liability')>Liabilitas</option>
                        <option value="equity" @selected(old('account_type') === 'equity')>Ekuitas</option>
                        <option value="revenue" @selected(old('account_type') === 'revenue')>Pendapatan</option>
                        <option value="expense" @selected(old('account_type') === 'expense')>Beban</option>
                    </select>
                    @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Saldo Normal <span class="text-danger">*</span></label>
                    <select name="normal_balance" class="form-select @error('normal_balance') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="debit" @selected(old('normal_balance') === 'debit')>Debit</option>
                        <option value="credit" @selected(old('normal_balance') === 'credit')>Kredit</option>
                    </select>
                    @error('normal_balance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Akun Induk</label>
                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">-- Tanpa Induk --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->account_code }} - {{ $parent->account_name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActive" @checked(old('is_active', true))>
                        <label class="form-check-label" for="isActive">Akun Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
