@extends('layouts.admin')

@section('title', 'Jadwal Staff Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Jadwal Staff Baru</h1>
    <a href="{{ route('staff-schedules.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('staff-schedules.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Staff <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Pilih Staff</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Shift <span class="text-danger">*</span></label>
                    <input type="date" name="shift_date" class="form-control @error('shift_date') is-invalid @enderror" value="{{ old('shift_date') }}" required>
                    @error('shift_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Shift <span class="text-danger">*</span></label>
                    <select name="shift_type" class="form-select @error('shift_type') is-invalid @enderror" required>
                        <option value="">Pilih Shift</option>
                        <option value="morning" @selected(old('shift_type') === 'morning')>Pagi</option>
                        <option value="afternoon" @selected(old('shift_type') === 'afternoon')>Siang</option>
                        <option value="night" @selected(old('shift_type') === 'night')>Malam</option>
                        <option value="on_call" @selected(old('shift_type') === 'on_call')>On Call</option>
                        <option value="off" @selected(old('shift_type') === 'off')>Off</option>
                    </select>
                    @error('shift_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}">
                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}" placeholder="Contoh: IGD, Poli Umum, OK">
                    @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('staff-schedules.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
