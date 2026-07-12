@extends('layouts.admin')

@section('title', 'Branding & Whitelabel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Branding & Whitelabel</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <span>Sesuaikan tampilan aplikasi dengan identitas rumah sakit Anda. Ubah logo, nama, dan teks sesuai kebutuhan.</span>
                </div>

                <form method="POST" action="{{ route('settings.branding.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="branding_app_name" class="form-label">App Name</label>
                        <input type="text" class="form-control" id="branding_app_name" name="branding_app_name"
                               value="{{ old('branding_app_name', $settings['branding_app_name'] ?? '') }}"
                               placeholder="{{ config('app.name', 'SIRS') }}" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label for="branding_logo_url" class="form-label">Logo URL</label>
                        <input type="text" class="form-control mb-2" id="branding_logo_url" name="branding_logo_url"
                               value="{{ old('branding_logo_url', $settings['branding_logo_url'] ?? '') }}"
                               placeholder="https://contoh.com/logo-rs.png" maxlength="500">
                        <input type="file" class="form-control" id="branding_logo_file" name="branding_logo_file"
                               accept="image/png,image/jpeg,image/svg+xml,image/webp">
                        <small class="text-muted">Tempel URL <strong>atau</strong> upload file (PNG/JPG/SVG/WebP, maks 2 MB). Jika upload, URL akan ditimpa otomatis.</small>
                    </div>

                    <div class="mb-3">
                        <label for="branding_favicon_url" class="form-label">Favicon URL</label>
                        <input type="text" class="form-control mb-2" id="branding_favicon_url" name="branding_favicon_url"
                               value="{{ old('branding_favicon_url', $settings['branding_favicon_url'] ?? '') }}"
                               placeholder="https://contoh.com/favicon.ico" maxlength="500">
                        <input type="file" class="form-control" id="branding_favicon_file" name="branding_favicon_file"
                               accept="image/x-icon,image/png,image/svg+xml">
                        <small class="text-muted">Tempel URL <strong>atau</strong> upload file (ICO/PNG/SVG, maks 1 MB).</small>
                    </div>

                    <div class="mb-3">
                        <label for="branding_footer_text" class="form-label">Footer Text</label>
                        <input type="text" class="form-control" id="branding_footer_text" name="branding_footer_text"
                               value="{{ old('branding_footer_text', $settings['branding_footer_text'] ?? '') }}"
                               placeholder="&copy; 2026 Rumah Sakit Anda. All rights reserved." maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="branding_hero_title" class="form-label">Hero Title <small class="text-muted">(Landing Page)</small></label>
                        <input type="text" class="form-control" id="branding_hero_title" name="branding_hero_title"
                               value="{{ old('branding_hero_title', $settings['branding_hero_title'] ?? '') }}"
                               placeholder="Kelola Rumah Sakit Lebih Modern & Terintegrasi" maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="branding_hero_subtitle" class="form-label">Hero Subtitle <small class="text-muted">(Landing Page)</small></label>
                        <input type="text" class="form-control" id="branding_hero_subtitle" name="branding_hero_subtitle"
                               value="{{ old('branding_hero_subtitle', $settings['branding_hero_subtitle'] ?? '') }}"
                               placeholder="Platform all-in-one untuk manajemen rumah sakit Anda." maxlength="500">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan Branding
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-eye"></i> Preview Branding</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">App Name</td>
                        <td class="fw-semibold">{{ $settings['branding_app_name'] ?? config('app.name', 'SIRS') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Logo</td>
                        <td>
                            @if(!empty($settings['branding_logo_url']))
                                <img src="{{ $settings['branding_logo_url'] }}" alt="Logo" style="max-height:40px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Favicon</td>
                        <td>
                            @if(!empty($settings['branding_favicon_url']))
                                <img src="{{ $settings['branding_favicon_url'] }}" alt="Favicon" style="max-height:24px;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Footer</td>
                        <td class="fw-semibold">{{ $settings['branding_footer_text'] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Hero Title</td>
                        <td class="fw-semibold">{{ $settings['branding_hero_title'] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Hero Subtitle</td>
                        <td class="fw-semibold">{{ $settings['branding_hero_subtitle'] ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
