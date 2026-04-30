@extends('layouts.admin')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Pembayaran</h1>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Appointment <span class="text-danger">*</span></label>
                    <select name="appointment_id" class="form-select" required>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" @selected(old('appointment_id', $payment->appointment_id) == $appointment->id)>
                                {{ $appointment->patient->name ?? '-' }} - {{ $appointment->appointment_date->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" min="0" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Metode</label>
                    <select name="payment_method" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="cash" @selected(old('payment_method', $payment->payment_method) === 'cash')>Tunai</option>
                        <option value="transfer" @selected(old('payment_method', $payment->payment_method) === 'transfer')>Transfer</option>
                        <option value="debit" @selected(old('payment_method', $payment->payment_method) === 'debit')>Kartu Debit</option>
                        <option value="credit" @selected(old('payment_method', $payment->payment_method) === 'credit')>Kartu Kredit</option>
                        <option value="qris" @selected(old('payment_method', $payment->payment_method) === 'qris')>QRIS</option>
                        <option value="insurance" @selected(old('payment_method', $payment->payment_method) === 'insurance')>Asuransi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="pending" @selected(old('status', $payment->status) === 'pending')>Pending</option>
                        <option value="completed" @selected(old('status', $payment->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $payment->status) === 'cancelled')>Cancelled</option>
                        <option value="refunded" @selected(old('status', $payment->status) === 'refunded')>Refunded</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $payment->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection