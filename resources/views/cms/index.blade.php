@extends('layouts.admin')

@section('title', 'CMS Landing Page')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header mb-3">
    <div>
        <h1 class="h2 mb-1">CMS Landing Page</h1>
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Semua perubahan di sini langsung tampil di halaman utama website. <a href="{{ url('/') }}" target="_blank" class="text-primary fw-semibold">Buka halaman utama →</a></small>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right"></i> Preview Live
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@php
    $sectionIcons = [
        'branding' => 'bi-palette-fill', 'hero' => 'bi-house-heart', 'trust' => 'bi-patch-check-fill',
        'video' => 'bi-play-circle-fill', 'modules' => 'bi-grid-3x3-gap-fill', 'features' => 'bi-stars',
        'showcase' => 'bi-images', 'stats' => 'bi-bar-chart-line-fill', 'testimonials' => 'bi-chat-quote-fill',
        'insights' => 'bi-newspaper', 'partners' => 'bi-people-fill', 'cta' => 'bi-megaphone-fill',
        'footer' => 'bi-bottom-justified', 'about' => 'bi-info-circle',
    ];
    $sectionLabels = [
        'branding' => 'Branding (Logo, Nama)', 'hero' => 'Hero (Banner Utama)', 'trust' => 'Trust Strip (Badge)',
        'video' => 'Video Demo', 'modules' => 'Modul Lengkap (13)', 'features' => 'Fitur Unggulan (8)',
        'showcase' => 'Screenshot Gallery', 'stats' => 'Stats Banner', 'testimonials' => 'Testimoni',
        'insights' => 'Insights / Blog', 'partners' => 'Partner Logos', 'cta' => 'Call to Action',
        'footer' => 'Footer', 'about' => 'About (Legacy)',
    ];
    $byKey = $pageContents->keyBy('section');
@endphp

@foreach($groups as $groupTitle => $sectionKeys)
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-uppercase small text-muted" style="letter-spacing:0.06em;">{{ $groupTitle }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px;">Order</th>
                        <th>Section</th>
                        <th>Title</th>
                        <th style="width:100px;">Status</th>
                        <th style="width:200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectionKeys as $key)
                        @php $content = $byKey->get($key); @endphp
                        @if($content)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $content->order }}</span></td>
                                <td>
                                    <i class="bi {{ $sectionIcons[$key] ?? 'bi-file-text' }} me-2 text-primary"></i>
                                    <strong>{{ $sectionLabels[$key] ?? ucfirst($key) }}</strong>
                                    <div class="small text-muted text-monospace" style="font-size:0.7rem;">{{ $key }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $content->title }}</div>
                                    @if($content->subtitle)
                                        <small class="text-muted">{{ Str::limit($content->subtitle, 70) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($content->is_active)
                                        <span class="badge bg-success"><span class="d-inline-block rounded-circle bg-white me-1" style="width:6px;height:6px;"></span> Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('cms.edit', $content) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('cms.toggle', $content) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $content->is_active ? 'Sembunyikan' : 'Tampilkan' }}">
                                            <i class="bi {{ $content->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

<div class="alert alert-info small mt-3">
    <i class="bi bi-lightbulb me-2"></i>
    <strong>Tip:</strong> Setelah edit, refresh halaman utama untuk lihat perubahan. Cache otomatis di-clear setiap kali Anda menyimpan.
    Untuk reset semua section ke nilai default, jalankan: <code>php artisan db:seed --class=PageContentSeeder</code>
</div>
@endsection
