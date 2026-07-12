@extends('layouts.admin')

@section('title', 'Edit Rujukan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Rujukan</h1>
    <a href="{{ route('referrals.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('referrals.update', $referral) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pasien <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $referral->patient_id) == $patient->id)>{{ $patient->name }} ({{ $patient->medical_record_number ?? $patient->id }})</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" @selected(old('status', $referral->status) === 'pending')>Pending</option>
                        <option value="approved" @selected(old('status', $referral->status) === 'approved')>Approved</option>
                        <option value="rejected" @selected(old('status', $referral->status) === 'rejected')>Rejected</option>
                        <option value="completed" @selected(old('status', $referral->status) === 'completed')>Completed</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dokter</label>
                    <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                        <option value="">Pilih Dokter (opsional)</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $referral->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dari Poli</label>
                    <select name="from_polyclinic_id" class="form-select @error('from_polyclinic_id') is-invalid @enderror">
                        <option value="">Pilih Poli Asal (opsional)</option>
                        @foreach($polyclinics as $polyclinic)
                            <option value="{{ $polyclinic->id }}" @selected(old('from_polyclinic_id', $referral->from_polyclinic_id) == $polyclinic->id)>{{ $polyclinic->name }}</option>
                        @endforeach
                    </select>
                    @error('from_polyclinic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ke Poli <span class="text-danger">*</span></label>
                    <select name="to_polyclinic_id" class="form-select @error('to_polyclinic_id') is-invalid @enderror" required>
                        <option value="">Pilih Poli Tujuan</option>
                        @foreach($polyclinics as $polyclinic)
                            <option value="{{ $polyclinic->id }}" @selected(old('to_polyclinic_id', $referral->to_polyclinic_id) == $polyclinic->id)>{{ $polyclinic->name }}</option>
                        @endforeach
                    </select>
                    @error('to_polyclinic_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Alasan Rujukan <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason', $referral->reason) }}</textarea>
                    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnosis</label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3">{{ old('diagnosis', $referral->diagnosis) }}</textarea>
                    @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $referral->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('referrals.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
