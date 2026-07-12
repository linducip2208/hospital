<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $brandName = cms('branding.title', config('app.name', 'SIMRS'));
        $waNumber  = cms('branding.meta.whatsapp_number', '6281296052010');
        $waText    = urlencode(cms('branding.meta.whatsapp_text', 'Halo, saya tertarik dengan source code SIMRS'));
        $primary   = cms('branding.meta.primary_color', '#2563eb');
        $accent    = cms('branding.meta.accent_color', '#06b6d4');
    @endphp

    <title>{{ $seoTitle ?? $brandName }}</title>
    <meta name="description" content="{{ $seoDescription ?? '' }}">
    @isset($seoKeywords)<meta name="keywords" content="{{ $seoKeywords }}">@endisset
    <link rel="canonical" href="{{ $seoCanonical ?? url()->current() }}">
    <meta name="theme-color" content="{{ $primary }}">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:url" content="{{ $seoCanonical ?? url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle ?? $brandName }}">
    <meta property="og:description" content="{{ $seoDescription ?? '' }}">
    @isset($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endisset

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle ?? $brandName }}">
    <meta name="twitter:description" content="{{ $seoDescription ?? '' }}">
    @isset($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endisset

    @isset($jsonLd)
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endisset

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: {{ $primary }};
            --accent: {{ $accent }};
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            color: #1f2937; background: #f9fafb; line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }
        a { color: var(--primary); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .pub-nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,.9); backdrop-filter: blur(12px);
            border-bottom: 1px solid #eef0f3;
        }
        .pub-nav-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .pub-brand { display: flex; align-items: center; gap: 10px; font-weight: 700; color: #111827; }
        .pub-brand .ico {
            width: 36px; height: 36px; border-radius: 9px; color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
        }
        .pub-nav a.link { color: #4b5563; font-weight: 500; margin-left: 1.25rem; }
        .container-pub { max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; }
        .hero-grad { background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; }
        .card-soft {
            background: #fff; border: 1px solid #eef0f3; border-radius: 14px;
            transition: transform .3s, box-shadow .3s;
        }
        .card-soft:hover { transform: translateY(-6px); box-shadow: 0 24px 48px -12px rgba(0,0,0,.12); }
        .btn-brand { background: var(--primary); color: #fff; border: none; font-weight: 600; }
        .btn-brand:hover { filter: brightness(1.08); color: #fff; }
        .sc-cta {
            background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;
            border-radius: 18px;
        }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity .7s, transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .wa-float {
            position: fixed; right: 18px; bottom: 18px; z-index: 200;
            width: 56px; height: 56px; border-radius: 50%; background: #25d366; color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
        }
        .wa-float:hover { color: #fff; transform: scale(1.05); }
        footer.pub-footer { background: #0f172a; color: #cbd5e1; padding: 3rem 0 2rem; margin-top: 4rem; }
        footer.pub-footer a { color: #cbd5e1; }
        @media (prefers-reduced-motion: reduce) { .reveal { transition-duration: .01ms; } }
        @media (max-width: 640px) {
            .pub-nav a.link { margin-left: .75rem; font-size: .9rem; }
        }
    </style>
    @stack('head')
</head>
<body>
    <nav class="pub-nav">
        <div class="pub-nav-inner">
            <a href="{{ url('/') }}" class="pub-brand">
                <span class="ico"><i class="bi bi-heart-pulse-fill"></i></span>
                <span>{{ $brandName }}</span>
            </a>
            <div>
                <a href="{{ url('/') }}" class="link d-none d-sm-inline">Beranda</a>
                <a href="{{ route('blog.index') }}" class="link">Blog</a>
                <a href="{{ route('docs') }}" class="link d-none d-sm-inline">Dokumentasi</a>
                <a href="{{ url('/beli-aplikasi-rumah-sakit') }}" class="link d-none d-md-inline">Beli Source Code</a>
                <a href="{{ route('login') }}" class="link">Masuk</a>
            </div>
        </div>
    </nav>

    {{ $slot }}

    {{-- Source Code CTA --}}
    <div class="container-pub">
        <div class="sc-cta p-4 p-md-5 my-5 reveal">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-2">Butuh Source Code SIMRS Lengkap?</h3>
                    <p class="mb-0 text-white-50">Dapatkan sistem informasi rumah sakit siap pakai — rekam medis, BPJS, SatuSehat, farmasi, keuangan, HR. Bisa di-whitelabel & self-host.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" class="btn btn-brand btn-lg" target="_blank" rel="noopener">
                        <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="pub-footer">
        <div class="container-pub">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="pub-brand text-white mb-3">
                        <span class="ico"><i class="bi bi-heart-pulse-fill"></i></span>
                        <span>{{ $brandName }}</span>
                    </div>
                    <p class="text-white-50">Sistem Informasi Manajemen Rumah Sakit terintegrasi — rekam medis elektronik, antrian, farmasi, keuangan, dan integrasi BPJS & SatuSehat.</p>
                </div>
                <div class="col-md-3 col-6">
                    <h6 class="text-white mb-3">Produk</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('docs') }}">Dokumentasi</a></li>
                        <li class="mb-2"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="mb-2"><a href="{{ url('/beli-aplikasi-rumah-sakit') }}">Beli Source Code</a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-6">
                    <h6 class="text-white mb-3">Kontak</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i> WhatsApp</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}">Masuk Aplikasi</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-white-50 small">© {{ date('Y') }} {{ $brandName }} · Powered by Laravel</div>
        </div>
    </footer>

    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" class="wa-float" target="_blank" rel="noopener" aria-label="WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
        }, { threshold: .12 });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));
    </script>
    @stack('scripts')
</body>
</html>
