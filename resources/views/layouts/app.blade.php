<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ContentHub') — Rekap Konten</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-width: 260px;
            --accent: #6366f1;
            --accent-soft: rgba(99,102,241,.12);
            --text-muted-custom: #94a3b8;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; min-height: 100vh; }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: var(--sidebar-width); background: var(--sidebar-bg);
            display: flex; flex-direction: column; z-index: 1040;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand h5 {
            color: #fff; font-weight: 800; margin: 0; font-size: 1.1rem;
            letter-spacing: -.3px;
        }
        .sidebar-brand span { color: var(--accent); }
        .sidebar-user {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--accent); display: flex; align-items: center;
            justify-content: center; font-weight: 700; color: #fff; font-size: .85rem;
            flex-shrink: 0;
        }
        .sidebar-user small { color: var(--text-muted-custom); font-size: .7rem; }
        .sidebar-nav { flex: 1; padding: .75rem .75rem 0; overflow-y: auto; }
        .nav-label {
            color: var(--text-muted-custom); font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px; padding: .65rem .5rem .25rem;
        }
        .sidebar-nav .nav-link {
            color: #cbd5e1; border-radius: 8px; padding: .55rem .9rem;
            display: flex; align-items: center; gap: .65rem; font-size: .875rem;
            font-weight: 500; transition: all .2s; margin-bottom: 2px;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--accent-soft); color: #fff;
        }
        .sidebar-nav .nav-link.active { color: #a5b4fc; }
        .sidebar-nav .nav-link i { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-footer {
            padding: .75rem; border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-footer .nav-link {
            color: #f87171; border-radius: 8px; padding: .5rem .9rem;
            display: flex; align-items: center; gap: .65rem;
            font-size: .875rem; font-weight: 500; transition: background .2s;
        }
        .sidebar-footer .nav-link:hover { background: rgba(248,113,113,.12); }

        /* ── Main content ── */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            background: #fff; border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem; display: flex;
            align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar .page-title { font-weight: 700; font-size: 1.05rem; color: #0f172a; margin: 0; }
        .page-body { padding: 1.5rem; flex: 1; }

        /* ── Cards ── */
        .card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: none; }
        .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; font-weight: 600; }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 14px; padding: 1.25rem 1.5rem;
            border: 1px solid rgba(0,0,0,.06);
            background: #fff;
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-number { font-size: 1.75rem; font-weight: 800; line-height: 1; color: #0f172a; }
        .stat-card .stat-label { font-size: .8rem; color: #64748b; font-weight: 500; }

        /* ── Badges / Pills ── */
        .platform-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .72rem; font-weight: 600; border-radius: 20px;
            padding: 3px 10px;
        }

        /* ── Table ── */
        .table thead th { background: #f8fafc; font-size: .78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; color: #64748b;
            border-bottom: 1px solid #e2e8f0; }
        .table td { vertical-align: middle; font-size: .875rem; }

        /* ── Toggle sidebar on mobile ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-grid-3x3-gap-fill me-1"></i>Rekap<span>Konten</span></h5>
        <small class="text-muted" style="font-size:.7rem;">Sistem Rekap Konten</small>
    </div>

    <div class="sidebar-user d-flex align-items-center gap-2">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <div class="text-white fw-600" style="font-size:.82rem;font-weight:600;">{{ auth()->user()->name }}</div>
            <small>{{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}</small>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-label">Menu</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        @if(auth()->user()->isSuperAdmin())
        <div class="nav-label">Kelola Konten</div>
        <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
            <i class="bi bi-file-post"></i> Postingan
        </a>
        <a href="{{ route('posts.create') }}" class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Tambah Postingan
        </a>
        @endif

        <div class="nav-label">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Rekap Bulanan
        </a>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link btn btn-link w-100 text-start">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</nav>

{{-- Main --}}
<div id="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary fw-600" style="font-size:.72rem;">
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <div class="page-body">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
