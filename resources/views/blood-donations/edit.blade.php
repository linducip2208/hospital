@extends('layouts.admin')

@section('title', 'Edit Donor Darah')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Donor Darah</h1>
    <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('blood-donations.update', $bloodDonation) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Donor <span class="text-danger">*</span></label>
                    <input type="text" name="donor_name" class="form-control @error('donor_name') is-invalid @enderror" value="{{ old('donor_name', $bloodDonation->donor_name) }}" required>
                    @error('donor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah <span class="text-danger">*</span></label>
                    <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror" required>
                        <option value="">Pilih Golongan Darah</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                            <option value="{{ $bt }}" @selected(old('blood_type', $bloodDonation->blood_type) === $bt)>{{ $bt }}</option>
                        @endforeach
                    </select>
                    @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Donor <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror" value="{{ old('donation_date', $bloodDonation->donation_date ? $bloodDonation->donation_date->format('Y-m-d\TH:i') : '') }}" required>
                    <small class="text-muted">Expiry: +42 hari dari tanggal donor</small>
                    @error('donation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Volume (ml)</label>
                    <input type="number" name="quantity_ml" class="form-control @error('quantity_ml') is-invalid @enderror" value="{{ old('quantity_ml', $bloodDonation->quantity_ml) }}" min="1">
                    @error('quantity_ml') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status', $bloodDonation->status) === 'available')>Available</option>
                        <option value="used" @selected(old('status', $bloodDonation->status) === 'used')>Used</option>
                        <option value="expired" @selected(old('status', $bloodDonation->status) === 'expired')>Expired</option>
                        <option value="discarded" @selected(old('status', $bloodDonation->status) === 'discarded')>Discarded</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $bloodDonation->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
