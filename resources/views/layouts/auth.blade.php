<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') - {{ config('app.name', 'Hospital App') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                <i class="bi bi-hospital fs-4 me-2"></i>
                <span class="fs-5 fw-semibold">{{ config('app.name', 'Hospital') }}</span>
            </a>
        </div>
    </nav>

    {{-- Centered Content --}}
    <div class="d-flex align-items-center justify-content-center flex-grow-1 py-5">
        <div class="w-100" style="max-width: 450px;">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center text-muted py-3 small border-top bg-white mt-auto">
        &copy; {{ date('Y') }} {{ config('app.name', 'Hospital App') }}. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
