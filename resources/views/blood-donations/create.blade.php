@extends('layouts.admin')

@section('title', 'Donor Darah Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Donor Darah Baru</h1>
    <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('blood-donations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Donor <span class="text-danger">*</span></label>
                    <input type="text" name="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name') }}" required>
                    @error('donor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah <span class="text-danger">*</span></label>
                    <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror" required>
                        <option value="">Pilih Golongan Darah</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                            <option value="{{ $bt }}" @selected(old('blood_type') === $bt)>{{ $bt }}</option>
                        @endforeach
                    </select>
                    @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Donor <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror" value="{{ old('donation_date') }}" required>
                    <small class="text-muted">Expiry: +42 hari dari tanggal donor</small>
                    @error('donation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Volume (ml)</label>
                    <input type="number" name="quantity_ml" class="form-control @error('quantity_ml') is-invalid @enderror" value="{{ old('quantity_ml', 350) }}" min="1">
                    @error('quantity_ml') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status') === 'available')>Available</option>
                        <option value="used" @selected(old('status') === 'used')>Used</option>
                        <option value="expired" @selected(old('status') === 'expired')>Expired</option>
                        <option value="discarded" @selected(old('status') === 'discarded')>Discarded</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
