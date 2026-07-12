@extends('layouts.admin')

@section('title', 'Edit Jurnal Entry')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Edit Jurnal Entry</h1>
    <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

@if($journalEntry->status !== 'draft')
<div class="alert alert-warning d-flex align-items-center gap-2 mb-3" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>Jurnal yang sudah diposting tidak dapat diubah.</span>
</div>
@else
<div class="card shadow-sm">
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            <span>Baris jurnal (debit/kredit) dikelola melalui halaman detail.</span>
        </div>

        <form action="{{ route('journal-entries.update', $journalEntry) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Entry <span class="text-danger">*</span></label>
                    <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" value="{{ old('entry_date', $journalEntry->entry_date->format('Y-m-d')) }}" required>
                    @error('entry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $journalEntry->description) }}" required>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Referensi</label>
                    <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $journalEntry->reference) }}" placeholder="No. invoice / dokumen">
                    @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $journalEntry->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
