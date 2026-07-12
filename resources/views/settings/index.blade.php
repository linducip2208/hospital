@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
<div class="page-header mb-3">
    <h1 class="h2">Pengaturan</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="umum-tab" data-bs-toggle="tab" data-bs-target="#umum" type="button" role="tab" aria-selected="true">
                    <i class="bi bi-gear me-1"></i> Umum
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="satusehat-tab" data-bs-toggle="tab" data-bs-target="#satusehat" type="button" role="tab" aria-selected="false">
                    <i class="bi bi-link-45deg me-1"></i> Satu Sehat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifikasi-tab" data-bs-toggle="tab" data-bs-target="#notifikasi" type="button" role="tab" aria-selected="false">
                    <i class="bi bi-bell me-1"></i> Notifikasi
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="settingsTabsContent">
            <div class="tab-pane fade show active" id="umum" role="tabpanel" tabindex="0">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-gear display-3"></i>
                    <p class="mt-3">Pengaturan umum akan tersedia di sini.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="satusehat" role="tabpanel" tabindex="0">
                <div class="py-3">
                    <p class="mb-3">Konfigurasi integrasi dengan Satu Sehat (SATUSEHAT) — platform pertukaran data kesehatan Kementerian Kesehatan RI.</p>
                    <a href="{{ route('settings.satusehat') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right-circle me-1"></i> Buka Konfigurasi Satu Sehat
                    </a>
                </div>
            </div>
            <div class="tab-pane fade" id="notifikasi" role="tabpanel" tabindex="0">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell display-3"></i>
                    <p class="mt-3">Pengaturan notifikasi akan tersedia di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
