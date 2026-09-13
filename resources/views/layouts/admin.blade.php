<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" id="htmlRoot">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Hospital') }}</title>
    @include('partials.pwa-head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sidebar-w: 265px;
            --sidebar-collapsed-w: 72px;
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --accent: #06b6d4;
            --accent-light: #22d3ee;
            --navy-900: #0a0f1e;
            --navy-800: #0f172a;
            --navy-700: #111c35;
            --navy-600: #1a2744;
            --silver: #c0c5d0;
            --silver-light: #e8ecf1;
            --success: #10b981;
            --success-light: #34d399;
            --warning: #f59e0b;
            --warning-light: #fbbf24;
            --danger: #ef4444;
            --danger-light: #f87171;
            --info: #6366f1;
            --info-light: #818cf8;
            --bg: #f6f8fb;
            --surface: #ffffff;
            --border: #e8edf3;
            --text: #1e293b;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --radius-lg: 16px;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-xs: 6px;
            --shadow-sm: 0 1px 3px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.03);
            --shadow: 0 4px 12px rgba(15,23,42,0.06), 0 1px 4px rgba(15,23,42,0.04);
            --shadow-lg: 0 12px 32px rgba(15,23,42,0.08), 0 4px 8px rgba(15,23,42,0.04);
            --shadow-xl: 0 20px 48px rgba(15,23,42,0.1), 0 8px 16px rgba(15,23,42,0.04);
            --transition: 0.2s cubic-bezier(0.4,0,0.2,1);
            --transition-slow: 0.35s cubic-bezier(0.4,0,0.2,1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background var(--transition), color var(--transition);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body.dark {
            --bg: #0b1120;
            --surface: #111c35;
            --border: rgba(255,255,255,0.06);
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --text-light: #64748b;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.2);
            --shadow: 0 4px 12px rgba(0,0,0,0.25);
            --shadow-lg: 0 12px 32px rgba(0,0,0,0.3);
            --shadow-xl: 0 20px 48px rgba(0,0,0,0.35);
        }

        /* ── Background ambient glow ── */
        body::before {
            content: '';
            position: fixed; top: -30%; right: -20%;
            width: 900px; height: 900px;
            background: radial-gradient(circle, rgba(37,99,235,0.06) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none; z-index: 0;
        }
        body::after {
            content: '';
            position: fixed; bottom: -20%; left: -10%;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(6,182,212,0.04) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none; z-index: 0;
        }
        body.dark::before { background: radial-gradient(circle, rgba(37,99,235,0.08) 0%, transparent 70%); }
        body.dark::after { background: radial-gradient(circle, rgba(6,182,212,0.06) 0%, transparent 70%); }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(175deg, var(--navy-900) 0%, var(--navy-800) 40%, var(--navy-700) 100%);
            position: fixed; left: 0; top: 0; bottom: 0; z-index: 1040;
            transition: width var(--transition-slow), transform var(--transition-slow);
            border-right: 1px solid rgba(255,255,255,0.04);
            display: flex; flex-direction: column;
            overflow: hidden;
        }
        .sidebar::before {
            content: '';
            position: absolute; top: -60px; right: -30px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(37,99,235,0.12) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .sidebar::after {
            content: '';
            position: absolute; bottom: 15%; left: -40px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(6,182,212,0.06) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        .sidebar-brand {
            padding: 1.35rem 1.35rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            position: relative; z-index: 1;
            display: flex; align-items: center; gap: 0;
        }
        .sidebar-brand a {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; color: #fff; width: 100%;
        }
        .sidebar-brand .logo-icon {
            width: 38px; height: 38px; min-width: 38px;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.05rem; color: #fff;
            box-shadow: 0 0 20px rgba(37,99,235,0.35), 0 0 40px rgba(37,99,235,0.1);
        }
        .sidebar-brand .brand-text {
            font-weight: 800; font-size: 0.92rem;
            letter-spacing: -0.01em;
            white-space: nowrap; overflow: hidden;
            transition: opacity var(--transition-slow), width var(--transition-slow);
        }

        .sidebar-menu {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 0.4rem 0; position: relative; z-index: 1;
        }
        .sidebar-menu::-webkit-scrollbar { width: 3px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 10px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }

        .menu-group { border-bottom: 1px solid rgba(255,255,255,0.02); padding: 0.2rem 0; }
        .menu-group-header {
            padding: 0.65rem 1.35rem 0.2rem;
            font-size: 0.62rem; font-weight: 800; text-transform: uppercase;
            color: rgba(99,102,241,0.5); letter-spacing: 0.12em;
            white-space: nowrap; overflow: hidden;
            transition: opacity var(--transition-slow);
        }

        .sidebar .nav-link {
            color: rgba(226,232,240,0.55) !important;
            padding: 0.52rem 1.35rem;
            font-size: 0.82rem; border-radius: 0;
            transition: all var(--transition);
            display: flex; align-items: center; gap: 0.65rem;
            border-left: 3px solid transparent;
            position: relative; margin: 1px 0;
            white-space: nowrap;
        }
        .sidebar .nav-link:not(.no-hover):hover {
            color: rgba(226,232,240,0.9) !important;
            background: rgba(255,255,255,0.025);
            border-left-color: rgba(37,99,235,0.4);
        }
        .sidebar .nav-link.active {
            color: #fff !important;
            background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.04));
            border-left-color: #3b82f6;
            text-shadow: 0 0 10px rgba(59,130,246,0.3);
        }
        .sidebar .nav-link.active::after {
            content: '';
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            width: 5px; height: 5px;
            background: #3b82f6; border-radius: 50%;
            box-shadow: 0 0 10px rgba(59,130,246,0.6), 0 0 20px rgba(59,130,246,0.3);
            animation: activePulse 2s ease-in-out infinite;
        }
        @keyframes activePulse {
            0%, 100% { opacity: 1; transform: translateY(-50%) scale(1); }
            50% { opacity: 0.5; transform: translateY(-50%) scale(1.4); }
        }

        .sidebar .nav-link i, .sidebar .nav-link .bi {
            font-size: 1rem; width: 21px; text-align: center;
            opacity: 0.6; transition: all var(--transition);
            flex-shrink: 0;
        }
        .sidebar .nav-link.active i,
        .sidebar .nav-link.active .bi { opacity: 1; }

        .sidebar .accordion-button {
            background: transparent !important;
            color: rgba(226,232,240,0.55) !important;
            padding: 0.52rem 1.35rem;
            font-size: 0.82rem; box-shadow: none;
            display: flex; align-items: center; gap: 0.65rem;
            border-left: 3px solid transparent;
            border-radius: 0 !important;
            white-space: nowrap;
        }
        .sidebar .accordion-button::after {
            filter: invert(0.35);
            margin-left: auto; width: 13px; height: 13px;
            background-size: 13px; transition: all var(--transition-slow);
            flex-shrink: 0;
        }
        .sidebar .accordion-button:not(.collapsed) {
            color: rgba(226,232,240,0.9) !important;
            background: rgba(255,255,255,0.02) !important;
            border-left-color: transparent;
        }
        .sidebar .accordion-button:hover {
            color: rgba(226,232,240,0.9) !important;
            background: rgba(255,255,255,0.025) !important;
        }
        .sidebar .accordion-body { padding: 0 0 0.2rem 0; }
        .sidebar .accordion-body .nav-link { padding-left: 2.9rem; font-size: 0.79rem; }
        .sidebar .accordion-item { border: none; background: transparent; }
        /* Force submenu visible when expanded — overrides any stuck inline height from Bootstrap collapse */
        .sidebar .accordion-collapse.show { display: block !important; height: auto !important; visibility: visible !important; overflow: visible !important; }
        .sidebar .accordion-collapse.show .accordion-body { display: block !important; }
        .sidebar .accordion-collapse:not(.show):not(.collapsing) { display: none; }

        .sidebar .nav-link .count-badge {
            margin-left: auto;
            background: rgba(239,68,68,0.2);
            color: #f87171;
            font-size: 0.62rem; font-weight: 700;
            padding: 2px 7px; border-radius: 10px;
            min-width: 20px; text-align: center;
            transition: opacity var(--transition-slow);
        }

        @keyframes countPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            50% { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        }

        .sidebar-user {
            padding: 0.8rem 1rem;
            border-top: 1px solid rgba(255,255,255,0.04);
            position: relative; z-index: 1;
        }
        .sidebar-user .dropdown-toggle {
            color: rgba(148,163,184,0.8);
            text-decoration: none;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.8rem; width: 100%;
        }
        .sidebar-user .dropdown-toggle:hover { color: #e2e8f0; }
        .sidebar-user .avatar {
            width: 30px; height: 30px; min-width: 30px;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb, #6366f1);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.68rem; font-weight: 700;
            box-shadow: 0 0 12px rgba(37,99,235,0.3);
        }
        .sidebar-user .user-info {
            white-space: nowrap; overflow: hidden;
            transition: opacity var(--transition-slow);
        }
        .sidebar-user .user-info .user-name {
            font-size: 0.78rem; color: #e2e8f0; font-weight: 600;
        }
        .sidebar-user .user-info .user-role {
            font-size: 0.64rem; color: #64748b; font-weight: 500;
        }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin var(--transition-slow);
            position: relative; z-index: 1;
        }

        /* ── Top Bar ── */
        .top-bar {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex; align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 1020;
            transition: all var(--transition);
            gap: 1rem;
        }
        body.dark .top-bar {
            background: rgba(17,28,53,0.85);
            border-color: rgba(255,255,255,0.05);
        }

        .top-bar-left {
            display: flex; align-items: center; gap: 1rem;
            flex: 1; min-width: 0;
        }
        .top-bar-right {
            display: flex; align-items: center; gap: 0.5rem;
        }

        .hamburger-btn {
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            cursor: pointer; font-size: 1.1rem;
            transition: all var(--transition);
            flex-shrink: 0;
        }
        .hamburger-btn:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            background: rgba(59,130,246,0.04);
        }
        body.dark .hamburger-btn {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.08);
            color: var(--text-muted);
        }
        body.dark .hamburger-btn:hover {
            border-color: var(--primary-light);
            color: var(--primary-light);
        }

        /* ── Search Bar ── */
        .search-box {
            position: relative; flex: 0 1 380px; min-width: 160px;
        }
        .search-box input {
            width: 100%;
            height: 38px;
            padding: 0 1rem 0 2.5rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            font-size: 0.82rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all var(--transition);
            outline: none;
        }
        .search-box input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
        }
        .search-box input::placeholder { color: var(--text-light); }
        .search-box .search-icon {
            position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%);
            color: var(--text-light); font-size: 0.9rem; pointer-events: none;
        }
        body.dark .search-box input {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.06);
            color: var(--text);
        }
        body.dark .search-box input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        /* ── Live Clock ── */
        .live-clock {
            font-size: 0.78rem; font-weight: 600;
            color: var(--text);
            display: flex; align-items: center; gap: 6px;
            white-space: nowrap;
            padding: 0 0.5rem;
        }
        .live-clock i { color: var(--primary-light); font-size: 0.85rem; }
        .live-clock .time { color: var(--primary); font-weight: 700; }
        body.dark .live-clock .time { color: var(--primary-light); }

        /* ── Top Action Icons ── */
        .top-action {
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            cursor: pointer; font-size: 1rem;
            transition: all var(--transition);
            position: relative;
        }
        .top-action:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            background: rgba(59,130,246,0.04);
        }
        body.dark .top-action {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.08);
        }
        body.dark .top-action:hover {
            border-color: var(--primary-light);
            color: var(--primary-light);
        }
        .top-action .badge-dot {
            position: absolute; top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--danger); border-radius: 50%;
            border: 2px solid var(--surface);
            animation: badgePulse 2s ease-in-out infinite;
        }
        @keyframes badgePulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.6; }
        }
        body.dark .top-action .badge-dot {
            border-color: var(--surface);
        }

        .profile-btn {
            height: 38px;
            border-radius: var(--radius);
            display: flex; align-items: center;
            gap: 10px;
            padding: 0 12px 0 4px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            cursor: pointer;
            font-size: 0.82rem; font-weight: 500;
            transition: all var(--transition);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .profile-btn:hover {
            border-color: var(--primary-light);
            background: rgba(59,130,246,0.03);
        }
        .profile-btn .user-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb, #6366f1);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.7rem; font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 0 12px rgba(37,99,235,0.2);
        }
        body.dark .profile-btn {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.08);
        }

        .quick-action {
            height: 34px;
            padding: 0 14px;
            border-radius: var(--radius-xs);
            font-size: 0.78rem; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            display: flex; align-items: center;
            gap: 6px;
            white-space: nowrap;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        }
        .quick-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        }
        .quick-action.secondary {
            background: rgba(37,99,235,0.08);
            color: var(--primary);
            box-shadow: none;
        }
        .quick-action.secondary:hover {
            background: rgba(37,99,235,0.12);
            box-shadow: none;
        }

        /* ── Main Content Area ── */
        .main-content {
            padding: 1.5rem;
            position: relative; z-index: 1;
        }

        /* ── Card Premium ── */
        .card-premium {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }
        .card-premium::after {
            content: '';
            position: absolute; inset: 0; border-radius: var(--radius-lg);
            pointer-events: none;
            background: linear-gradient(135deg, transparent 0%, rgba(37,99,235,0.015) 100%);
            z-index: 0;
        }
        .card-premium > * { position: relative; z-index: 1; }
        .card-premium:hover {
            border-color: rgba(59,130,246,0.2);
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }
        body.dark .card-premium {
            background: rgba(17,28,53,0.8);
            border-color: rgba(255,255,255,0.05);
        }
        body.dark .card-premium:hover {
            border-color: rgba(59,130,246,0.25);
            box-shadow: 0 12px 32px rgba(0,0,0,0.35);
        }
        .card-premium .card-header-premium {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            justify-content: space-between;
            font-weight: 600; font-size: 0.88rem;
            color: var(--text);
        }
        body.dark .card-premium .card-header-premium {
            border-color: rgba(255,255,255,0.05);
        }

        /* ── KPI Card ── */
        .kpi-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.2rem 1.25rem;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }
        .kpi-card::before {
            content: '';
            position: absolute; top: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.04; transition: all var(--transition-slow);
        }
        .kpi-card:hover::before {
            opacity: 0.08;
            transform: scale(1.3);
        }
        .kpi-card:hover {
            border-color: rgba(59,130,246,0.2);
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        body.dark .kpi-card {
            background: rgba(17,28,53,0.8);
            border-color: rgba(255,255,255,0.05);
        }
        body.dark .kpi-card:hover {
            border-color: rgba(59,130,246,0.25);
            box-shadow: 0 12px 32px rgba(0,0,0,0.35);
        }

        .kpi-card .kpi-icon {
            width: 42px; height: 42px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .kpi-card .kpi-value {
            font-size: 1.55rem; font-weight: 800;
            letter-spacing: -0.02em; line-height: 1;
            color: var(--text);
        }
        .kpi-card .kpi-label {
            font-size: 0.74rem; color: var(--text-muted);
            font-weight: 500; margin-top: 2px;
        }
        .kpi-card .kpi-change {
            font-size: 0.7rem; font-weight: 600;
            padding: 2px 7px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 3px;
            margin-top: 4px;
        }
        .kpi-change.up { background: rgba(16,185,129,0.1); color: #10b981; }
        .kpi-change.down { background: rgba(239,68,68,0.1); color: #ef4444; }

        /* ── Live indicator ── */
        .live-dot {
            display: inline-block;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #10b981;
            animation: livePulse 1.5s ease-in-out infinite;
            margin-right: 4px;
        }
        @keyframes livePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
            50% { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        }
        .live-dot.warn { background: #f59e0b; animation-name: warnPulse; }
        .live-dot.crit { background: #ef4444; animation-name: critPulse; }
        @keyframes warnPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0.5); }
            50% { box-shadow: 0 0 0 8px rgba(245,158,11,0); }
        }
        @keyframes critPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.6); }
            50% { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
        }

        /* ── Modern Tables ── */
        .table-modern {
            font-size: 0.82rem; width: 100%; border-collapse: collapse;
        }
        .table-modern thead th {
            background: var(--bg);
            font-weight: 600; font-size: 0.7rem;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            padding: 0.7rem 1rem; text-align: left;
            white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 0.75rem 1rem; vertical-align: middle;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        .table-modern tbody tr:last-child td { border-bottom: none; }
        .table-modern tbody tr { transition: all var(--transition); }
        .table-modern tbody tr:hover {
            background: rgba(37,99,235,0.02);
        }
        body.dark .table-modern thead th {
            background: rgba(255,255,255,0.02);
            border-color: rgba(255,255,255,0.05);
        }
        body.dark .table-modern tbody td {
            border-color: rgba(255,255,255,0.04);
        }
        body.dark .table-modern tbody tr:hover {
            background: rgba(37,99,235,0.05);
        }

        .badge-modern {
            font-weight: 500; font-size: 0.68rem;
            padding: 4px 10px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 4px;
            letter-spacing: 0.01em;
        }
        .badge-modern.success { background: rgba(16,185,129,0.1); color: #10b981; }
        .badge-modern.warning { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .badge-modern.danger { background: rgba(239,68,68,0.1); color: #ef4444; }
        .badge-modern.primary { background: rgba(37,99,235,0.1); color: #2563eb; }
        .badge-modern.info { background: rgba(99,102,241,0.1); color: #6366f1; }
        .badge-modern .badge-dot {
            width: 5px; height: 5px; border-radius: 50%;
            background: currentColor;
        }

        /* ── Alert banner ── */
        .alert-banner {
            display: flex; align-items: center; gap: 10px;
            padding: 0.7rem 1rem;
            border-radius: var(--radius);
            border: 1px solid transparent;
            margin-bottom: 1rem;
            font-size: 0.8rem; font-weight: 500;
            animation: slideInDown 0.35s ease-out;
        }
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-banner.warn {
            background: rgba(245,158,11,0.06);
            border-color: rgba(245,158,11,0.2);
            color: #d97706;
        }
        .alert-banner.danger {
            background: rgba(239,68,68,0.06);
            border-color: rgba(239,68,68,0.2);
            color: #dc2626;
        }

        /* ── Chart Container ── */
        .chart-container { position: relative; width: 100%; }
        .chart-container canvas { width: 100% !important; }

        /* ── Heartbeat SVG Animation ── */
        .heartbeat-anim {
            width: 100%; height: 60px;
            overflow: visible;
        }
        .heartbeat-path {
            fill: none; stroke: #ef4444; stroke-width: 3;
            stroke-dasharray: 1200; stroke-dashoffset: 1200;
            animation: heartbeatDraw 3s ease-in-out infinite;
            filter: drop-shadow(0 0 4px rgba(239,68,68,0.4));
        }
        .heartbeat-glow {
            fill: none; stroke: rgba(239,68,68,0.2); stroke-width: 8;
            stroke-dasharray: 1200; stroke-dashoffset: 1200;
            animation: heartbeatDraw 3s ease-in-out 0.1s infinite;
        }
        @keyframes heartbeatDraw {
            0% { stroke-dashoffset: 1200; }
            50% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: -1200; }
        }

        /* ── Floating medical icons ── */
        .floating-medical {
            position: absolute; pointer-events: none; z-index: 0;
            opacity: 0.03; animation: floatIcon 20s ease-in-out infinite;
            font-size: 6rem; color: var(--primary);
        }
        .floating-medical:nth-child(2) {
            right: 5%; bottom: 10%;
            animation-delay: -7s; animation-duration: 17s;
            font-size: 5rem; opacity: 0.025;
        }
        @keyframes floatIcon {
            0%, 100% { transform: translate(0,0) rotate(0deg); }
            25% { transform: translate(30px,-20px) rotate(5deg); }
            50% { transform: translate(-15px,-40px) rotate(-3deg); }
            75% { transform: translate(-25px,-10px) rotate(2deg); }
        }

        /* ── Glassmorphism card variant ── */
        .glass-card {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.8);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            transition: all var(--transition);
        }
        body.dark .glass-card {
            background: rgba(17,28,53,0.5);
            border-color: rgba(255,255,255,0.06);
        }
        .glass-card:hover {
            background: rgba(255,255,255,0.75);
            border-color: rgba(59,130,246,0.25);
            box-shadow: var(--shadow-lg);
        }
        body.dark .glass-card:hover {
            background: rgba(17,28,53,0.65);
        }

        /* ── Animate on scroll ── */
        .fade-up {
            opacity: 0; transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .fade-up.visible {
            opacity: 1; transform: translateY(0);
        }

        /* ── Progress ring ── */
        .progress-ring-container {
            position: relative;
            width: 70px; height: 70px;
            display: flex; align-items: center; justify-content: center;
        }
        .progress-ring {
            position: absolute; inset: 0;
            transform: rotate(-90deg);
        }
        .progress-ring circle {
            fill: none; stroke-width: 4;
            transition: stroke-dashoffset 1.5s ease-out;
        }
        .progress-ring .bg-ring { stroke: var(--border); }
        .progress-ring .fill-ring { stroke: var(--primary); stroke-linecap: round; }

        /* ── Notification dropdown ── */
        .notif-dropdown {
            position: absolute; top: 100%; right: 0;
            margin-top: 8px;
            width: 340px; max-height: 380px; overflow-y: auto;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            z-index: 1050;
            display: none;
            animation: fadeIn 0.2s ease-out;
        }
        .notif-dropdown.show { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body.dark .notif-dropdown {
            background: var(--navy-600);
            border-color: rgba(255,255,255,0.08);
        }
        .notif-item {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex; gap: 10px;
            cursor: pointer; transition: all var(--transition);
            font-size: 0.8rem;
        }
        .notif-item:hover { background: rgba(37,99,235,0.03); }
        .notif-item:last-child { border-bottom: none; }
        .notif-icon {
            width: 34px; height: 34px; min-width: 34px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Tabs ── */
        .tab-modern {
            display: flex; gap: 2px;
            background: var(--bg);
            border-radius: var(--radius);
            padding: 3px;
        }
        .tab-modern .tab-btn {
            padding: 6px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem; font-weight: 600;
            color: var(--text-muted);
            border: none; background: transparent;
            cursor: pointer; transition: all var(--transition);
            font-family: 'Plus Jakarta Sans', sans-serif;
            white-space: nowrap;
        }
        .tab-modern .tab-btn.active {
            background: var(--surface);
            color: var(--text);
            box-shadow: var(--shadow-sm);
        }
        body.dark .tab-modern { background: rgba(255,255,255,0.03); }
        body.dark .tab-modern .tab-btn.active {
            background: rgba(255,255,255,0.06);
            color: var(--text);
        }

        /* ── Responsive ── */
        @media (max-width: 1199px) {
            .search-box { flex: 0 1 240px; }
            .quick-action span { display: none; }
            .quick-action { padding: 0 10px; width: 34px; justify-content: center; }
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-w); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1035; }
            .mobile-overlay.show { display: block; }
            .search-box { flex: 1; }
            .top-bar { padding: 0 1rem; }
            .main-content { padding: 1rem; }
            .live-clock,.quick-action.secondary { display: none; }
        }
        @media (max-width: 640px) {
            .search-box { display: none; }
            .top-bar { justify-content: space-between; }
            .kpi-card .kpi-value { font-size: 1.3rem; }
            .kpi-card { padding: 1rem; }
        }

        /* ── Page enter animation ── */
        .page-enter {
            animation: pageFadeIn 0.4s ease-out;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        body.dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* ── Form controls upgrade ── */
        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border-color: var(--border);
            font-size: 0.84rem; font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all var(--transition); padding: 0.5rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        body.dark .form-control, body.dark .form-select {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.08);
            color: var(--text);
        }
        body.dark .form-control:focus, body.dark .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        .btn {
            font-weight: 600; border-radius: var(--radius-sm);
            transition: all var(--transition); font-size: 0.82rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 0.48rem 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
            transform: translateY(-1px);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary-light);
        }
        .btn-outline-primary:hover {
            background: rgba(37,99,235,0.06);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ── Dropdown Menu ── */
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 0.4rem;
            font-size: 0.82rem;
        }
        .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            transition: all var(--transition);
        }
        .dropdown-item:hover {
            background: rgba(37,99,235,0.06);
            color: var(--primary);
        }
        body.dark .dropdown-menu {
            background: var(--navy-600);
            border-color: rgba(255,255,255,0.08);
        }
        body.dark .dropdown-item { color: var(--text); }
        body.dark .dropdown-item:hover {
            background: rgba(37,99,235,0.12);
            color: #fff;
        }

        body.dark .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text);
            --bs-table-hover-color: var(--text);
            --bs-table-hover-bg: rgba(37,99,235,0.05);
        }

        .modal-content { border-radius: var(--radius-lg); border: none; }
        body.dark .modal-content { background: var(--navy-600); color: var(--text); }
        body.dark .modal-header { border-color: rgba(255,255,255,0.06); }
        body.dark .btn-close { filter: invert(1); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            @php
                $brandName = cms('branding.title', \App\Models\Setting::where('key','branding_app_name')->value('value') ?? config('app.name','SIRS'));
                $brandLogo = cms('branding.image_url', null);
            @endphp
            <a href="{{ route('dashboard') }}">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height:36px;width:auto;max-width:200px;object-fit:contain;">
                @else
                    <span class="logo-icon"><i class="bi bi-hospital"></i></span>
                    <span class="brand-text">{{ $brandName }}</span>
                @endif
            </a>
        </div>

        <nav class="sidebar-menu">
            {{-- Dashboard --}}
            <div class="menu-group">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </div>

            {{-- ═══ Reorganized: Section → Group → Submenu (each with icons) ═══ --}}

            {{-- ════════════ 🏥 PELAYANAN KLINIS ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-hospital"></i> Pelayanan Klinis</div>
            </div>

            {{-- Pendaftaran Pasien --}}
            <div class="menu-group">
                @php($regOpen = request()->is('patients*','patient-screenings*'))
                <div class="accordion" id="menuReg">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $regOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseReg" aria-expanded="{{ $regOpen ? 'true' : 'false' }}" aria-controls="collapseReg">
                            <i class="bi bi-person-vcard"></i> Pendaftaran Pasien
                        </button>
                        <div id="collapseReg" class="accordion-collapse collapse {{ $regOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Data Pasien</a>
                                <a href="{{ route('patient-screenings.index') }}" class="nav-link {{ request()->routeIs('patient-screenings.*') ? 'active' : '' }}"><i class="bi bi-clipboard2-check"></i> Skrining Pasien</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IGD & Gawat Darurat --}}
            <div class="menu-group">
                @php($emerOpen = request()->is('emergencies*','code-blue-activations*','ambulances*','ambulance-calls*'))
                <div class="accordion" id="menuEmer">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $emerOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseEmer" aria-expanded="{{ $emerOpen ? 'true' : 'false' }}" aria-controls="collapseEmer">
                            <i class="bi bi-exclamation-octagon-fill" style="color:#f87171;"></i> IGD & Gawat Darurat
                        </button>
                        <div id="collapseEmer" class="accordion-collapse collapse {{ $emerOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('emergencies.index') }}" class="nav-link {{ request()->routeIs('emergencies.*') ? 'active' : '' }}"><i class="bi bi-heart-pulse-fill"></i> Triase IGD <span class="count-badge">24J</span></a>
                                <a href="{{ route('code-blue-activations.index') }}" class="nav-link {{ request()->routeIs('code-blue-activations.*') ? 'active' : '' }}"><i class="bi bi-broadcast"></i> Aktivasi Code Blue</a>
                                <a href="{{ route('ambulances.index') }}" class="nav-link {{ request()->routeIs('ambulances.*') ? 'active' : '' }}"><i class="bi bi-truck-front"></i> Armada Ambulans</a>
                                <a href="{{ route('ambulance-calls.index') }}" class="nav-link {{ request()->routeIs('ambulance-calls.*') ? 'active' : '' }}"><i class="bi bi-telephone-inbound"></i> Panggilan Darurat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Antrian & Appointment --}}
            <div class="menu-group">
                @php($queueOpen = request()->is('appointments*','queues*'))
                <div class="accordion" id="menuQueue">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $queueOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseQueue" aria-expanded="{{ $queueOpen ? 'true' : 'false' }}" aria-controls="collapseQueue">
                            <i class="bi bi-calendar-check"></i> Antrian & Appointment
                        </button>
                        <div id="collapseQueue" class="accordion-collapse collapse {{ $queueOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"><i class="bi bi-calendar2-week"></i> Appointment</a>
                                <a href="{{ route('queues.index') }}" class="nav-link {{ request()->routeIs('queues.*') ? 'active' : '' }}"><i class="bi bi-list-ol"></i> Antrian Poli</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pelayanan Medis (Poliklinik) --}}
            <div class="menu-group">
                @php($medisOpen = request()->is('medical-records*','treatments*','surgeries*','prescriptions*','informed-consents*','referrals*','telemedicine-sessions*','odontograms*'))
                <div class="accordion" id="menuMedis">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $medisOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseMedis" aria-expanded="{{ $medisOpen ? 'true' : 'false' }}" aria-controls="collapseMedis">
                            <i class="bi bi-heart-pulse"></i> Pelayanan Medis (Poliklinik)
                        </button>
                        <div id="collapseMedis" class="accordion-collapse collapse {{ $medisOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('medical-records.index') }}" class="nav-link {{ request()->routeIs('medical-records.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-medical"></i> Rekam Medis</a>
                                <a href="{{ route('treatments.index') }}" class="nav-link {{ request()->routeIs('treatments.*') ? 'active' : '' }}"><i class="bi bi-clipboard2-pulse"></i> Treatment / Tindakan</a>
                                <a href="{{ route('prescriptions.index') }}" class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}"><i class="bi bi-prescription2"></i> Resep Obat</a>
                                <a href="{{ route('surgeries.index') }}" class="nav-link {{ request()->routeIs('surgeries.*') ? 'active' : '' }}"><i class="bi bi-scissors"></i> Operasi / OT</a>
                                <a href="{{ route('informed-consents.index') }}" class="nav-link {{ request()->routeIs('informed-consents.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-check"></i> Persetujuan Tindakan</a>
                                <a href="{{ route('referrals.index') }}" class="nav-link {{ request()->routeIs('referrals.*') ? 'active' : '' }}"><i class="bi bi-signpost-split"></i> Rujukan</a>
                                <a href="{{ route('telemedicine-sessions.index') }}" class="nav-link {{ request()->routeIs('telemedicine-sessions.*') ? 'active' : '' }}"><i class="bi bi-camera-video"></i> Telemedicine</a>
                                <a href="{{ route('odontograms.index') }}" class="nav-link {{ request()->routeIs('odontograms.*') ? 'active' : '' }}"><i class="bi bi-emoji-smile"></i> Odontogram</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kebidanan & Anak --}}
            <div class="menu-group">
                @php($kebidananOpen = request()->is('maternities*','anc-records*','partographs*','postnatal-records*','baby-immunizations*'))
                <div class="accordion" id="menuKebidanan">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $kebidananOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseKebidanan" aria-expanded="{{ $kebidananOpen ? 'true' : 'false' }}" aria-controls="collapseKebidanan">
                            <i class="bi bi-gender-female"></i> Kebidanan & Anak
                        </button>
                        <div id="collapseKebidanan" class="accordion-collapse collapse {{ $kebidananOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('anc-records.index') }}" class="nav-link {{ request()->routeIs('anc-records.*') ? 'active' : '' }}"><i class="bi bi-heart"></i> Pemeriksaan Hamil (ANC)</a>
                                <a href="{{ route('partographs.index') }}" class="nav-link {{ request()->routeIs('partographs.*') ? 'active' : '' }}"><i class="bi bi-graph-up"></i> Partograf</a>
                                <a href="{{ route('maternities.index') }}" class="nav-link {{ request()->routeIs('maternities.*') ? 'active' : '' }}"><i class="bi bi-hospital"></i> Ruang Bersalin</a>
                                <a href="{{ route('postnatal-records.index') }}" class="nav-link {{ request()->routeIs('postnatal-records.*') ? 'active' : '' }}"><i class="bi bi-flower1"></i> Perawatan Nifas</a>
                                <a href="{{ route('baby-immunizations.index') }}" class="nav-link {{ request()->routeIs('baby-immunizations.*') ? 'active' : '' }}"><i class="bi bi-shield-plus"></i> Imunisasi Bayi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rawat Inap & ICU --}}
            <div class="menu-group">
                @php($inpatientOpen = request()->is('rooms*','hospital-beds*','icu-monitorings*','diet-orders*'))
                <div class="accordion" id="menuInpatient">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $inpatientOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseInpatient" aria-expanded="{{ $inpatientOpen ? 'true' : 'false' }}" aria-controls="collapseInpatient">
                            <i class="bi bi-building-fill"></i> Rawat Inap & ICU
                        </button>
                        <div id="collapseInpatient" class="accordion-collapse collapse {{ $inpatientOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"><i class="bi bi-door-open"></i> Daftar Kamar</a>
                                <a href="{{ route('hospital-beds.index') }}" class="nav-link {{ request()->routeIs('hospital-beds.*') ? 'active' : '' }}"><i class="bi bi-hospital"></i> Bed Management</a>
                                <a href="{{ route('icu-monitorings.index') }}" class="nav-link {{ request()->routeIs('icu-monitorings.*') ? 'active' : '' }}"><i class="bi bi-activity"></i> Monitoring ICU</a>
                                <a href="{{ route('diet-orders.index') }}" class="nav-link {{ request()->routeIs('diet-orders.*') ? 'active' : '' }}"><i class="bi bi-egg-fried"></i> Order Diet Pasien</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Asuhan Keperawatan --}}
            <div class="menu-group">
                @php($nursingOpen = request()->is('vital-signs*','nurse-assignments*','medication-administrations*','nursing-cares*','shift-handovers*'))
                <div class="accordion" id="menuNursing">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $nursingOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseNursing" aria-expanded="{{ $nursingOpen ? 'true' : 'false' }}" aria-controls="collapseNursing">
                            <i class="bi bi-clipboard2-pulse"></i> Asuhan Keperawatan
                        </button>
                        <div id="collapseNursing" class="accordion-collapse collapse {{ $nursingOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('vital-signs.index') }}" class="nav-link {{ request()->routeIs('vital-signs.*') ? 'active' : '' }}"><i class="bi bi-thermometer-half"></i> Tanda Vital</a>
                                <a href="{{ route('nurse-assignments.index') }}" class="nav-link {{ request()->routeIs('nurse-assignments.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Penugasan Perawat</a>
                                <a href="{{ route('nursing-cares.index') }}" class="nav-link {{ request()->routeIs('nursing-cares.*') ? 'active' : '' }}"><i class="bi bi-journal-medical"></i> Asuhan (SOAP)</a>
                                <a href="{{ route('medication-administrations.index') }}" class="nav-link {{ request()->routeIs('medication-administrations.*') ? 'active' : '' }}"><i class="bi bi-capsule"></i> Pemberian Obat</a>
                                <a href="{{ route('shift-handovers.index') }}" class="nav-link {{ request()->routeIs('shift-handovers.*') ? 'active' : '' }}"><i class="bi bi-arrow-left-right"></i> Serah Terima Shift</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pulang & Dokumen (Discharge) --}}
            <div class="menu-group">
                @php($dischargeOpen = request()->is('discharge-summaries*','medical-certificates*','cost-estimates*'))
                <div class="accordion" id="menuDischarge">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $dischargeOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseDischarge" aria-expanded="{{ $dischargeOpen ? 'true' : 'false' }}" aria-controls="collapseDischarge">
                            <i class="bi bi-file-earmark-text"></i> Pulang & Dokumen
                        </button>
                        <div id="collapseDischarge" class="accordion-collapse collapse {{ $dischargeOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('discharge-summaries.index') }}" class="nav-link {{ request()->routeIs('discharge-summaries.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-medical"></i> Resume Medis Pulang</a>
                                <a href="{{ route('medical-certificates.index') }}" class="nav-link {{ request()->routeIs('medical-certificates.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-ruled"></i> Surat Keterangan</a>
                                <a href="{{ route('cost-estimates.index') }}" class="nav-link {{ request()->routeIs('cost-estimates.*') ? 'active' : '' }}"><i class="bi bi-calculator"></i> Estimasi Biaya</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════ 🔬 PENUNJANG MEDIS ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-clipboard2-data"></i> Penunjang Medis</div>
            </div>

            {{-- Penunjang Diagnostik (Lab/Radiologi) --}}
            <div class="menu-group">
                @php($diagOpen = request()->is('lab-tests*','radiologies*','blood-donations*'))
                <div class="accordion" id="menuDiag">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $diagOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseDiag" aria-expanded="{{ $diagOpen ? 'true' : 'false' }}" aria-controls="collapseDiag">
                            <i class="bi bi-clipboard2-data"></i> Penunjang Diagnostik
                        </button>
                        <div id="collapseDiag" class="accordion-collapse collapse {{ $diagOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('lab-tests.index') }}" class="nav-link {{ request()->routeIs('lab-tests.*') ? 'active' : '' }}"><i class="bi bi-eyedropper"></i> Laboratorium</a>
                                <a href="{{ route('radiologies.index') }}" class="nav-link {{ request()->routeIs('radiologies.*') ? 'active' : '' }}"><i class="bi bi-radioactive"></i> Radiologi</a>
                                <a href="{{ route('blood-donations.index') }}" class="nav-link {{ request()->routeIs('blood-donations.*') ? 'active' : '' }}"><i class="bi bi-droplet-fill"></i> Bank Darah</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Apotek & Farmasi --}}
            <div class="menu-group">
                @php($pharmaOpen = request()->is('drugs*','drug-supply-orders*','drug-destructions*'))
                <div class="accordion" id="menuPharma">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $pharmaOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapsePharma" aria-expanded="{{ $pharmaOpen ? 'true' : 'false' }}" aria-controls="collapsePharma">
                            <i class="bi bi-capsule-pill"></i> Apotek & Farmasi
                        </button>
                        <div id="collapsePharma" class="accordion-collapse collapse {{ $pharmaOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('drugs.index') }}" class="nav-link {{ request()->routeIs('drugs.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Inventaris Obat</a>
                                <a href="{{ route('drug-supply-orders.index') }}" class="nav-link {{ request()->routeIs('drug-supply-orders.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-plus"></i> Surat Pesanan Obat</a>
                                <a href="{{ route('drug-destructions.index') }}" class="nav-link {{ request()->routeIs('drug-destructions.*') ? 'active' : '' }}"><i class="bi bi-trash3"></i> Pemusnahan Obat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aset & Logistik / Alkes --}}
            <div class="menu-group">
                @php($logOpen = request()->is('assets*','purchase-orders*','vendors*','equipment-calibrations*','medical-wastes*'))
                <div class="accordion" id="menuLog">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $logOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseLog" aria-expanded="{{ $logOpen ? 'true' : 'false' }}" aria-controls="collapseLog">
                            <i class="bi bi-box-seam"></i> Aset & Logistik / Alkes
                        </button>
                        <div id="collapseLog" class="accordion-collapse collapse {{ $logOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}"><i class="bi bi-pc-display"></i> Aset & Inventaris</a>
                                <a href="{{ route('equipment-calibrations.index') }}" class="nav-link {{ request()->routeIs('equipment-calibrations.*') ? 'active' : '' }}"><i class="bi bi-tools"></i> Kalibrasi Alkes</a>
                                <a href="{{ route('medical-wastes.index') }}" class="nav-link {{ request()->routeIs('medical-wastes.*') ? 'active' : '' }}"><i class="bi bi-biohazard"></i> Limbah Medis B3</a>
                                <a href="{{ route('vendors.index') }}" class="nav-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}"><i class="bi bi-shop"></i> Vendor / Supplier</a>
                                <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> Purchase Order</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════ 💰 KEUANGAN ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-cash-coin"></i> Keuangan</div>
            </div>

            {{-- Kasir & Pembayaran --}}
            <div class="menu-group">
                @php($billingOpen = request()->is('payments*','reports*'))
                <div class="accordion" id="menuBilling">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $billingOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseBilling" aria-expanded="{{ $billingOpen ? 'true' : 'false' }}" aria-controls="collapseBilling">
                            <i class="bi bi-cash-stack"></i> Kasir & Pembayaran
                        </button>
                        <div id="collapseBilling" class="accordion-collapse collapse {{ $billingOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="bi bi-credit-card"></i> Transaksi Pembayaran</a>
                                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Laporan Pendapatan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Akuntansi --}}
            <div class="menu-group">
                @php($acctOpen = request()->is('chart-of-accounts*','journal-entries*'))
                <div class="accordion" id="menuAcct">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $acctOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseAcct" aria-expanded="{{ $acctOpen ? 'true' : 'false' }}" aria-controls="collapseAcct">
                            <i class="bi bi-journal-bookmark"></i> Akuntansi
                        </button>
                        <div id="collapseAcct" class="accordion-collapse collapse {{ $acctOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('chart-of-accounts.index') }}" class="nav-link {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Chart of Accounts</a>
                                <a href="{{ route('journal-entries.index') }}" class="nav-link {{ request()->routeIs('journal-entries.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Jurnal Umum</a>
                                <a href="{{ route('reports.finance') }}" class="nav-link {{ request()->routeIs('reports.finance') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Cost Center & Case-Mix</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Klaim BPJS --}}
            <div class="menu-group">
                @php($claimOpen = request()->is('insurance-claims*'))
                <div class="accordion" id="menuClaim">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $claimOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseClaim" aria-expanded="{{ $claimOpen ? 'true' : 'false' }}" aria-controls="collapseClaim">
                            <i class="bi bi-shield-shaded"></i> Klaim BPJS
                        </button>
                        <div id="collapseClaim" class="accordion-collapse collapse {{ $claimOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('insurance-claims.index') }}" class="nav-link {{ request()->routeIs('insurance-claims.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-medical"></i> Klaim Asuransi & BPJS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════ 👥 SDM & OPERASIONAL ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-people"></i> SDM & Operasional</div>
            </div>

            {{-- SDM & Master Data --}}
            <div class="menu-group">
                @php($masterOpen = request()->is('doctors*','employees*','users*','departments*','staff-schedules*','polyclinics*'))
                <div class="accordion" id="menuMaster">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $masterOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseMaster" aria-expanded="{{ $masterOpen ? 'true' : 'false' }}" aria-controls="collapseMaster">
                            <i class="bi bi-people-fill"></i> SDM & Master Data
                        </button>
                        <div id="collapseMaster" class="accordion-collapse collapse {{ $masterOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Dokter</a>
                                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"><i class="bi bi-person-workspace"></i> Karyawan</a>
                                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-person-gear"></i> Pengguna Sistem</a>
                                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"><i class="bi bi-diagram-2"></i> Departemen</a>
                                <a href="{{ route('polyclinics.index') }}" class="nav-link {{ request()->routeIs('polyclinics.*') ? 'active' : '' }}"><i class="bi bi-hospital"></i> Polyclinic / Poli</a>
                                <a href="{{ route('staff-schedules.index') }}" class="nav-link {{ request()->routeIs('staff-schedules.*') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> Jadwal Staff</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HR Operasional --}}
            <div class="menu-group">
                @php($hrOpsOpen = request()->is('attendances*','salaries*','leaves*'))
                <div class="accordion" id="menuHROps">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $hrOpsOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseHROps" aria-expanded="{{ $hrOpsOpen ? 'true' : 'false' }}" aria-controls="collapseHROps">
                            <i class="bi bi-person-workspace"></i> HR Operasional
                        </button>
                        <div id="collapseHROps" class="accordion-collapse collapse {{ $hrOpsOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}"><i class="bi bi-fingerprint"></i> Absensi</a>
                                <a href="{{ route('salaries.index') }}" class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Penggajian</a>
                                <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}"><i class="bi bi-calendar-x"></i> Cuti & Izin</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════ 📋 MUTU & KEPATUHAN ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-shield-check"></i> Mutu & Kepatuhan</div>
            </div>

            {{-- Mutu & Audit --}}
            <div class="menu-group">
                @php($qualOpen = request()->is('patient-safety-incidents*','infection-surveillances*','clinical-pathways*','equipment-maintenances*'))
                <div class="accordion" id="menuQual">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $qualOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseQual" aria-expanded="{{ $qualOpen ? 'true' : 'false' }}" aria-controls="collapseQual">
                            <i class="bi bi-shield-check"></i> Mutu & Audit
                        </button>
                        <div id="collapseQual" class="accordion-collapse collapse {{ $qualOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('patient-safety-incidents.index') }}" class="nav-link {{ request()->routeIs('patient-safety-incidents.*') ? 'active' : '' }}"><i class="bi bi-exclamation-triangle"></i> Insiden Keselamatan (IKP)</a>
                                <a href="{{ route('infection-surveillances.index') }}" class="nav-link {{ request()->routeIs('infection-surveillances.*') ? 'active' : '' }}"><i class="bi bi-virus"></i> Surveilans Infeksi (HAIs)</a>
                                <a href="{{ route('clinical-pathways.index') }}" class="nav-link {{ request()->routeIs('clinical-pathways.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Clinical Pathway</a>
                                <a href="{{ route('equipment-maintenances.index') }}" class="nav-link {{ request()->routeIs('equipment-maintenances.*') ? 'active' : '' }}"><i class="bi bi-tools"></i> Maintenance Alat</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kepuasan Pasien --}}
            <div class="menu-group">
                @php($satisOpen = request()->is('patient-feedbacks*','activity-logs*'))
                <div class="accordion" id="menuSatis">
                    <div class="accordion-item">
                        <button type="button" class="accordion-button {{ $satisOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapseSatis" aria-expanded="{{ $satisOpen ? 'true' : 'false' }}" aria-controls="collapseSatis">
                            <i class="bi bi-emoji-smile"></i> Kepuasan Pasien
                        </button>
                        <div id="collapseSatis" class="accordion-collapse collapse {{ $satisOpen ? 'show' : '' }}">
                            <div class="accordion-body">
                                <a href="{{ route('patient-feedbacks.index') }}" class="nav-link {{ request()->routeIs('patient-feedbacks.*') ? 'active' : '' }}"><i class="bi bi-chat-heart"></i> Feedback Pasien</a>
                                @if(in_array(Auth::user()?->role, ['developer','admin','director']))
                                <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Log Aktivitas (Audit)</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════ ⚙️ SISTEM & INTEGRASI ════════════ --}}
            <div class="menu-group">
                <div class="menu-group-header"><i class="bi bi-gear-fill"></i> Sistem & Integrasi</div>
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') || request()->routeIs('settings.update') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
                <a href="{{ route('settings.branding') }}" class="nav-link {{ request()->routeIs('settings.branding') ? 'active' : '' }}">
                    <i class="bi bi-brush"></i> Branding
                </a>
                <a href="{{ route('settings.bpjs') }}" class="nav-link {{ request()->routeIs('settings.bpjs') || request()->routeIs('settings.bpjs.update') ? 'active' : '' }}">
                    <i class="bi bi-shield-shaded"></i> Integrasi BPJS
                </a>
                <a href="{{ route('settings.satusehat') }}" class="nav-link {{ request()->routeIs('settings.satusehat') || request()->routeIs('settings.satusehat.update') ? 'active' : '' }}">
                    <i class="bi bi-link-45deg"></i> SATUSEHAT
                </a>
                @if(in_array(Auth::user()?->role, ['developer','admin']))
                <a href="{{ route('cms.index') }}" class="nav-link {{ request()->routeIs('cms.*') ? 'active' : '' }}">
                    <i class="bi bi-palette"></i> CMS Tampilan
                    @if(Auth::user()?->role === 'developer')
                        <span class="count-badge" style="background:rgba(99,102,241,0.15);color:#a5b4fc;">DEV</span>
                    @endif
                </a>
                <a href="{{ route('admin.blog.posts.index') }}" class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                    <i class="bi bi-newspaper"></i> Blog & Artikel
                </a>
                @endif
                <a href="{{ route('tutorial') }}" class="nav-link {{ request()->routeIs('tutorial') ? 'active' : '' }}">
                    <i class="bi bi-book"></i> Tutorial
                </a>
            </div>
        </nav>

        <div class="sidebar-user">
            <div class="dropdown">
                <a href="{{ route('dashboard') }}" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="avatar">{{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 2)) }}</span>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()?->name ?? 'User' }}</div>
                        <div class="user-role">{{ Auth::user()?->role ?? '-' }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu">
                    <li><span class="dropdown-item-text small" style="color:var(--text-muted);">{{ Auth::user()?->email ?? '' }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="main-wrapper" id="mainWrapper">
        {{-- Top Bar --}}
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="hamburger-btn" onclick="toggleSidebar()" title="Menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" placeholder="Cari pasien, dokter, appointment..." id="globalSearch" autocomplete="off">
                </div>

                <div class="live-clock">
                    <i class="bi bi-clock"></i>
                    <span id="liveDate"></span>
                    <span class="time" id="liveTime"></span>
                </div>
            </div>

            <div class="top-bar-right">
                {{-- Quick Actions --}}
                <div class="d-flex gap-1 me-2 d-none d-lg-flex">
                    <a href="{{ route('patients.create') }}" class="quick-action secondary" title="Tambah Pasien">
                        <i class="bi bi-person-plus"></i> <span>Pasien</span>
                    </a>
                    <a href="{{ route('appointments.create') }}" class="quick-action" title="Buat Appointment">
                        <i class="bi bi-calendar-plus"></i> <span>Appointment</span>
                    </a>
                </div>

                {{-- Notification --}}
                <div style="position:relative;">
                    <button class="top-action" onclick="toggleNotif()" title="Notifikasi" id="notifBtn">
                        <i class="bi bi-bell"></i>
                        @if(($adminNotif['total'] ?? 0) > 0)<span class="badge-dot"></span>@endif
                    </button>
                    <div class="notif-dropdown" id="notifDropdown">
                        <div style="padding:0.8rem 1rem;font-weight:700;font-size:0.82rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;">
                            <span>Notifikasi</span>
                            <span class="badge bg-danger">{{ $adminNotif['total'] ?? 0 }}</span>
                        </div>
                        <a href="{{ route('emergencies.index') }}" class="notif-item text-decoration-none text-reset" style="display:flex;">
                            <div class="notif-icon" style="background:rgba(239,68,68,0.1);color:#ef4444;"><i class="bi bi-exclamation-triangle"></i></div>
                            <div>
                                <div style="font-weight:600;">IGD Kritis (Merah)</div>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $adminNotif['igd_kritis'] ?? 0 }} pasien butuh penanganan segera</div>
                            </div>
                        </a>
                        <a href="{{ route('drugs.index') }}" class="notif-item text-decoration-none text-reset" style="display:flex;">
                            <div class="notif-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;"><i class="bi bi-capsule"></i></div>
                            <div>
                                <div style="font-weight:600;">Stok Obat Menipis</div>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $adminNotif['stok_obat'] ?? 0 }} item di bawah threshold</div>
                            </div>
                        </a>
                        <a href="{{ route('insurance-claims.index') }}" class="notif-item text-decoration-none text-reset" style="display:flex;">
                            <div class="notif-icon" style="background:rgba(37,99,235,0.1);color:#2563eb;"><i class="bi bi-shield-shaded"></i></div>
                            <div>
                                <div style="font-weight:600;">Klaim BPJS Pending</div>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $adminNotif['klaim'] ?? 0 }} klaim menunggu proses</div>
                            </div>
                        </a>
                        <a href="{{ route('referrals.index') }}" class="notif-item text-decoration-none text-reset" style="display:flex;">
                            <div class="notif-icon" style="background:rgba(99,102,241,0.1);color:#6366f1;"><i class="bi bi-signpost-split"></i></div>
                            <div>
                                <div style="font-weight:600;">Rujukan Pending</div>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $adminNotif['rujukan'] ?? 0 }} rujukan perlu ditinjau</div>
                            </div>
                        </a>
                        <a href="{{ route('lab-tests.index') }}" class="notif-item text-decoration-none text-reset" style="display:flex;">
                            <div class="notif-icon" style="background:rgba(6,182,212,0.1);color:#06b6d4;"><i class="bi bi-eyedropper"></i></div>
                            <div>
                                <div style="font-weight:600;">Hasil Lab Tertunda</div>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $adminNotif['lab'] ?? 0 }} pemeriksaan dalam proses</div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Dark Mode --}}
                <button class="top-action" onclick="toggleDarkMode()" title="Tema" id="darkToggle">
                    <i class="bi bi-moon-stars" id="darkIcon"></i>
                </button>

                {{-- Profile --}}
                <button class="profile-btn d-none d-md-flex" data-bs-toggle="dropdown">
                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 2)) }}</span>
                    <span style="font-size:0.8rem;font-weight:600;">{{ Auth::user()?->name ?? 'Admin' }}</span>
                    <i class="bi bi-chevron-down" style="font-size:0.7rem;opacity:0.5;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small" style="color:var(--text-muted);">{{ Auth::user()?->email ?? '' }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-person me-2"></i> Profil & Pengaturan</a></li>
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="dropdown-item" style="color:#ef4444;"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="main-content page-enter" id="mainContent">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:var(--radius);border:none;box-shadow:var(--shadow-sm);">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:var(--radius);border:none;box-shadow:var(--shadow-sm);">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    {{-- Purchase Popup --}}
    <div class="modal fade" id="purchasePopup" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:var(--radius-lg);border:none;box-shadow:var(--shadow-xl);">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Source Code Full Version</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <p style="color:var(--text-muted);margin-bottom:0.5rem;">Dapatkan source code lengkap + lisensi + support</p>
                    <p class="mb-2">WhatsApp</p>
                    <a href="https://wa.me/6281296052010" target="_blank" class="btn btn-success btn-lg mb-2" style="border-radius:var(--radius);">
                        <i class="bi bi-whatsapp me-2"></i> 0812-9605-2010
                    </a>
                    <p class="text-muted small mb-0">Gratis konsultasi & bantuan instalasi</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const dm = localStorage.getItem('darkMode');
            if (dm === 'true') { document.body.classList.add('dark'); }
        })();

        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
            const icon = document.getElementById('darkIcon');
            if (icon) icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('mobileOverlay').classList.toggle('show');
        }

        function toggleNotif() {
            document.getElementById('notifDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const notif = document.getElementById('notifDropdown');
            const btn = document.getElementById('notifBtn');
            if (notif && btn && !btn.contains(e.target) && !notif.contains(e.target)) {
                notif.classList.remove('show');
            }
        });

        function updateClock() {
            const now = new Date();
            const opts = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
            document.getElementById('liveDate').textContent = now.toLocaleDateString('id-ID', opts);
            document.getElementById('liveTime').textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        }
        updateClock();
        setInterval(updateClock, 1000);

        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('popupShown')) {
                setTimeout(function() {
                    new bootstrap.Modal(document.getElementById('purchasePopup')).show();
                    sessionStorage.setItem('popupShown', '1');
                }, 1500);
            }

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.fade-up').forEach(function(el) { observer.observe(el); });
        });
    </script>
    @stack('scripts')

    {{-- PWA Service Worker registration --}}
    @include('partials.pwa-register')

    {{-- PWA Install button (muncul saat installable) --}}
    <button type="button" id="pwa-install-btn" aria-label="Pasang aplikasi" style="position:fixed;bottom:20px;right:20px;z-index:998;display:none;align-items:center;gap:8px;padding:10px 16px;border-radius:50px;background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;font-weight:600;font-size:0.84rem;border:none;cursor:pointer;box-shadow:0 8px 20px rgba(37,99,235,0.35);font-family:inherit;">
        <i class="bi bi-download"></i> Pasang Aplikasi
    </button>
    <style>#pwa-install-btn.show { display: inline-flex !important; }</style>
</body>
</html>
