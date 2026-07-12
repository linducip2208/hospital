@extends('layouts.admin')

@section('title', 'Tambah Kamar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tambah Kamar</h1>
    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                    <input type="text" name="room_number" class="form-control @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" required>
                    @error('room_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe Kamar</label>
                    <select name="room_type" class="form-select @error('room_type') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="VIP" @selected(old('room_type') === 'VIP')>VIP</option>
                        <option value="Kelas 1" @selected(old('room_type') === 'Kelas 1')>Kelas 1</option>
                        <option value="Kelas 2" @selected(old('room_type') === 'Kelas 2')>Kelas 2</option>
                        <option value="Kelas 3" @selected(old('room_type') === 'Kelas 3')>Kelas 3</option>
                    </select>
                    @error('room_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status') === 'available')>Tersedia</option>
                        <option value="occupied" @selected(old('status') === 'occupied')>Terisi</option>
                        <option value="maintenance" @selected(old('status') === 'maintenance')>Perbaikan</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lantai</label>
                    <input type="number" name="floor" class="form-control @error('floor') is-invalid @enderror" value="{{ old('floor') }}" min="1">
                    @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Tempat Tidur</label>
                    <input type="number" name="bed_count" class="form-control @error('bed_count') is-invalid @enderror" value="{{ old('bed_count', 1) }}" min="1">
                    @error('bed_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga/Hari</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" value="{{ old('price_per_day', 0) }}" min="0">
                        @error('price_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Fasilitas (satu per baris)</label>
                    <textarea name="facilities" class="form-control @error('facilities') is-invalid @enderror" rows="4" placeholder="AC&#10;TV&#10;Kamar mandi dalam&#10;WiFi">{{ old('facilities') }}</textarea>
                    @error('facilities') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
