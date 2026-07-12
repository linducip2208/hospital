@extends('layouts.admin')

@section('title', 'Tambah Aset')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Aset</h1>
    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('assets.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kode Aset <span class="text-danger">*</span></label>
                    <input type="text" name="asset_code" class="form-control @error('asset_code') is-invalid @enderror" value="{{ old('asset_code') }}" required>
                    @error('asset_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Aset <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="medical" @selected(old('category') === 'medical')>Medis</option>
                        <option value="IT" @selected(old('category') === 'IT')>IT</option>
                        <option value="furniture" @selected(old('category') === 'furniture')>Furniture</option>
                        <option value="vehicle" @selected(old('category') === 'vehicle')>Kendaraan</option>
                        <option value="building" @selected(old('category') === 'building')>Bangunan</option>
                        <option value="other" @selected(old('category') === 'other')>Lainnya</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <label class="form-label">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}">
                    @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" step="0.01" min="0" placeholder="0">
                    @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror" value="{{ old('supplier') }}">
                    @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number') }}">
                    @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Garansi Sampai</label>
                    <input type="date" name="warranty_expiry" class="form-control @error('warranty_expiry') is-invalid @enderror" value="{{ old('warranty_expiry') }}">
                    @error('warranty_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kondisi</label>
                    <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="good" @selected(old('condition') === 'good')>Baik</option>
                        <option value="fair" @selected(old('condition') === 'fair')>Cukup</option>
                        <option value="poor" @selected(old('condition') === 'poor')>Buruk</option>
                        <option value="damaged" @selected(old('condition') === 'damaged')>Rusak</option>
                    </select>
                    @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="active" @selected(old('status') === 'active')>Aktif</option>
                        <option value="maintenance" @selected(old('status') === 'maintenance')>Perbaikan</option>
                        <option value="disposed" @selected(old('status') === 'disposed')>Dihapus</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
