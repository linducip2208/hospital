<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        // Branding
        $brandName    = cms('branding.title', config('app.name', 'SIMRS'));
        $brandTagline = cms('branding.subtitle', 'Sistem Informasi Manajemen Rumah Sakit');
        $brandLogo    = cms('branding.image_url', null); // logo dari upload CMS
        $waNumber     = cms('branding.meta.whatsapp_number', '6281296052010');
        $waText       = cms('branding.meta.whatsapp_text', 'Halo, saya tertarik dengan SIMRS');
        $primaryColor = cms('branding.meta.primary_color', '#2563eb');
        $accentColor  = cms('branding.meta.accent_color', '#06b6d4');

        // Hero
        $heroTitle    = cms('hero.title', 'Modernisasi Operasional Rumah Sakit Anda');
        $heroSubtitle = cms('hero.subtitle', 'Sistem Manajemen Rumah Sakit Terintegrasi');
        $heroContent  = cms('hero.content', 'Platform all-in-one untuk modernisasi rumah sakit.');
        $heroImg      = cms('hero.image_url', 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=1200&q=80');
        $heroBtnText  = cms('hero.button_text', 'Lihat Demo Video');
        $heroBtnUrl   = cms('hero.button_url', '#video');
        $heroHighlight = cms('hero.meta.highlight_words', '');
        $heroCta2Text = cms('hero.meta.cta_secondary_text', 'Coba Gratis');
        $heroCta2Url  = cms('hero.meta.cta_secondary_url', '/register');
        $miniStats    = cms('hero.meta.mini_stats', []);
        $heroFloat1   = cms('hero.meta.floating_card_1', 'Antrian Live');
        $heroFloat2   = cms('hero.meta.floating_card_2', 'BPJS & SatuSehat Ready');

        // Video
        $videoUrl     = cms('video.video_url', 'https://www.youtube.com/watch?v=JlpgG4qfa8k');
        $videoPoster  = cms('video.image_url', 'https://images.unsplash.com/photo-1666214280391-8ff5bd3c0bf0?auto=format&fit=crop&w=1400&q=80');
        // Extract YouTube ID dari URL
        preg_match('~(?:v=|youtu\.be/|/embed/)([A-Za-z0-9_-]{11})~', $videoUrl, $m);
        $youtubeId = $m[1] ?? 'JlpgG4qfa8k';

        // Helper untuk auto-replace stats value
        $autoStat = function ($value) use ($stats) {
            if (! is_string($value) || ! str_starts_with($value, 'auto:')) return $value;
            $key = substr($value, 5);
            return number_format($stats[$key] ?? 0) . '+';
        };

        // Highlight title with span
        $renderTitle = function ($title, $highlight) {
            if (! $highlight || ! str_contains($title, $highlight)) return e($title);
            $parts = explode($highlight, $title, 2);
            return e($parts[0]) . '<span class="grad">' . e($highlight) . '</span>' . e($parts[1] ?? '');
        };
    @endphp

    <title>{{ $brandName }} — {{ $brandTagline }}</title>
    <meta name="description" content="{{ Str::limit($heroContent, 160) }}">
    <meta name="theme-color" content="{{ $primaryColor }}">
    <link rel="canonical" href="{{ url('/') }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $brandName }} — {{ $brandTagline }}">
    <meta property="og:description" content="{{ Str::limit($heroContent, 160) }}">
    <meta property="og:image" content="{{ $heroImg }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $brandName }}">
    <meta name="twitter:description" content="{{ Str::limit($heroContent, 160) }}">
    <meta name="twitter:image" content="{{ $heroImg }}">

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'MedicalBusiness',
        'name' => $brandName,
        'url' => url('/'),
        'description' => $brandTagline,
        'areaServed' => 'ID',
        'inLanguage' => 'id-ID',
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>

    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">

    <style>
        :root {
            --blue: {{ $primaryColor }};
            --blue-dark: #1d4ed8;
            --teal: {{ $accentColor }};
            --emerald: #10b981;
            --amber: #f59e0b;
            --rose: #f43f5e;
            --indigo: #6366f1;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --white: #ffffff;
            --radius: 16px;
            --radius-sm: 10px;
            --transition: 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--slate-800); background: var(--white); line-height: 1.6; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        img { max-width: 100%; display: block; }

        .nav-fixed { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid rgba(0,0,0,0.05); transition: all var(--transition); }
        .nav-fixed.scrolled { background: rgba(255,255,255,0.95); box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
        .nav-inner { max-width: 1280px; margin: 0 auto; padding: 0 2rem; height: 64px; display: flex; align-items: center; justify-content: space-between; }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; font-weight: 800; font-size: 1.1rem; color: var(--slate-900); letter-spacing: -0.01em; }
        .brand-dot { width: 32px; height: 32px; background: linear-gradient(135deg, var(--blue), var(--teal)); border-radius: 9px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.9rem; box-shadow: 0 0 16px rgba(37,99,235,0.25); }
        .nav-links { display: flex; align-items: center; gap: 0.4rem; }
        .nav-links a:not(.btn) { text-decoration: none; font-size: 0.84rem; font-weight: 500; color: var(--slate-600); padding: 0.4rem 0.8rem; border-radius: 8px; transition: all var(--transition); }
        .nav-links a:not(.btn):hover { color: var(--slate-900); background: var(--slate-100); }
        .nav-toggle { display: none; background: none; border: none; font-size: 1.4rem; color: var(--slate-700); cursor: pointer; }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.55rem 1.25rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.84rem; font-family: inherit; cursor: pointer; border: none; text-decoration: none; transition: all var(--transition); white-space: nowrap; }
        .btn-primary { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); color: #fff; box-shadow: 0 2px 10px rgba(37,99,235,0.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); color: #fff; }
        .btn-outline { background: transparent; color: var(--slate-800); border: 1.5px solid var(--slate-200); }
        .btn-outline:hover { border-color: var(--blue); color: var(--blue); background: rgba(37,99,235,0.03); }
        .btn-cta { background: #fff; color: var(--blue); font-weight: 700; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); color: var(--blue); }
        .btn-ghost { background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.15); }
        .btn-ghost:hover { background: rgba(255,255,255,0.14); color: #fff; }

        .hero { padding: 7rem 2rem 4rem; max-width: 1280px; margin: 0 auto; position: relative; }
        .hero-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 3rem; align-items: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 6px; padding: 0.35rem 1rem; border-radius: 50px; background: rgba(37,99,235,0.06); border: 1px solid rgba(37,99,235,0.12); font-size: 0.78rem; font-weight: 600; color: var(--blue); margin-bottom: 1.5rem; }
        .hero-badge .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--emerald); animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); } 50% { box-shadow: 0 0 0 8px rgba(16,185,129,0); } }
        .hero h1 { font-size: clamp(2rem, 4.4vw, 3.4rem); font-weight: 800; line-height: 1.12; letter-spacing: -0.03em; color: var(--slate-900); margin-bottom: 1.25rem; }
        .hero h1 span.grad { background: linear-gradient(135deg, var(--blue), var(--teal)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p { font-size: 1.05rem; color: var(--slate-500); margin-bottom: 2rem; max-width: 540px; }
        .hero-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .hero-mini-stats { display: flex; gap: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--slate-200); }
        .hero-mini-stats .ms-item { display: flex; flex-direction: column; }
        .hero-mini-stats .ms-num { font-size: 1.5rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; }
        .hero-mini-stats .ms-lbl { font-size: 0.74rem; color: var(--slate-500); font-weight: 500; }
        .hero-visual { position: relative; border-radius: 24px; overflow: hidden; box-shadow: 0 30px 80px rgba(15,23,42,0.15), 0 10px 25px rgba(15,23,42,0.06); aspect-ratio: 4 / 3; }
        .hero-visual img { width: 100%; height: 100%; object-fit: cover; }
        .hero-visual::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(6,182,212,0.05)); }
        .hero-floating-card { position: absolute; background: #fff; padding: 0.85rem 1rem; border-radius: 14px; box-shadow: 0 12px 30px rgba(15,23,42,0.12); display: flex; align-items: center; gap: 10px; font-size: 0.8rem; font-weight: 600; color: var(--slate-800); animation: floatY 4s ease-in-out infinite; }
        .hero-floating-card.fc1 { top: 20px; left: -20px; }
        .hero-floating-card.fc2 { bottom: 30px; right: -20px; animation-delay: 1.5s; }
        .hero-floating-card .fc-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
        @keyframes floatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

        .trust-strip { background: var(--slate-50); border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200); padding: 1.5rem 2rem; }
        .trust-inner { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
        .trust-label { font-size: 0.72rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.08em; width: 100%; text-align: center; margin-bottom: 0.5rem; }
        .trust-badge { display: flex; align-items: center; gap: 8px; padding: 0.45rem 0.9rem; border-radius: 10px; background: #fff; border: 1px solid var(--slate-200); font-size: 0.78rem; font-weight: 600; color: var(--slate-700); transition: all var(--transition); }
        .trust-badge:hover { border-color: var(--blue); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37,99,235,0.08); }
        .trust-badge i { font-size: 1rem; color: var(--blue); }

        .section { padding: 5rem 2rem; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-label { display: inline-block; padding: 0.3rem 0.85rem; background: rgba(37,99,235,0.08); color: var(--blue); border-radius: 50px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.85rem; }
        .section-title { font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; line-height: 1.2; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 0.85rem; }
        .section-desc { color: var(--slate-500); font-size: 1rem; max-width: 600px; margin-bottom: 3rem; line-height: 1.7; }
        .text-center { text-align: center; }
        .center-block { margin-left: auto; margin-right: auto; }

        .video-section { padding: 5rem 2rem; background: linear-gradient(180deg, var(--white) 0%, var(--slate-50) 100%); }
        .video-wrap { max-width: 1000px; margin: 0 auto; position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 70px rgba(15,23,42,0.18); cursor: pointer; aspect-ratio: 16 / 9; }
        .video-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .video-wrap:hover img { transform: scale(1.04); }
        .video-wrap::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(37,99,235,0.45), rgba(15,23,42,0.5)); }
        .play-btn { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90px; height: 90px; border-radius: 50%; background: rgba(255,255,255,0.95); display: flex; align-items: center; justify-content: center; color: var(--blue); font-size: 2rem; box-shadow: 0 15px 40px rgba(0,0,0,0.3); z-index: 2; transition: all var(--transition); border: none; cursor: pointer; }
        .play-btn::before { content: ''; position: absolute; inset: -8px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.4); animation: ringPulse 2s ease-out infinite; }
        @keyframes ringPulse { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(1.4); opacity: 0; } }
        .video-wrap:hover .play-btn { transform: translate(-50%,-50%) scale(1.08); background: #fff; }
        .video-caption { position: absolute; bottom: 24px; left: 24px; z-index: 2; color: #fff; font-size: 0.95rem; font-weight: 600; text-shadow: 0 2px 6px rgba(0,0,0,0.4); }
        .video-highlights { display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; margin-top: 2rem; }
        .highlight-chip { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #fff; border: 1px solid var(--slate-200); border-radius: 50px; font-size: 0.82rem; font-weight: 600; color: var(--slate-700); }
        .highlight-chip i { color: var(--emerald); }

        .modules-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .module-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 14px; padding: 1.5rem 1.25rem; transition: all var(--transition); position: relative; overflow: hidden; }
        .module-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--blue), var(--teal)); transform: scaleX(0); transform-origin: left; transition: transform 0.35s ease; }
        .module-card:hover { border-color: rgba(37,99,235,0.3); transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15,23,42,0.06); }
        .module-card:hover::before { transform: scaleX(1); }
        .module-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; margin-bottom: 1rem; }
        .module-title { font-size: 0.95rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.4rem; }
        .module-desc { font-size: 0.78rem; color: var(--slate-500); line-height: 1.55; }
        .grad-blue { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); }
        .grad-teal { background: linear-gradient(135deg, var(--teal), #0891b2); }
        .grad-emerald { background: linear-gradient(135deg, var(--emerald), #059669); }
        .grad-amber { background: linear-gradient(135deg, var(--amber), #d97706); }
        .grad-rose { background: linear-gradient(135deg, var(--rose), #e11d48); }
        .grad-indigo { background: linear-gradient(135deg, var(--indigo), #4f46e5); }
        .grad-cyan { background: linear-gradient(135deg, #06b6d4, #6366f1); }
        .grad-slate { background: linear-gradient(135deg, var(--slate-500), var(--slate-700)); }

        .features-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .feature-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 14px; padding: 1.5rem 1.25rem; transition: all var(--transition); }
        .feature-card:hover { border-color: rgba(37,99,235,0.25); box-shadow: 0 8px 24px rgba(15,23,42,0.06); transform: translateY(-3px); }
        .fc-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.85rem; font-size: 1.1rem; }
        .fc-title { font-size: 0.92rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.4rem; }
        .fc-desc { font-size: 0.78rem; color: var(--slate-500); line-height: 1.55; }

        /* ── Features Detail (alternating screenshot + caption) ── */
        .features-detail { padding: 5rem 2rem; background: linear-gradient(180deg, var(--white) 0%, var(--slate-50) 100%); }
        .feature-row { display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem; align-items: center; margin-bottom: 5rem; }
        .feature-row:last-child { margin-bottom: 0; }
        .feature-row.reverse .feature-shot { order: 2; }
        .feature-row.reverse .feature-caption { order: 1; }
        .feature-shot { position: relative; }
        .browser-mock { background: #fff; border-radius: 14px; box-shadow: 0 20px 50px rgba(15,23,42,0.12), 0 8px 16px rgba(15,23,42,0.04); border: 1px solid var(--slate-200); overflow: hidden; transition: transform 0.4s ease, box-shadow 0.4s ease; }
        .feature-shot:hover .browser-mock { transform: translateY(-6px); box-shadow: 0 30px 70px rgba(15,23,42,0.18), 0 10px 20px rgba(15,23,42,0.06); }
        .browser-bar { display: flex; align-items: center; gap: 6px; padding: 10px 14px; background: var(--slate-50); border-bottom: 1px solid var(--slate-200); }
        .browser-dot { width: 11px; height: 11px; border-radius: 50%; }
        .browser-dot.r { background: #ff5f57; }
        .browser-dot.y { background: #febc2e; }
        .browser-dot.g { background: #28c840; }
        .browser-url { flex: 1; margin-left: 12px; padding: 4px 12px; background: #fff; border: 1px solid var(--slate-200); border-radius: 6px; font-size: 0.74rem; color: var(--slate-500); font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .browser-body { display: block; width: 100%; aspect-ratio: 16 / 10; background: var(--slate-100); }
        .browser-body img { width: 100%; height: 100%; object-fit: cover; object-position: top; display: block; }

        .feature-caption .badge { display: inline-flex; align-items: center; gap: 6px; padding: 0.3rem 0.85rem; background: rgba(37,99,235,0.08); color: var(--blue); border-radius: 50px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 1rem; }
        .feature-caption h3 { font-size: clamp(1.4rem, 2.4vw, 1.85rem); font-weight: 800; color: var(--slate-900); line-height: 1.25; letter-spacing: -0.02em; margin-bottom: 1rem; }
        .feature-caption .lead { font-size: 1rem; color: var(--slate-600); line-height: 1.7; margin-bottom: 1.5rem; }
        .feature-caption ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.65rem; }
        .feature-caption ul li { display: flex; align-items: flex-start; gap: 10px; font-size: 0.92rem; color: var(--slate-700); line-height: 1.55; }
        .feature-caption ul li i { color: var(--emerald); font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

        @media (max-width: 992px) {
            .feature-row { grid-template-columns: 1fr; gap: 2rem; margin-bottom: 4rem; }
            .feature-row.reverse .feature-shot { order: 1; }
            .feature-row.reverse .feature-caption { order: 2; }
        }
        @media (max-width: 640px) {
            .features-detail { padding: 3.5rem 1.25rem; }
            .feature-row { gap: 1.5rem; margin-bottom: 3rem; }
            .feature-caption h3 { font-size: 1.3rem; }
            .feature-caption .lead { font-size: 0.92rem; }
            .feature-caption ul li { font-size: 0.86rem; }
            .browser-bar { padding: 8px 10px; }
            .browser-dot { width: 9px; height: 9px; }
            .browser-url { font-size: 0.68rem; padding: 3px 8px; }
        }

        /* ── Modul Lainnya (compact grid) ── */
        .more-modules { padding: 5rem 2rem; background: var(--white); }
        .module-group { margin-bottom: 2.5rem; }
        .module-group:last-child { margin-bottom: 0; }
        .module-group-label { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; font-size: 0.78rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.06em; }
        .module-group-label::before { content: ''; width: 22px; height: 2px; background: linear-gradient(90deg, var(--blue), var(--teal)); border-radius: 2px; }
        .module-mini-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.85rem; }
        .module-mini { display: flex; align-items: flex-start; gap: 12px; padding: 1rem 1.1rem; background: var(--white); border: 1px solid var(--slate-200); border-radius: 12px; transition: all var(--transition); text-decoration: none; color: inherit; min-height: 78px; }
        .module-mini:hover { border-color: rgba(37,99,235,0.3); transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15,23,42,0.06); color: inherit; }
        .module-mini .mm-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
        .module-mini .mm-body { flex: 1; min-width: 0; }
        .module-mini .mm-title { font-size: 0.84rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.2rem; line-height: 1.3; }
        .module-mini .mm-desc { font-size: 0.73rem; color: var(--slate-500); line-height: 1.45; }

        @media (max-width: 992px) {
            .module-mini-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 720px) {
            .module-mini-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .more-modules { padding: 3.5rem 1.25rem; }
            .module-mini-grid { grid-template-columns: 1fr; gap: 0.7rem; }
            .module-mini { padding: 0.85rem 1rem; min-height: 68px; }
        }

        .showcase { background: var(--slate-900); color: #fff; padding: 5rem 2rem; position: relative; overflow: hidden; }
        .showcase::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 30%, rgba(37,99,235,0.18), transparent 50%), radial-gradient(circle at 80% 70%, rgba(6,182,212,0.12), transparent 50%); }
        .showcase-inner { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; }
        .showcase .section-label { background: rgba(37,99,235,0.2); color: #93c5fd; }
        .showcase .section-title { color: #fff; }
        .showcase .section-desc { color: rgba(255,255,255,0.6); }
        .showcase-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .showcase-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; overflow: hidden; transition: all var(--transition); }
        .showcase-card:hover { border-color: rgba(37,99,235,0.5); transform: translateY(-4px); }
        .showcase-card .sc-img { aspect-ratio: 16 / 11; overflow: hidden; background: linear-gradient(135deg, var(--blue-dark), var(--slate-900)); }
        .showcase-card .sc-img img { width: 100%; height: 100%; object-fit: cover; opacity: 0.85; transition: opacity 0.3s; }
        .showcase-card:hover .sc-img img { opacity: 1; }
        .showcase-card .sc-body { padding: 1.25rem; }
        .showcase-card .sc-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .showcase-card .sc-desc { font-size: 0.82rem; color: rgba(255,255,255,0.6); }

        .stats-banner { background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 50%, #1e3a8a 100%); color: #fff; padding: 4rem 2rem; position: relative; overflow: hidden; }
        .stats-banner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 90% 50%, rgba(6,182,212,0.25), transparent 50%); }
        .stats-grid { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; text-align: center; }
        .stats-grid .sb-num { font-size: clamp(1.8rem, 3.8vw, 2.6rem); font-weight: 800; letter-spacing: -0.02em; line-height: 1; }
        .stats-grid .sb-lbl { font-size: 0.85rem; opacity: 0.85; margin-top: 0.4rem; }
        .stats-grid .sb-icon { font-size: 1.4rem; opacity: 0.7; margin-bottom: 0.75rem; }

        .testimonial-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .testimonial-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 16px; padding: 1.75rem; position: relative; }
        .testimonial-card::before { content: '\201C'; position: absolute; top: -10px; left: 20px; font-size: 4rem; color: rgba(37,99,235,0.15); font-family: Georgia, serif; line-height: 1; }
        .tq { font-size: 0.92rem; color: var(--slate-700); line-height: 1.7; margin-bottom: 1.25rem; position: relative; z-index: 1; }
        .ta { display: flex; align-items: center; gap: 12px; padding-top: 1.25rem; border-top: 1px solid var(--slate-100); }
        .ta-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: linear-gradient(135deg, var(--blue), var(--teal)); }
        .ta-name { font-size: 0.88rem; font-weight: 700; color: var(--slate-900); }
        .ta-role { font-size: 0.74rem; color: var(--slate-500); }

        .insights-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .insight-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 16px; overflow: hidden; transition: all var(--transition); display: block; text-decoration: none; color: inherit; }
        .insight-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(15,23,42,0.08); border-color: rgba(37,99,235,0.25); color: inherit; }
        .insight-card .ic-img { aspect-ratio: 16 / 9; overflow: hidden; background: linear-gradient(135deg, var(--blue), var(--teal)); }
        .insight-card .ic-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .insight-card:hover .ic-img img { transform: scale(1.05); }
        .insight-card .ic-body { padding: 1.5rem; }
        .insight-card .ic-tag { display: inline-block; padding: 0.2rem 0.7rem; background: rgba(37,99,235,0.08); color: var(--blue); border-radius: 50px; font-size: 0.7rem; font-weight: 700; margin-bottom: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .insight-card .ic-title { font-size: 1.02rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.6rem; line-height: 1.4; }
        .insight-card .ic-excerpt { font-size: 0.82rem; color: var(--slate-500); line-height: 1.6; margin-bottom: 1rem; }
        .insight-card .ic-meta { display: flex; align-items: center; justify-content: space-between; font-size: 0.74rem; color: var(--slate-500); }
        .insight-card .ic-meta .read-more { color: var(--blue); font-weight: 600; }

        .partners-row { display: flex; flex-wrap: wrap; gap: 1.5rem 3rem; justify-content: center; align-items: center; }
        .partner-logo { font-size: 1rem; font-weight: 800; color: var(--slate-400); letter-spacing: -0.01em; padding: 0.5rem 1rem; border: 1px dashed var(--slate-200); border-radius: 8px; transition: all var(--transition); }
        .partner-logo:hover { color: var(--slate-700); border-color: var(--slate-300); }

        .cta-section { padding: 5rem 2rem; }
        .cta-card { max-width: 1100px; margin: 0 auto; background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 50%, var(--slate-900) 100%); border-radius: 24px; padding: 4rem 3rem; color: #fff; text-align: center; position: relative; overflow: hidden; box-shadow: 0 25px 60px rgba(37,99,235,0.25); }
        .cta-card::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 30% 50%, rgba(6,182,212,0.3), transparent 60%); }
        .cta-card > * { position: relative; z-index: 1; }
        .cta-card h2 { font-size: clamp(1.6rem, 3.2vw, 2.4rem); font-weight: 800; line-height: 1.2; margin-bottom: 0.85rem; letter-spacing: -0.02em; }
        .cta-card p { font-size: 1rem; opacity: 0.9; margin-bottom: 2rem; }
        .cta-actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }

        footer.footer { background: var(--slate-900); color: rgba(255,255,255,0.7); padding: 4rem 2rem 1.5rem; }
        .footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .footer h4 { font-size: 0.82rem; font-weight: 700; color: #fff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .footer-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 1rem; color: #fff; font-weight: 800; font-size: 1.1rem; text-decoration: none; }
        .footer-tagline { font-size: 0.85rem; line-height: 1.65; margin-bottom: 1.25rem; }
        .footer-contact { font-size: 0.82rem; line-height: 1.85; }
        .footer-contact i { width: 16px; color: var(--teal); }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.85rem; transition: color var(--transition); }
        .footer-links a:hover { color: #fff; }
        .footer-cert { display: flex; flex-direction: column; gap: 0.6rem; }
        .cert-badge { display: inline-flex; align-items: center; gap: 6px; padding: 0.4rem 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; font-size: 0.74rem; color: rgba(255,255,255,0.85); }
        .cert-badge i { color: var(--teal); }
        .footer-bottom { max-width: 1200px; margin: 0 auto; padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; font-size: 0.78rem; color: rgba(255,255,255,0.5); }
        .social-links { display: flex; gap: 0.75rem; }
        .social-links a { width: 34px; height: 34px; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem; transition: all var(--transition); }
        .social-links a:hover { background: var(--blue); color: #fff; border-color: var(--blue); }

        .floating-wa { position: fixed; bottom: 24px; right: 24px; z-index: 999; display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px; border-radius: 50px; background: #25d366; color: #fff; font-weight: 600; font-size: 0.84rem; text-decoration: none; box-shadow: 0 10px 25px rgba(37,211,102,0.35); transition: all var(--transition); }
        .floating-wa:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(37,211,102,0.45); color: #fff; }
        .floating-wa i { font-size: 1.2rem; }
        .floating-wa::before { content: ''; position: absolute; inset: -2px; border-radius: 50px; box-shadow: 0 0 0 0 rgba(37,211,102,0.5); animation: waPulse 2.5s infinite; }
        @keyframes waPulse { 0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.5); } 70% { box-shadow: 0 0 0 14px rgba(37,211,102,0); } 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); } }

        .video-modal { position: fixed; inset: 0; z-index: 9999; background: rgba(15,23,42,0.92); display: none; align-items: center; justify-content: center; padding: 2rem; opacity: 0; transition: opacity 0.25s; }
        .video-modal.open { display: flex; opacity: 1; }
        .video-modal-inner { position: relative; width: 100%; max-width: 960px; aspect-ratio: 16 / 9; border-radius: 16px; overflow: hidden; box-shadow: 0 30px 80px rgba(0,0,0,0.5); }
        .video-modal-close { position: absolute; top: -44px; right: 0; background: none; border: none; color: #fff; font-size: 1.6rem; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 600; }
        .video-modal iframe { width: 100%; height: 100%; border: 0; }

        .fade-up { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 992px) {
            .hero-grid { grid-template-columns: 1fr; gap: 2rem; }
            .hero-floating-card.fc1, .hero-floating-card.fc2 { display: none; }
            .modules-grid { grid-template-columns: repeat(3, 1fr); }
            .features-grid { grid-template-columns: repeat(3, 1fr); }
            .showcase-grid { grid-template-columns: 1fr; }
            .testimonial-grid, .insights-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 720px) {
            .nav-toggle { display: block; }
            .nav-links { display: none; position: absolute; top: 64px; left: 0; right: 0; background: #fff; flex-direction: column; padding: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 1px solid var(--slate-200); }
            .nav-links.open { display: flex; }
            .hero { padding: 6rem 1.25rem 3rem; }
            .section, .video-section, .showcase, .cta-section, .stats-banner { padding-left: 1.25rem; padding-right: 1.25rem; }
            .modules-grid, .features-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .floating-wa span { display: none; }
            .floating-wa { padding: 14px; }
            .cta-card { padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ============ NAV ============ --}}
    <nav class="nav-fixed" id="navbar">
        <div class="nav-inner">
            <a href="/" class="nav-brand">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height:32px;width:auto;max-width:140px;object-fit:contain;">
                @else
                    <span class="brand-dot"><i class="bi bi-hospital" aria-hidden="true"></i></span>
                    {{ $brandName }}
                @endif
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Buka menu">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <div class="nav-links" id="navLinks">
                <a href="#features">Features</a>
                <a href="#modul-lainnya">Semua Modul</a>
                <a href="#video">Demo</a>
                <a href="#testimoni">Testimoni</a>
                <a href="#blog">Insights</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Request Demo</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    @if(cms('hero.is_active', true))
    <section class="hero">
        <div class="hero-grid">
            <div class="fade-up">
                <div class="hero-badge">
                    <span class="badge-dot"></span> {{ $heroSubtitle }}
                </div>
                <h1>{!! $renderTitle($heroTitle, $heroHighlight) !!}</h1>
                <p>{{ $heroContent }}</p>
                <div class="hero-actions">
                    <button type="button" class="btn btn-primary" data-video-open style="font-size:0.95rem;padding:0.7rem 1.6rem;">
                        <i class="bi bi-play-circle-fill" aria-hidden="true"></i> {{ $heroBtnText }}
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="font-size:0.95rem;padding:0.7rem 1.6rem;">
                            <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i> Buka Dashboard
                        </a>
                    @else
                        <a href="{{ $heroCta2Url }}" class="btn btn-outline" style="font-size:0.95rem;padding:0.7rem 1.6rem;">
                            <i class="bi bi-rocket-takeoff" aria-hidden="true"></i> {{ $heroCta2Text }}
                        </a>
                    @endauth
                </div>
                @if(! empty($miniStats))
                <div class="hero-mini-stats">
                    @foreach($miniStats as $s)
                        @php
                            $val = $s['value'] ?? '-';
                            if ($val === 'auto') {
                                $val = number_format($stats['patients'] ?? 0) . '+';
                            }
                        @endphp
                        <div class="ms-item">
                            <div class="ms-num">{{ $val }}</div>
                            <div class="ms-lbl">{{ $s['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="hero-visual fade-up" style="transition-delay:0.15s;">
                <img src="{{ $heroImg }}" alt="{{ $heroTitle }}" loading="eager">
                <div class="hero-floating-card fc1">
                    <div class="fc-icon grad-emerald"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></div>
                    <div>
                        <div style="font-size:0.7rem;color:var(--slate-500);font-weight:500;">{{ $heroFloat1 }}</div>
                        <div>{{ number_format($stats['patients'] ?? 0) }}+ Pasien Aktif</div>
                    </div>
                </div>
                <div class="hero-floating-card fc2">
                    <div class="fc-icon grad-blue"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
                    <div>
                        <div style="font-size:0.7rem;color:var(--slate-500);font-weight:500;">Terintegrasi</div>
                        <div>{{ $heroFloat2 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============ FEATURES DETAIL (alternating screenshot + caption) ============ --}}
    @php
        $featuresDetail = [
            [
                'badge' => 'Dashboard',
                'title' => 'Dashboard Eksekutif dengan KPI Real-Time',
                'lead'  => 'Lihat denyut nadi rumah sakit dalam sekali pandang. Statistik pasien, dokter, appointment hari ini, pendapatan bulan berjalan, status kamar, dan antrian — semua live dengan grafik tren 7 hari & 6 bulan.',
                'bullets' => [
                    'KPI cards: pasien aktif, dokter, appointment, kamar, IGD, antrian',
                    'Grafik kunjungan pasien 7 hari & pendapatan 6 bulan terakhir',
                    'Doughnut chart polikinik terpadat & breakdown kamar per tipe',
                    'Alert stok obat kritis & okupansi kamar real-time',
                    'Quick actions ke modul yang paling sering diakses',
                ],
                'image' => 'dashboard.png',
                'url'   => '/dashboard',
            ],
            [
                'badge' => 'Appointment',
                'title' => 'Janji Temu Pasien — Scheduling Tanpa Konflik',
                'lead'  => 'Atur jadwal appointment per dokter, poli, atau treatment. Sistem otomatis mendeteksi bentrok slot, mengirim reminder, dan tracking status dari Scheduled hingga Completed.',
                'bullets' => [
                    'Filter cepat: tanggal, status, dokter, poliklinik, treatment',
                    'Status flow: Scheduled → Confirmed → In Progress → Completed',
                    'Search pasien & dokter sekali klik di header tabel',
                    'One-click ubah status appointment via tombol aksi',
                    'Linked otomatis ke modul Rekam Medis & Pembayaran',
                ],
                'image' => 'appointments.png',
                'url'   => '/appointments',
                'reverse' => true,
            ],
            [
                'badge' => 'Rekam Medis',
                'title' => 'Rekam Medis Digital — Aman, Terstruktur, Bisa Dicari',
                'lead'  => 'Catat diagnosis (primer & sekunder), tindakan medis, resep obat, dan vital signs. Setiap rekam medis terkunci ke pasien, dokter, dan appointment — auditable & SatuSehat-ready.',
                'bullets' => [
                    'Diagnosis dengan kode ICD-10 (opsional) & deskripsi narasi',
                    'Tindakan medis dari katalog treatment dengan harga otomatis',
                    'Resep obat dari inventaris farmasi — stok auto-berkurang',
                    'Vital signs: TD, nadi, suhu, RR, SpO2 tersimpan riwayatnya',
                    'Integrasi SATUSEHAT untuk sinkron ke RME nasional',
                ],
                'image' => 'medical-records.png',
                'url'   => '/medical-records',
            ],
            [
                'badge' => 'Farmasi',
                'title' => 'Inventaris Obat — Stok Cerdas, Resep Lancar',
                'lead'  => 'Kelola ribuan SKU obat dengan kategori, satuan, harga beli, dan harga jual. Stok auto-berkurang setiap resep dibuat, dengan alert untuk obat di bawah threshold minimum.',
                'bullets' => [
                    'CRUD obat lengkap: kategori, dosis, kemasan, supplier',
                    'Stok real-time, alert otomatis bila di bawah min_stock',
                    'Harga beli & harga jual tracking untuk margin analysis',
                    'Terhubung langsung ke modul Resep di Rekam Medis',
                    'Export Excel untuk audit & purchase order',
                ],
                'image' => 'drugs.png',
                'url'   => '/drugs',
                'reverse' => true,
            ],
            [
                'badge' => 'IGD',
                'title' => 'IGD & Triase — Penanganan Cepat dengan Prioritas Jelas',
                'lead'  => 'Catat kunjungan IGD dengan sistem triase ESI (Hijau/Kuning/Merah). Disposisi terkontrol: rawat inap, pulang, atau rujuk — semua tercatat untuk audit clinical pathway.',
                'bullets' => [
                    'Triase 3 level (Hijau-Kuning-Merah) dengan warna visual',
                    'Keluhan utama, vital signs, tindakan darurat tercatat',
                    'Disposisi: admit / discharge / refer dengan reason',
                    'Trigger Code Blue activation langsung dari form IGD',
                    'Laporan IGD per shift untuk evaluasi kinerja',
                ],
                'image' => 'emergencies.png',
                'url'   => '/emergencies',
            ],
            [
                'badge' => 'Lab',
                'title' => 'Laboratorium — Order, Sampel, Hasil dalam Satu Alur',
                'lead'  => 'Dokter order pemeriksaan lab langsung dari Rekam Medis. Petugas lab catat hasil per parameter dengan flag normal/abnormal — laporan otomatis terkirim ke rekam medis pasien.',
                'bullets' => [
                    'Modul: hematologi, kimia darah, urinalisis, mikrobiologi',
                    'Status: Requested → Sample Collected → In Progress → Completed',
                    'Hasil dengan reference range & flag (normal/high/low/critical)',
                    'Cetak laporan PDF dengan kop rumah sakit & tanda tangan',
                    'Auto-attach ke rekam medis pasien setelah hasil selesai',
                ],
                'image' => 'lab-tests.png',
                'url'   => '/lab-tests',
                'reverse' => true,
            ],
            [
                'badge' => 'Pembayaran',
                'title' => 'Pembayaran & Invoice — Multi-Method, Auto-Number',
                'lead'  => 'Proses pembayaran konsultasi, tindakan, obat, dan rawat inap dalam satu transaksi. Nomor invoice otomatis (INV/YYYY/MM/XXXXX), multi-metode, dengan laporan keuangan terhubung ke akuntansi.',
                'bullets' => [
                    'Metode: Tunai, Transfer Bank, Debit, Kredit, QRIS',
                    'Invoice auto-number format: INV/2026/05/00001',
                    'Status pembayaran: Pending → Completed → Cancelled',
                    'Cetak invoice/kwitansi PDF siap cetak',
                    'Auto-jurnal ke buku besar saat status Completed',
                ],
                'image' => 'payments.png',
                'url'   => '/payments',
            ],
            [
                'badge' => 'HR & Payroll',
                'title' => 'HR & Payroll — Karyawan, Absensi, Gaji Otomatis',
                'lead'  => 'Kelola data karyawan, absensi harian, dan generate slip gaji bulanan. Komponen lengkap: gaji pokok, tunjangan, potongan BPJS/PPh21, lembur, dengan workflow approval.',
                'bullets' => [
                    'Master karyawan: NIP, jabatan, departemen, gaji pokok',
                    'Absensi: jam masuk/keluar, status (hadir/izin/sakit/alfa)',
                    'Komponen gaji: tunjangan, lembur, potongan, bonus',
                    'Workflow gaji: Draft → Approved → Paid dengan audit log',
                    'Cetak slip gaji PDF per karyawan, batch per departemen',
                ],
                'image' => 'salaries.png',
                'url'   => '/salaries',
                'reverse' => true,
            ],
            [
                'badge' => 'Rawat Inap',
                'title' => 'Rawat Inap — Kamar, Bed, Tarif Real-Time',
                'lead'  => 'Manajemen kamar rawat inap lengkap dengan tipe (VVIP/VIP/Kelas 1-3/ICU/NICU/OK), fasilitas, tarif per malam, dan status okupansi real-time terhubung dengan dashboard.',
                'bullets' => [
                    'Tipe kamar: VVIP, VIP, Kelas 1-3, ICU, NICU, OK',
                    'Fasilitas per kamar: AC, TV, kamar mandi dalam, bed-side table',
                    'Status: Tersedia / Terisi / Maintenance / Cleaning',
                    'Tarif per malam tracking untuk billing rawat inap',
                    'Heat map okupansi per tipe kamar di dashboard',
                ],
                'image' => 'rooms.png',
                'url'   => '/rooms',
            ],
            [
                'badge' => 'Akuntansi',
                'title' => 'Akuntansi — Chart of Accounts & Jurnal Double-Entry',
                'lead'  => 'Buku besar akuntansi dengan COA lengkap (Aset, Kewajiban, Ekuitas, Pendapatan, Beban). Jurnal double-entry dengan validasi balance debit=kredit, immutable setelah posted.',
                'bullets' => [
                    'Chart of Accounts terstruktur dengan kode hierarki',
                    'Jurnal umum double-entry — debit & kredit otomatis cek balance',
                    'Status: Draft → Posted (immutable setelah posted)',
                    'Auto-jurnal dari modul Pembayaran ke akun terkait',
                    'Laporan Neraca, Laba/Rugi, dan Buku Besar per periode',
                ],
                'image' => 'journal-entries.png',
                'url'   => '/journal-entries',
                'reverse' => true,
            ],
        ];
    @endphp
    <section class="features-detail" id="features">
        <div class="section-inner text-center" style="margin-bottom:4rem;">
            <div class="section-label">Fitur Utama</div>
            <div class="section-title">10 Modul Inti untuk Rumah Sakit Modern</div>
            <div class="section-desc center-block">
                Lihat tampilan asli dari sistem yang sudah berjalan. Setiap modul dirancang untuk alur kerja sehari-hari tenaga medis & administrasi rumah sakit.
            </div>
        </div>
        <div class="section-inner">
            @foreach($featuresDetail as $idx => $f)
                <div class="feature-row fade-up{{ ! empty($f['reverse']) ? ' reverse' : '' }}" style="transition-delay:{{ $idx * 0.04 }}s;">
                    <div class="feature-shot">
                        <div class="browser-mock">
                            <div class="browser-bar">
                                <span class="browser-dot r"></span>
                                <span class="browser-dot y"></span>
                                <span class="browser-dot g"></span>
                                <div class="browser-url">{{ $brandName }} {{ $f['url'] }}</div>
                            </div>
                            <div class="browser-body">
                                <img src="{{ asset('marketing/screens/' . $f['image']) }}" alt="Tampilan {{ $f['title'] }}" loading="lazy" width="1440" height="900">
                            </div>
                        </div>
                    </div>
                    <div class="feature-caption">
                        <span class="badge"><i class="bi bi-stars"></i> {{ $f['badge'] }}</span>
                        <h3>{{ $f['title'] }}</h3>
                        <p class="lead">{{ $f['lead'] }}</p>
                        <ul>
                            @foreach($f['bullets'] as $b)
                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ $b }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ MODUL LAINNYA (grid kompak 34+ modul) ============ --}}
    @php
        $moreModules = [
            'Master Data' => [
                ['icon' => 'bi-person-heart',         'color' => 'var(--blue)',    'title' => 'Pasien',          'desc' => 'NIK, BPJS, alergi, riwayat medis, kontak darurat', 'url' => '/patients'],
                ['icon' => 'bi-person-badge',         'color' => 'var(--teal)',    'title' => 'Dokter',          'desc' => 'Spesialisasi, STR, biaya konsultasi, jadwal',       'url' => '/doctors'],
                ['icon' => 'bi-people',               'color' => 'var(--indigo)',  'title' => 'Pengguna',        'desc' => '14 role akses, username, password, departemen',     'url' => '/users'],
                ['icon' => 'bi-diagram-3',            'color' => 'var(--amber)',   'title' => 'Departemen',      'desc' => 'Struktur organisasi & assign karyawan',             'url' => '/departments'],
                ['icon' => 'bi-clipboard-pulse',      'color' => 'var(--rose)',    'title' => 'Treatment',       'desc' => 'Katalog tindakan + harga + durasi',                 'url' => '/treatments'],
                ['icon' => 'bi-calendar3',            'color' => 'var(--emerald)', 'title' => 'Jadwal Staff',    'desc' => 'Shift pagi/siang/malam per user per tanggal',       'url' => '/staff-schedules'],
            ],
            'Operasional Klinis' => [
                ['icon' => 'bi-bell',                 'color' => 'var(--amber)',   'title' => 'Antrian',         'desc' => 'Nomor otomatis, waiting→called→completed',          'url' => '/queues'],
                ['icon' => 'bi-arrow-left-right',     'color' => 'var(--blue)',    'title' => 'Rujukan',         'desc' => 'Rujuk antar poli, workflow approval',               'url' => '/referrals'],
                ['icon' => 'bi-hospital',             'color' => 'var(--teal)',    'title' => 'Polyclinic',      'desc' => '12 poli (umum, gigi, anak, jantung, dll.)',         'url' => '/polyclinics'],
                ['icon' => 'bi-scissors',             'color' => 'var(--rose)',    'title' => 'Operasi / OT',    'desc' => 'Jadwal operasi, anestesi, pre/post-op',             'url' => '/surgeries'],
                ['icon' => 'bi-heart-pulse',          'color' => 'var(--rose)',    'title' => 'Ruang Bersalin',  'desc' => 'Normal/Caesar/Vacuum, data ibu & bayi',             'url' => '/maternities'],
                ['icon' => 'bi-exclamation-octagon',  'color' => 'var(--rose)',    'title' => 'Code Blue',       'desc' => 'Aktivasi tim resusitasi darurat',                   'url' => '/code-blue-activations'],
                ['icon' => 'bi-diagram-2',            'color' => 'var(--indigo)',  'title' => 'Clinical Pathway','desc' => 'Standarisasi protokol klinis per diagnosis',         'url' => '/clinical-pathways'],
                ['icon' => 'bi-file-earmark-medical', 'color' => 'var(--blue)',    'title' => 'Discharge Summary','desc'=> 'Ringkasan pulang pasien rawat inap',                 'url' => '/discharge-summaries'],
            ],
            'Keperawatan & Kebidanan' => [
                ['icon' => 'bi-activity',             'color' => 'var(--rose)',    'title' => 'Tanda Vital',     'desc' => 'TD, HR, RR, SpO2, suhu — monitoring real-time',     'url' => '/vital-signs'],
                ['icon' => 'bi-capsule',              'color' => 'var(--emerald)', 'title' => 'Pemberian Obat',  'desc' => 'Dosis + rute (oral/IV/IM/SC) + jam',                'url' => '/medication-administrations'],
                ['icon' => 'bi-journal-medical',      'color' => 'var(--blue)',    'title' => 'Asuhan Keperawatan','desc'=> 'SOAP: subjektif, objektif, diagnosis, evaluasi',     'url' => '/nursing-cares'],
                ['icon' => 'bi-arrow-repeat',         'color' => 'var(--amber)',   'title' => 'Serah Terima Shift','desc'=> 'Handover pagi→siang→malam dengan catatan',           'url' => '/shift-handovers'],
                ['icon' => 'bi-person-check',         'color' => 'var(--teal)',    'title' => 'Penugasan',       'desc' => 'Assign perawat/bidan ke pasien per shift',          'url' => '/nurse-assignments'],
                ['icon' => 'bi-emoji-smile',          'color' => 'var(--rose)',    'title' => 'ANC (Antenatal)', 'desc' => 'Kunjungan hamil, TFU, DJJ, Hb, TT, Fe',             'url' => '/anc-records'],
                ['icon' => 'bi-graph-up',             'color' => 'var(--indigo)',  'title' => 'Partograf',       'desc' => 'Pembukaan, penurunan, kontraksi, oksitosin',        'url' => '/partographs'],
                ['icon' => 'bi-heart',                'color' => 'var(--rose)',    'title' => 'Perawatan Nifas', 'desc' => 'TFU, lochia, ASI, kontrasepsi pasca lahir',         'url' => '/postnatal-records'],
                ['icon' => 'bi-shield-plus',          'color' => 'var(--emerald)', 'title' => 'Imunisasi Bayi',  'desc' => 'BCG, DPT, Polio, Hepatitis B, Campak',              'url' => '/baby-immunizations'],
                ['icon' => 'bi-cup-straw',            'color' => 'var(--amber)',   'title' => 'Diet Order',      'desc' => 'Order diet pasien rawat inap & monitoring',         'url' => '/diet-orders'],
            ],
            'Penunjang & Ambulans' => [
                ['icon' => 'bi-broadcast',            'color' => 'var(--indigo)',  'title' => 'Radiologi',       'desc' => 'Thorax, CT Scan, MRI, USG, Mamografi',              'url' => '/radiologies'],
                ['icon' => 'bi-droplet-fill',         'color' => 'var(--rose)',    'title' => 'Bank Darah',      'desc' => 'Stok darah A/B/AB/O ± dengan expiry tracking',      'url' => '/blood-donations'],
                ['icon' => 'bi-truck',                'color' => 'var(--amber)',   'title' => 'Armada Ambulans', 'desc' => 'Unit, supir, status (tersedia/bertugas)',           'url' => '/ambulances'],
                ['icon' => 'bi-telephone-forward',    'color' => 'var(--blue)',    'title' => 'Panggilan Darurat','desc'=> 'Dispatch, alamat jemput, status realtime',           'url' => '/ambulance-calls'],
            ],
            'Keuangan & Akuntansi' => [
                ['icon' => 'bi-list-columns',         'color' => 'var(--indigo)',  'title' => 'Chart of Accounts','desc'=> '20 akun (aset, liabilitas, ekuitas, P&L)',           'url' => '/chart-of-accounts'],
                ['icon' => 'bi-bar-chart-line',       'color' => 'var(--blue)',    'title' => 'Laporan Keuangan','desc' => 'Revenue per bulan, top treatments, status',         'url' => '/reports'],
                ['icon' => 'bi-calculator',           'color' => 'var(--teal)',    'title' => 'Cost Estimate',   'desc' => 'Estimasi biaya tindakan untuk pasien',              'url' => '/cost-estimates'],
            ],
            'HR & Logistik' => [
                ['icon' => 'bi-person-workspace',     'color' => 'var(--blue)',    'title' => 'Karyawan',        'desc' => 'NIP, jabatan, gaji pokok, bank, BPJS TK',           'url' => '/employees'],
                ['icon' => 'bi-clock-history',        'color' => 'var(--amber)',   'title' => 'Absensi',         'desc' => 'Check-in/out, present/late/absent/sick',            'url' => '/attendances'],
                ['icon' => 'bi-calendar-x',           'color' => 'var(--rose)',    'title' => 'Cuti & Izin',     'desc' => 'Annual, sick, maternity, paternity leave',          'url' => '/leaves'],
                ['icon' => 'bi-box-seam',             'color' => 'var(--teal)',    'title' => 'Aset & Inventaris','desc'=> 'Aset medis, IT, kendaraan, furniture, kondisi',      'url' => '/assets'],
                ['icon' => 'bi-bag-check',            'color' => 'var(--emerald)', 'title' => 'Purchase Order',  'desc' => 'PO supplier, items, draft→approved→received',       'url' => '/purchase-orders'],
            ],
        ];
    @endphp
    <section class="more-modules" id="modul-lainnya">
        <div class="section-inner text-center" style="margin-bottom:3.5rem;">
            <div class="section-label">Modul Lainnya</div>
            <div class="section-title">Plus 34+ Modul untuk Operasional Lengkap</div>
            <div class="section-desc center-block">
                Selain 10 fitur utama di atas, sistem ini sudah dilengkapi modul pendukung lengkap untuk operasional rumah sakit dari hulu ke hilir — dari pendaftaran hingga akuntansi.
            </div>
        </div>
        <div class="section-inner">
            @foreach($moreModules as $groupName => $items)
                <div class="module-group fade-up">
                    <div class="module-group-label">{{ $groupName }}</div>
                    <div class="module-mini-grid">
                        @foreach($items as $m)
                            @auth
                                <a href="{{ $m['url'] }}" class="module-mini">
                            @else
                                <div class="module-mini" style="cursor:default;">
                            @endauth
                                <div class="mm-icon" style="background:{{ $m['color'] }}14;color:{{ $m['color'] }};">
                                    <i class="bi {{ $m['icon'] }}" aria-hidden="true"></i>
                                </div>
                                <div class="mm-body">
                                    <div class="mm-title">{{ $m['title'] }}</div>
                                    <div class="mm-desc">{{ $m['desc'] }}</div>
                                </div>
                            @auth
                                </a>
                            @else
                                </div>
                            @endauth
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ TRUST STRIP ============ --}}
    @if(cms('trust.is_active', true))
    <div class="trust-strip">
        <div class="trust-inner">
            <div class="trust-label">{{ cms('trust.title', 'Kompatibel dengan Sistem Nasional') }}</div>
            @foreach(cms('trust.meta.badges', []) as $badge)
                <div class="trust-badge"><i class="bi {{ $badge['icon'] ?? 'bi-check' }}" aria-hidden="true"></i> {{ $badge['text'] ?? '' }}</div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ============ VIDEO DEMO ============ --}}
    @if(cms('video.is_active', true))
    <section class="video-section" id="video">
        <div class="section-inner text-center" style="margin-bottom:2.5rem;">
            <div class="section-label">{{ cms('video.subtitle', 'Lihat dalam Aksi') }}</div>
            <div class="section-title">{{ cms('video.title', 'Tour Singkat Platform Kami') }}</div>
            <div class="section-desc center-block">{{ cms('video.content', '') }}</div>
        </div>
        <div class="video-wrap fade-up" data-video-open>
            <img src="{{ $videoPoster }}" alt="Video demo {{ $brandName }}" loading="lazy">
            <span class="video-caption"><i class="bi bi-clock" aria-hidden="true"></i> {{ cms('video.meta.caption', 'Durasi 2 menit') }}</span>
            <button type="button" class="play-btn" aria-label="Putar video demo">
                <i class="bi bi-play-fill" aria-hidden="true"></i>
            </button>
        </div>
        <div class="video-highlights fade-up">
            @foreach(cms('video.meta.highlights', []) as $hl)
                <span class="highlight-chip"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> {{ $hl }}</span>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ============ MODULES ============ --}}
    @if(cms('modules.is_active', true))
    <section class="section" id="modul" style="background:var(--slate-50);">
        <div class="section-inner text-center">
            <div class="section-label">{{ cms('modules.subtitle', 'Modul Lengkap') }}</div>
            <div class="section-title">{{ cms('modules.title') }}</div>
            <div class="section-desc center-block">{{ cms('modules.content') }}</div>
        </div>
        <div class="section-inner">
            <div class="modules-grid">
                @foreach(cms('modules.meta.items', []) as $m)
                    <div class="module-card fade-up">
                        <div class="module-icon {{ $m['color'] ?? 'grad-blue' }}"><i class="bi {{ $m['icon'] ?? 'bi-box' }}" aria-hidden="true"></i></div>
                        <div class="module-title">{{ $m['title'] ?? '' }}</div>
                        <div class="module-desc">{{ $m['desc'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ FEATURES ============ --}}
    @if(cms('features.is_active', true))
    <section class="section" id="fitur">
        <div class="section-inner text-center">
            <div class="section-label">{{ cms('features.subtitle', 'Fitur Unggulan') }}</div>
            <div class="section-title">{{ cms('features.title') }}</div>
            <div class="section-desc center-block">{{ cms('features.content') }}</div>
        </div>
        <div class="section-inner">
            <div class="features-grid">
                @foreach(cms('features.meta.items', []) as $i => $f)
                    @php $color = $f['color'] ?? '#2563eb'; @endphp
                    <div class="feature-card fade-up" style="transition-delay:{{ $i * 0.05 }}s;">
                        <div class="fc-icon" style="background:{{ $color }}14;color:{{ $color }};"><i class="bi {{ $f['icon'] ?? 'bi-stars' }}" aria-hidden="true"></i></div>
                        <div class="fc-title">{{ $f['title'] ?? '' }}</div>
                        <div class="fc-desc">{{ $f['desc'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ SHOWCASE ============ --}}
    @if(cms('showcase.is_active', true))
    <section class="showcase">
        <div class="showcase-inner">
            <div class="text-center" style="margin-bottom:3rem;">
                <div class="section-label">{{ cms('showcase.subtitle', 'Lihat Tampilannya') }}</div>
                <div class="section-title">{{ cms('showcase.title') }}</div>
                <div class="section-desc center-block">{{ cms('showcase.content') }}</div>
            </div>
            <div class="showcase-grid">
                @foreach(cms('showcase.meta.items', []) as $i => $s)
                    <div class="showcase-card fade-up" style="transition-delay:{{ $i * 0.1 }}s;">
                        <div class="sc-img"><img src="{{ $s['img'] ?? '' }}" alt="{{ $s['title'] ?? '' }}" loading="lazy"></div>
                        <div class="sc-body">
                            <div class="sc-title">{{ $s['title'] ?? '' }}</div>
                            <div class="sc-desc">{{ $s['desc'] ?? '' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ STATS BANNER ============ --}}
    @if(cms('stats.is_active', true))
    <section class="stats-banner">
        <div class="stats-grid">
            @foreach(cms('stats.meta.items', []) as $i => $s)
                <div class="fade-up" style="transition-delay:{{ $i * 0.1 }}s;">
                    <div class="sb-icon"><i class="bi {{ $s['icon'] ?? 'bi-star' }}" aria-hidden="true"></i></div>
                    <div class="sb-num">{{ $autoStat($s['value'] ?? '-') }}</div>
                    <div class="sb-lbl">{{ $s['label'] ?? '' }}</div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ============ TESTIMONIALS ============ --}}
    @if(cms('testimonials.is_active', true))
    <section class="section" id="testimoni">
        <div class="section-inner text-center">
            <div class="section-label">{{ cms('testimonials.subtitle', 'Testimoni') }}</div>
            <div class="section-title">{{ cms('testimonials.title') }}</div>
            <div class="section-desc center-block">{{ cms('testimonials.content') }}</div>
        </div>
        <div class="section-inner">
            <div class="testimonial-grid">
                @foreach(cms('testimonials.meta.items', []) as $i => $t)
                    <div class="testimonial-card fade-up" style="transition-delay:{{ $i * 0.1 }}s;">
                        <div class="tq">{{ $t['quote'] ?? '' }}</div>
                        <div class="ta">
                            @if(! empty($t['avatar']))
                                <img class="ta-avatar" src="{{ $t['avatar'] }}" alt="Foto {{ $t['name'] ?? '' }}" loading="lazy">
                            @else
                                <div class="ta-avatar"></div>
                            @endif
                            <div>
                                <div class="ta-name">{{ $t['name'] ?? '' }}</div>
                                <div class="ta-role">{{ $t['role'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ INSIGHTS / BLOG ============ --}}
    @if(cms('insights.is_active', true))
    <section class="section" id="blog" style="background:var(--slate-50);">
        <div class="section-inner text-center">
            <div class="section-label">{{ cms('insights.subtitle', 'Insights') }}</div>
            <div class="section-title">{{ cms('insights.title') }}</div>
            <div class="section-desc center-block">{{ cms('insights.content') }}</div>
        </div>
        <div class="section-inner">
            <div class="insights-grid">
                @foreach(cms('insights.meta.items', []) as $i => $a)
                    <a href="{{ $a['url'] ?? '#' }}" class="insight-card fade-up" style="transition-delay:{{ $i * 0.1 }}s;">
                        <div class="ic-img"><img src="{{ $a['img'] ?? '' }}" alt="{{ $a['title'] ?? '' }}" loading="lazy"></div>
                        <div class="ic-body">
                            <span class="ic-tag">{{ $a['tag'] ?? '' }}</span>
                            <div class="ic-title">{{ $a['title'] ?? '' }}</div>
                            <div class="ic-excerpt">{{ $a['excerpt'] ?? '' }}</div>
                            <div class="ic-meta">
                                <span><i class="bi bi-calendar3" aria-hidden="true"></i> {{ $a['duration'] ?? '' }}</span>
                                <span class="read-more">Baca →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ PARTNERS ============ --}}
    @if(cms('partners.is_active', true))
    <section class="section" style="padding-top:0;">
        <div class="section-inner text-center">
            <div style="font-size:0.78rem;font-weight:700;color:var(--slate-500);margin-bottom:1.5rem;text-transform:uppercase;letter-spacing:0.06em;">{{ cms('partners.title', 'Kompatibel dengan') }}</div>
            <div class="partners-row">
                @foreach(cms('partners.meta.items', []) as $p)
                    <span class="partner-logo">{{ $p }}</span>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ CTA ============ --}}
    @if(cms('cta.is_active', true))
    <section class="cta-section">
        <div class="cta-card fade-up">
            <h2>{!! nl2br(e(cms('cta.title'))) !!}</h2>
            <p>{{ cms('cta.content') }}</p>
            <div class="cta-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-cta" style="font-size:0.95rem;padding:0.75rem 1.8rem;">
                        <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i> Buka Dashboard
                    </a>
                @else
                    <a href="{{ cms('cta.button_url', '/register') }}" class="btn btn-cta" style="font-size:0.95rem;padding:0.75rem 1.8rem;">
                        <i class="bi bi-rocket-takeoff-fill" aria-hidden="true"></i> {{ cms('cta.button_text', 'Mulai Trial Gratis') }}
                    </a>
                    <a href="{{ cms('cta.meta.cta_secondary_url', 'https://wa.me/' . $waNumber) }}" target="_blank" rel="noopener" class="btn btn-ghost" style="font-size:0.95rem;padding:0.75rem 1.8rem;">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i> {{ cms('cta.meta.cta_secondary_text', 'Request Demo') }}
                    </a>
                @endauth
            </div>
        </div>
    </section>
    @endif

    {{-- ============ FOOTER ============ --}}
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <a href="/" class="footer-brand">
                    @if($brandLogo)
                        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height:32px;width:auto;max-width:140px;object-fit:contain;background:rgba(255,255,255,0.95);padding:4px 8px;border-radius:6px;">
                    @else
                        <span class="brand-dot"><i class="bi bi-hospital" aria-hidden="true"></i></span>
                        {{ cms('footer.title', $brandName) }}
                    @endif
                </a>
                <p class="footer-tagline">{{ cms('footer.subtitle', $brandTagline) }}</p>
                <div class="footer-contact">
                    <div><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> {{ cms('footer.meta.address', 'Jakarta, Indonesia') }}</div>
                    <div><i class="bi bi-telephone-fill" aria-hidden="true"></i> {{ cms('footer.meta.phone', '+62 812-9605-2010') }}</div>
                    <div><i class="bi bi-envelope-fill" aria-hidden="true"></i> {{ cms('footer.meta.email', 'info@simrs.id') }}</div>
                </div>
            </div>
            <div>
                <h4>Produk</h4>
                <ul class="footer-links">
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#modul">Modul</a></li>
                    <li><a href="#video">Demo</a></li>
                    <li><a href="{{ route('docs') }}">Dokumentasi</a></li>
                </ul>
            </div>
            <div>
                <h4>Sumber Daya</h4>
                <ul class="footer-links">
                    <li><a href="#blog">Insights</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                    <li><a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener">Hubungi Kami</a></li>
                    @guest
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                    @endguest
                </ul>
            </div>
            <div>
                <h4>Standar &amp; Integrasi</h4>
                <div class="footer-cert">
                    @foreach(cms('footer.meta.cert_badges', []) as $cb)
                        <span class="cert-badge"><i class="bi {{ $cb['icon'] ?? 'bi-check' }}" aria-hidden="true"></i> {{ $cb['text'] ?? '' }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} {{ $brandName }}. Hak cipta dilindungi.</div>
            <div class="social-links">
                @foreach(cms('footer.meta.social', []) as $sc)
                    <a href="{{ $sc['url'] ?? '#' }}" target="_blank" rel="noopener" aria-label="{{ $sc['label'] ?? '' }}">
                        <i class="bi {{ $sc['icon'] ?? 'bi-link' }}" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </footer>

    {{-- ============ FLOATING WHATSAPP ============ --}}
    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}" target="_blank" rel="noopener" class="floating-wa" aria-label="Chat via WhatsApp">
        <i class="bi bi-whatsapp" aria-hidden="true"></i>
        <span>Tanya kami via WhatsApp</span>
    </a>

    {{-- ============ PWA INSTALL BUTTON (hidden default, muncul saat installable) ============ --}}
    <button type="button" id="pwa-install-btn" aria-label="Pasang aplikasi" style="position:fixed;bottom:24px;left:24px;z-index:998;display:none;align-items:center;gap:8px;padding:12px 18px;border-radius:50px;background:#1e293b;color:#fff;font-weight:600;font-size:0.84rem;border:none;cursor:pointer;box-shadow:0 10px 25px rgba(15,23,42,0.3);font-family:inherit;">
        <i class="bi bi-download" aria-hidden="true"></i> Pasang Aplikasi
    </button>
    <style>#pwa-install-btn.show { display: inline-flex !important; }</style>

    {{-- ============ VIDEO MODAL ============ --}}
    <div class="video-modal" id="videoModal" role="dialog" aria-modal="true" aria-label="Video demo">
        <div class="video-modal-inner">
            <button type="button" class="video-modal-close" id="videoClose" aria-label="Tutup video">
                <i class="bi bi-x-circle" aria-hidden="true"></i> Tutup
            </button>
            <iframe id="videoFrame" src="" title="Demo {{ $brandName }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('.fade-up').forEach(function (el) { io.observe(el); });

            var navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function () {
                navbar.classList.toggle('scrolled', window.scrollY > 50);
            }, { passive: true });

            var toggle = document.getElementById('navToggle');
            var navLinks = document.getElementById('navLinks');
            if (toggle) {
                toggle.addEventListener('click', function () { navLinks.classList.toggle('open'); });
                navLinks.querySelectorAll('a').forEach(function (a) {
                    a.addEventListener('click', function () { navLinks.classList.remove('open'); });
                });
            }

            var modal = document.getElementById('videoModal');
            var frame = document.getElementById('videoFrame');
            var closeBtn = document.getElementById('videoClose');
            var ytId = @json($youtubeId);
            var ytUrl = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0&modestbranding=1';
            document.querySelectorAll('[data-video-open]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    frame.src = ytUrl;
                    modal.classList.add('open');
                    document.body.style.overflow = 'hidden';
                });
            });
            function closeModal() {
                modal.classList.remove('open');
                frame.src = '';
                document.body.style.overflow = '';
            }
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('open')) closeModal(); });
        });
    </script>

    @include('partials.pwa-register')
</body>
</html>
