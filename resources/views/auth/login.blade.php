@php
    $demoAccounts = [
        ['role' => 'Developer', 'email' => 'developer@hospital.test', 'password' => 'password'],
        ['role' => 'Admin', 'email' => 'admin@hospital.test', 'password' => 'password'],
        ['role' => 'Dokter', 'email' => 'dr.andi@hospital.test', 'password' => 'password'],
        ['role' => 'Perawat', 'email' => 'nurse.rina@hospital.test', 'password' => 'password'],
        ['role' => 'Apoteker', 'email' => 'apt.dian@hospital.test', 'password' => 'password'],
        ['role' => 'Kasir', 'email' => 'cashier@hospital.test', 'password' => 'password'],
        ['role' => 'Finance', 'email' => 'finance@hospital.test', 'password' => 'password'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ config('app.name', 'SIMRS Hospital') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --brand: #2563eb; --brand-dark: #0f172a; --brand-accent: #06b6d4; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; color: #172033; background: #f7f9fc; font-family: 'Plus Jakarta Sans', Inter, system-ui, sans-serif; }
        .auth-shell { min-height: 100vh; display: grid; grid-template-columns: minmax(0, 1fr) minmax(420px, .85fr); }
        .auth-hero { position: relative; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 48px clamp(32px, 6vw, 96px); color: #fff; background: radial-gradient(circle at 12% 18%, rgba(6,182,212,.32), transparent 28%), linear-gradient(145deg, #2563eb 0%, #172554 53%, #0f172a 100%); }
        .auth-hero::before, .auth-hero::after { content: ''; position: absolute; border: 1px solid rgba(255,255,255,.13); border-radius: 50%; pointer-events: none; }
        .auth-hero::before { width: 520px; height: 520px; right: -220px; top: -150px; }
        .auth-hero::after { width: 360px; height: 360px; left: -170px; bottom: -190px; }
        .hero-content, .hero-brand, .hero-footer { position: relative; z-index: 1; }
        .hero-brand { display: inline-flex; align-items: center; gap: 12px; color: #fff; text-decoration: none; font-size: 1.35rem; font-weight: 800; }
        .hero-logo { display: grid; width: 44px; height: 44px; place-items: center; border-radius: 13px; background: linear-gradient(135deg, #38bdf8, #2563eb); box-shadow: 0 12px 30px rgba(0,0,0,.2); font-size: 1.4rem; }
        .hero-content { max-width: 600px; padding: 64px 0; }
        .hero-content h1 { max-width: 560px; margin: 0 0 20px; font-size: clamp(2.45rem, 4vw, 4.65rem); line-height: 1.06; letter-spacing: -.045em; font-weight: 800; }
        .hero-content p { max-width: 520px; margin-bottom: 32px; color: rgba(239,246,255,.82); font-size: 1.08rem; line-height: 1.75; }
        .benefits { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; max-width: 580px; }
        .benefit { min-height: 112px; padding: 16px; border: 1px solid rgba(255,255,255,.14); border-radius: 16px; background: rgba(255,255,255,.09); backdrop-filter: blur(12px); }
        .benefit i { display: block; margin-bottom: 14px; color: #67e8f9; font-size: 1.35rem; }
        .benefit span { display: block; color: #fff; font-size: .82rem; line-height: 1.4; font-weight: 700; }
        .hero-footer { color: rgba(219,234,254,.62); font-size: .78rem; }
        .auth-form-panel { display: flex; align-items: center; justify-content: center; padding: 48px clamp(24px, 6vw, 88px); background: #fff; }
        .auth-form { width: 100%; max-width: 500px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 7px; margin-bottom: 14px; color: var(--brand); font-size: .76rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .auth-form h2 { margin-bottom: 8px; color: #111827; font-size: clamp(2rem, 4vw, 2.8rem); letter-spacing: -.04em; font-weight: 800; }
        .auth-form .subcopy { margin-bottom: 30px; color: #64748b; }
        .auth-form .subcopy a { color: var(--brand); font-weight: 700; text-decoration: none; }
        .field-label { display: block; margin-bottom: 8px; color: #334155; font-size: .84rem; font-weight: 700; }
        .field-wrap { position: relative; }
        .field-wrap i { position: absolute; left: 16px; top: 50%; z-index: 1; color: #64748b; transform: translateY(-50%); }
        .field-wrap input { min-height: 50px; padding-left: 46px; border: 1.5px solid #dbe3ef; border-radius: 12px; }
        .field-wrap input:focus { border-color: var(--brand); box-shadow: 0 0 0 4px rgba(37,99,235,.12); }
        .btn-brand { min-height: 50px; border: 0; border-radius: 12px; background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 12px 24px rgba(37,99,235,.24); font-weight: 800; }
        .btn-brand:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); }
        .demo-box { margin-top: 28px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 14px; background: #f8fafc; }
        .demo-title { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #1e293b; font-weight: 800; }
        .demo-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 7px 16px; color: #64748b; font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: .69rem; }
        .demo-grid strong { color: #334155; }
        @media (max-width: 900px) { .auth-shell { grid-template-columns: 1fr; } .auth-hero { min-height: 360px; padding: 28px 24px; } .hero-content { padding: 42px 0 10px; } .hero-content h1 { font-size: 2.35rem; } .hero-content p { margin-bottom: 22px; font-size: .94rem; } .hero-footer { display: none; } .auth-form-panel { padding: 42px 24px 56px; } }
        @media (max-width: 520px) { .benefits { gap: 8px; } .benefit { min-height: 94px; padding: 12px; } .benefit i { margin-bottom: 9px; } .benefit span { font-size: .72rem; } .demo-grid { grid-template-columns: 1fr; } }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; } }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="auth-hero" aria-label="Tentang {{ config('app.name', 'SIMRS Hospital') }}">
            <a class="hero-brand" href="{{ route('home') }}"><span class="hero-logo"><i class="bi bi-hospital" aria-hidden="true"></i></span>{{ config('app.name', 'SIMRS Hospital') }}</a>
            <div class="hero-content">
                <div class="eyebrow text-info-emphasis"><i class="bi bi-shield-check" aria-hidden="true"></i> Platform operasional terintegrasi</div>
                <h1>Rawat pasien lebih baik, kelola rumah sakit lebih cerdas.</h1>
                <p>Satukan pelayanan klinis, administrasi, farmasi, keuangan, dan laporan dalam satu sistem yang mudah dipantau setiap hari.</p>
                <div class="benefits">
                    <div class="benefit"><i class="bi bi-activity" aria-hidden="true"></i><span>Alur klinis terhubung</span></div>
                    <div class="benefit"><i class="bi bi-bar-chart-line" aria-hidden="true"></i><span>KPI real-time</span></div>
                    <div class="benefit"><i class="bi bi-lock" aria-hidden="true"></i><span>Akses berbasis peran</span></div>
                </div>
            </div>
            <div class="hero-footer">© {{ date('Y') }} {{ config('app.name', 'SIMRS Hospital') }} · Sistem Informasi Rumah Sakit</div>
        </section>
        <section class="auth-form-panel" aria-label="Form masuk">
            <div class="auth-form">
                <div class="eyebrow"><i class="bi bi-person-check" aria-hidden="true"></i> Area staf</div>
                <h2>Masuk</h2>
                <p class="subcopy">Belum punya akun? <a href="{{ route('register') }}">Daftar akun staf</a></p>

                @if($errors->any())
                    <div class="alert alert-danger d-flex gap-2" role="alert"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i><div><strong>Gagal masuk.</strong><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3"><label class="field-label" for="email">Email</label><div class="field-wrap"><i class="bi bi-envelope" aria-hidden="true"></i><input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@rumahsakit.id"></div>@error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="field-label" for="password">Kata sandi</label><div class="field-wrap"><i class="bi bi-lock" aria-hidden="true"></i><input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Masukkan kata sandi"></div>@error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-4"><label class="form-check d-flex align-items-center gap-2 m-0 small text-secondary"><input type="checkbox" name="remember" class="form-check-input" value="1"> Ingat saya</label><a class="small text-decoration-none" href="{{ route('home') }}#kontak">Butuh bantuan?</a></div>
                    <button class="btn btn-primary btn-brand w-100" type="submit"><i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Masuk ke Dashboard</button>
                </form>

                <div class="demo-box" aria-label="Akun demo">
                    <div class="demo-title"><span aria-hidden="true">🧪</span> Demo Login</div>
                    <div class="demo-grid">@foreach($demoAccounts as $account)<div><strong>{{ $account['role'] }}:</strong> {{ $account['email'] }} / {{ $account['password'] }}</div>@endforeach</div>
                    <small class="d-block mt-3 text-secondary">Gunakan hanya untuk demo. Ganti atau hapus password default sebelum production.</small>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
