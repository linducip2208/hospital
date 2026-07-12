<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php $brandName = cms('branding.title', config('app.name', 'SIMRS')); $primary = cms('branding.meta.primary_color', '#2563eb'); $accent = cms('branding.meta.accent_color', '#06b6d4'); @endphp
    <title>Daftar Portal Pasien — {{ $brandName }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --primary: {{ $primary }}; --accent: {{ $accent }}; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; margin: 0; }
        .split { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }
        .hero { position: relative; overflow: hidden; color: #fff; padding: 3rem;
            background: linear-gradient(135deg, var(--primary), var(--accent), #0f172a);
            display: flex; flex-direction: column; justify-content: space-between; }
        .hero .circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,.08); }
        .hero .c1 { width: 320px; height: 320px; top: -80px; right: -80px; }
        .hero .c2 { width: 200px; height: 200px; bottom: 40px; left: -60px; }
        .benefit { background: rgba(255,255,255,.12); backdrop-filter: blur(6px); border-radius: 12px; padding: 1rem; }
        .form-wrap { display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .form-inner { width: 100%; max-width: 420px; }
        .btn-brand { background: var(--primary); color: #fff; font-weight: 600; }
        .btn-brand:hover { filter: brightness(1.08); color: #fff; }
        @media (max-width: 900px) { .split { grid-template-columns: 1fr; } .hero { display: none; } }
    </style>
</head>
<body>
<div class="split">
    <div class="hero">
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <a href="{{ url('/') }}" class="text-white text-decoration-none fw-bold fs-4 position-relative"><i class="bi bi-heart-pulse-fill"></i> {{ $brandName }}</a>
        <div class="position-relative">
            <h2 class="fw-bold display-6 mb-3">Bergabung Sekarang</h2>
            <p class="text-white-50 mb-4" style="max-width:380px">Daftar untuk mengakses layanan kesehatan digital secara mandiri.</p>
            <div class="row g-2" style="max-width:400px">
                <div class="col-4"><div class="benefit text-center"><i class="bi bi-calendar-check fs-4"></i><div class="small mt-1">Janji Temu</div></div></div>
                <div class="col-4"><div class="benefit text-center"><i class="bi bi-file-medical fs-4"></i><div class="small mt-1">Rekam Medis</div></div></div>
                <div class="col-4"><div class="benefit text-center"><i class="bi bi-receipt fs-4"></i><div class="small mt-1">Tagihan</div></div></div>
            </div>
        </div>
        <div class="text-white-50 small position-relative">© {{ date('Y') }} {{ $brandName }} · Powered by Laravel</div>
    </div>

    <div class="form-wrap">
        <div class="form-inner">
            <h1 class="fw-bold mb-1">Daftar</h1>
            <p class="text-muted mb-4">Sudah punya akun? <a href="{{ route('portal.login') }}" class="fw-semibold text-decoration-none">Masuk</a></p>

            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form action="{{ route('portal.register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-3" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control rounded-3">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                    </div>
                </div>
                <button class="btn btn-brand btn-lg w-100 rounded-3">Daftar</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
