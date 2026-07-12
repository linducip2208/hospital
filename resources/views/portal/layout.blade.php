<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php $brandName = cms('branding.title', config('app.name', 'SIMRS')); $primary = cms('branding.meta.primary_color', '#2563eb'); $accent = cms('branding.meta.accent_color', '#06b6d4'); @endphp
    <title>@yield('title', 'Portal Pasien') — {{ $brandName }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary: {{ $primary }}; --accent: {{ $accent }}; }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #f1f5f9; color: #1f2937; margin: 0; }
        .portal-nav { background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; }
        .portal-nav .container { display: flex; align-items: center; justify-content: space-between; height: 62px; }
        .portal-brand { color: #fff; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .portal-tabs { background: #fff; border-bottom: 1px solid #e5e7eb; overflow-x: auto; }
        .portal-tabs .container { display: flex; gap: .25rem; }
        .portal-tabs a { padding: .85rem 1rem; color: #4b5563; font-weight: 600; text-decoration: none; white-space: nowrap; border-bottom: 3px solid transparent; }
        .portal-tabs a.active, .portal-tabs a:hover { color: var(--primary); border-bottom-color: var(--primary); }
        .stat-card { background: #fff; border-radius: 14px; border: 1px solid #eef0f3; padding: 1.25rem; }
        .stat-card .val { font-size: 1.6rem; font-weight: 800; }
        .card-soft { background: #fff; border: 1px solid #eef0f3; border-radius: 14px; }
        @media (max-width: 640px) { .portal-nav .container, .portal-tabs .container { padding-left: .75rem; padding-right: .75rem; } }
    </style>
</head>
<body>
    <nav class="portal-nav">
        <div class="container">
            <a href="{{ route('portal.dashboard') }}" class="portal-brand"><i class="bi bi-heart-pulse-fill"></i> {{ $brandName }} · Portal Pasien</a>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-sm-inline small">{{ Auth::guard('patient')->user()?->name }}</span>
                <form action="{{ route('portal.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-light"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="portal-tabs">
        <div class="container">
            <a href="{{ route('portal.dashboard') }}" class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}"><i class="bi bi-grid"></i> Dashboard</a>
            <a href="{{ route('portal.appointments.index') }}" class="{{ request()->routeIs('portal.appointments.*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Janji Temu</a>
            <a href="{{ route('portal.medical-records.index') }}" class="{{ request()->routeIs('portal.medical-records.*') ? 'active' : '' }}"><i class="bi bi-file-medical"></i> Rekam Medis</a>
            <a href="{{ route('portal.invoices.index') }}" class="{{ request()->routeIs('portal.invoices.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Tagihan</a>
        </div>
    </div>

    <main class="container py-4">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
