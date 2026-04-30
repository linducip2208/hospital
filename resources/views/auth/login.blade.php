@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-hospital fs-1 text-primary"></i>
                <h4 class="mt-2 fw-bold">Masuk</h4>
                <p class="text-muted small">Silakan masuk ke akun Anda</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Gagal masuk!</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus autocomplete="email"
                               placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="current-password"
                               placeholder="Kata sandi Anda">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Remember --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-3">
        <p class="mb-0 text-muted small">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar Sekarang</a>
        </p>
    </div>
@endsection
