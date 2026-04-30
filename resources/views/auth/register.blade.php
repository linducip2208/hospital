@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <div class="card shadow">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-hospital fs-1 text-primary"></i>
                <h4 class="mt-2 fw-bold">Daftar</h4>
                <p class="text-muted small">Buat akun baru untuk memulai</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Pendaftaran gagal!</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Nama lengkap Anda">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Username (optional) --}}
                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="text-muted">(opsional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" name="username" id="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" autocomplete="username"
                               placeholder="username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autocomplete="email"
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
                               required autocomplete="new-password"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control"
                               required autocomplete="new-password"
                               placeholder="Ulangi kata sandi">
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                        <i class="bi bi-person-plus me-2"></i> Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-3">
        <p class="mb-0 text-muted small">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
        </p>
    </div>
@endsection
