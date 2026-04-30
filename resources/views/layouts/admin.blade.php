<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Hospital App') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <nav class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px; min-height: 100vh;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <i class="bi bi-hospital fs-4 me-2"></i>
                <span class="fs-5 fw-semibold">{{ config('app.name', 'Hospital') }}</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('patients.index') }}" class="nav-link text-white {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Pasien
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctors.index') }}" class="nav-link text-white {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Dokter
                    </a>
                </li>
                <li>
                    <a href="{{ route('treatments.index') }}" class="nav-link text-white {{ request()->routeIs('treatments.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule me-2"></i> Treatment
                    </a>
                </li>
                <li>
                    <a href="{{ route('appointments.index') }}" class="nav-link text-white {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> Appointment
                    </a>
                </li>
                <li>
                    <a href="{{ route('medical-records.index') }}" class="nav-link text-white {{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                        <i class="bi bi-file-medical me-2"></i> Rekam Medis
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="nav-link text-white {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack me-2"></i> Pembayaran
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <strong>{{ Auth::user()?->name ?? 'Admin' }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="flex-grow-1 d-flex flex-column" style="min-height: 100vh;">
            <main class="flex-grow-1 p-4 bg-light">
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
            </main>

            <footer class="text-center text-muted py-3 small border-top bg-white">
                &copy; {{ date('Y') }} {{ config('app.name', 'Hospital App') }}. All rights reserved.
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
