<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AssetTrack — IT Asset Monitoring')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    @stack('styles')
</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="12" height="12" rx="2" fill="currentColor" opacity="0.9"/>
                    <rect x="18" y="2" width="12" height="12" rx="2" fill="currentColor" opacity="0.5"/>
                    <rect x="2" y="18" width="12" height="12" rx="2" fill="currentColor" opacity="0.5"/>
                    <rect x="18" y="18" width="12" height="12" rx="2" fill="currentColor" opacity="0.9"/>
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">AssetTrack</span>
                <span class="brand-sub">IT Inventory</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" fill="currentColor"/><rect x="11" y="2" width="7" height="7" rx="1.5" fill="currentColor" opacity="0.5"/><rect x="2" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity="0.5"/><rect x="11" y="11" width="7" height="7" rx="1.5" fill="currentColor"/></svg>
                Dashboard
            </a>
            <a href="{{ route('assets.index') }}" class="nav-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                All Assets
            </a>
            <div class="nav-section-label">By Type</div>
            @foreach(\App\Models\Asset::TYPES as $key => $label)
            <a href="{{ route('assets.index', ['type' => $key]) }}" class="nav-item nav-item-sm {{ request()->get('type') === $key ? 'active' : '' }}">
                <span class="nav-dot"></span>{{ $label }}
            </a>
            @endforeach
            <div class="nav-section-label">Management</div>
            <a href="{{ route('assets.create') }}" class="nav-item {{ request()->routeIs('assets.create') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M10 7v6M7 10h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Add Asset
            </a>
            <a href="{{ route('assets.scan') }}" class="nav-item {{ request()->routeIs('assets.scan') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none"><path d="M2 7V4a2 2 0 012-2h3M13 2h3a2 2 0 012 2v3M18 13v3a2 2 0 01-2 2h-3M7 18H4a2 2 0 01-2-2v-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6 10h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Scan Barcode
            </a>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z" stroke="currentColor" stroke-width="1.5"/><path d="M7 8h6M7 11h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Reports
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="status-pills">
                @php $counts = \App\Models\Asset::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status'); @endphp
                <div class="status-pill working">{{ $counts['working'] ?? 0 }} Working</div>
                <div class="status-pill defective">{{ $counts['defective'] ?? 0 }} Defective</div>
                <div class="status-pill disposal">{{ $counts['for_disposal'] ?? 0 }} Disposal</div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <span></span><span></span><span></span>
                </button>
                <div class="breadcrumb">@yield('breadcrumb', 'Dashboard')</div>
            </div>
            <div class="topbar-right">
                <form action="{{ route('assets.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search asset, sticker no, user…" value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-btn">
                        <svg viewBox="0 0 20 20" fill="none"><circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="1.5"/><path d="M15 15l-2.5-2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </form>
                <div class="topbar-date">{{ now()->format('M d, Y') }}</div>
            </div>
        </header>

        <div class="page-content">
            @if(session('success'))
            <div class="alert alert-success">
                <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>