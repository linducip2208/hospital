@extends('layouts.admin')

@section('title', 'Tambah Purchase Order')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Purchase Order</h1>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            <span>Item PO dikelola setelah PO disimpan, melalui halaman detail.</span>
        </div>

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Order <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    @error('order_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Diharapkan</label>
                    <input type="date" name="expected_date" class="form-control @error('expected_date') is-invalid @enderror" value="{{ old('expected_date') }}">
                    @error('expected_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Departemen</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pajak</label>
                    <input type="number" name="tax" class="form-control @error('tax') is-invalid @enderror" value="{{ old('tax', 0) }}" step="0.01" min="0" placeholder="0">
                    @error('tax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name') }}" required>
                    @error('supplier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon Supplier</label>
                    <input type="text" name="supplier_phone" class="form-control @error('supplier_phone') is-invalid @enderror" value="{{ old('supplier_phone') }}">
                    @error('supplier_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
