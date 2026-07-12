@extends('layouts.admin')

@section('title', 'Integrasi BPJS Kesehatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header mb-3">
    <h1 class="h2">Integrasi BPJS Kesehatan</h1>
    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i> Tentang BPJS Kesehatan
            </div>
            <div class="card-body">
                <p>BPJS (Badan Penyelenggara Jaminan Sosial) Kesehatan adalah program jaminan kesehatan nasional Indonesia yang dikelola oleh pemerintah. Program ini memberikan perlindungan kesehatan kepada seluruh warga negara Indonesia melalui sistem asuransi kesehatan sosial.</p>
                <p class="mb-0">Dengan mengaktifkan integrasi ini, aplikasi dapat berkomunikasi dengan sistem BPJS Kesehatan untuk verifikasi kepesertaan, klaim, rujukan online, dan data SEP (Surat Eligibilitas Peserta) pasien.</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <i class="bi bi-sliders me-2"></i> Konfigurasi
            </div>
            <div class="card-body">
                <form action="{{ route('settings.bpjs.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Base URL</label>
                            <input type="text" name="bpjs_base_url" class="form-control @error('bpjs_base_url') is-invalid @enderror" value="{{ old('bpjs_base_url', $settings['bpjs_base_url'] ?? '') }}" placeholder="https://api.bpjs-kesehatan.go.id">
                            @error('bpjs_base_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Consumer ID</label>
                            <input type="text" name="bpjs_consumer_id" class="form-control @error('bpjs_consumer_id') is-invalid @enderror" value="{{ old('bpjs_consumer_id', $settings['bpjs_consumer_id'] ?? '') }}">
                            @error('bpjs_consumer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Consumer Secret</label>
                            <input type="password" name="bpjs_consumer_secret" class="form-control @error('bpjs_consumer_secret') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            @error('bpjs_consumer_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User Key</label>
                            <input type="text" name="bpjs_user_key" class="form-control @error('bpjs_user_key') is-invalid @enderror" value="{{ old('bpjs_user_key', $settings['bpjs_user_key'] ?? '') }}">
                            @error('bpjs_user_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="bpjs_is_enabled" class="form-check-input" value="1" id="bpjsEnabled" @checked(old('bpjs_is_enabled', $settings['bpjs_is_enabled'] ?? false))>
                                <label class="form-check-label" for="bpjsEnabled">Aktifkan Integrasi</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Konfigurasi BPJS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="bi bi-plug me-2"></i> Status Koneksi
            </div>
            <div class="card-body text-center">
                @if($settings['bpjs_is_enabled'] ?? false)
                    <i class="bi bi-check-circle text-success display-3"></i>
                    <h5 class="mt-2 text-success">Terhubung</h5>
                @else
                    <i class="bi bi-x-circle text-secondary display-3"></i>
                    <h5 class="mt-2 text-muted">Belum terhubung</h5>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <i class="bi bi-question-circle me-2"></i> Perlu Bantuan?
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">Untuk mendapatkan kredensial API BPJS Kesehatan:</p>
                <ol class="small text-muted mb-0 ps-3">
                    <li>Faskes harus terdaftar dan terverifikasi oleh BPJS Kesehatan</li>
                    <li>Ajukan permohonan akses bridging system melalui kantor cabang BPJS terdekat</li>
                    <li>Gunakan Consumer ID, Consumer Secret, dan User Key yang diberikan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
