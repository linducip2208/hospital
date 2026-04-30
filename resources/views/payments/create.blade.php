@extends('layouts.admin')

@section('title', 'Catat Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Catat Pembayaran</h1>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Appointment <span class="text-danger">*</span></label>
                    <select name="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Appointment --</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" @selected(old('appointment_id') == $appointment->id)>
                                {{ $appointment->patient->name ?? '-' }} - {{ $appointment->appointment_date->format('d/m/Y H:i') }} ({{ $appointment->doctor->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                        <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer</option>
                        <option value="debit" @selected(old('payment_method') === 'debit')>Kartu Debit</option>
                        <option value="credit" @selected(old('payment_method') === 'credit')>Kartu Kredit</option>
                        <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                        <option value="insurance" @selected(old('payment_method') === 'insurance')>Asuransi</option>
                    </select>
                    @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>Pending</option>
                        <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled</option>
                        <option value="refunded" @selected(old('status') === 'refunded')>Refunded</option>
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
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection