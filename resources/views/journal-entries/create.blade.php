@extends('layouts.admin')

@section('title', 'Tambah Jurnal Entry')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Jurnal Entry</h1>
    <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            <span>Baris jurnal (debit/kredit) dikelola setelah jurnal disimpan, melalui halaman detail.</span>
        </div>

        <form action="{{ route('journal-entries.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Entry <span class="text-danger">*</span></label>
                    <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                    @error('entry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Referensi</label>
                    <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}" placeholder="No. invoice / dokumen">
                    @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
