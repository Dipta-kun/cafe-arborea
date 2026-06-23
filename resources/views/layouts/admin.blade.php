<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – Café Arborea</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:    #1B4332;
            --primary-light: #2D6A4F;
            --secondary:  #8B5E3C;
            --accent:     #F8F5F0;
            --text:       #333333;
            --bg:         #F4F6F9;
            --sidebar-w:  260px;
            --sidebar-collapsed-w: 70px;
            --card-radius: 16px;
            --transition: all .25s ease;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .fw-semibold, .fw-bold { font-family: 'Poppins', sans-serif; }

        /*-- SIDEBAR --*/
        #sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(160deg, #1B4332 0%, #0D2B20 100%);
            transition: var(--transition);
            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
        }
        #sidebar.collapsed { width: var(--sidebar-collapsed-w); }
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .sidebar-brand .brand-logo {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--secondary);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
        }
        .sidebar-brand .brand-text { color: #fff; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 15px; line-height: 1.2; }
        .sidebar-brand .brand-text small { font-weight: 300; font-size: 11px; opacity: .7; display: block; }
        #sidebar.collapsed .brand-text { display: none; }

        .sidebar-nav { padding: 16px 0; }
        .nav-section-title {
            padding: 8px 20px 4px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
        }
        #sidebar.collapsed .nav-section-title { display: none; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            border-radius: 0;
            font-size: 14px;
            font-weight: 400;
            transition: var(--transition);
            position: relative;
            white-space: nowrap;
        }
        .sidebar-link i { font-size: 18px; flex-shrink: 0; width: 24px; text-align: center; }
        .sidebar-link:hover, .sidebar-link.active {
            color: #fff;
            background: rgba(255,255,255,.08);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 4px; bottom: 4px;
            width: 4px;
            background: #74C69D;
            border-radius: 0 4px 4px 0;
        }
        #sidebar.collapsed .sidebar-link span { display: none; }
        #sidebar.collapsed .sidebar-link { justify-content: center; padding: 12px; }
        #sidebar.collapsed .sidebar-link i { width: auto; }

        /*-- MAIN CONTENT --*/
        #main-content {
            margin-left: var(--sidebar-w);
            transition: var(--transition);
            min-height: 100vh;
        }
        #main-content.expanded { margin-left: var(--sidebar-collapsed-w); }

        /*-- TOPBAR --*/
        .topbar {
            background: #fff;
            height: 64px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            position: sticky; top: 0; z-index: 900;
            box-shadow: 0 2px 12px rgba(0,0,0,.05);
        }
        .topbar .page-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; color: var(--primary); }

        /*-- CARDS --*/
        .card-modern {
            background: #fff;
            border-radius: var(--card-radius);
            border: none;
            box-shadow: 0 2px 16px rgba(0,0,0,.06);
            transition: transform .2s, box-shadow .2s;
        }
        .card-modern:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.1); }

        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 20px 24px;
            border: none;
            box-shadow: 0 2px 16px rgba(0,0,0,.06);
            transition: transform .2s, box-shadow .2s;
            border-left: 4px solid transparent;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.1); }
        .stat-card.green  { border-color: var(--primary); }
        .stat-card.brown  { border-color: var(--secondary); }
        .stat-card.blue   { border-color: #3B82F6; }
        .stat-card.orange { border-color: #F59E0B; }

        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-icon.green  { background: rgba(27,67,50,.1);  color: var(--primary); }
        .stat-icon.brown  { background: rgba(139,94,60,.1); color: var(--secondary); }
        .stat-icon.blue   { background: rgba(59,130,246,.1);color: #3B82F6; }
        .stat-icon.orange { background: rgba(245,158,11,.1); color: #F59E0B; }

        /*-- BADGES --*/
        .badge-menunggu      { background: #FEF3C7; color: #92400E; }
        .badge-diproses      { background: #DBEAFE; color: #1E40AF; }
        .badge-siap_disajikan{ background: #D1FAE5; color: #065F46; }
        .badge-selesai       { background: #ECFDF5; color: #047857; }
        .badge-dibatalkan    { background: #FEE2E2; color: #991B1B; }

        /*-- BTN --*/
        .btn-primary-custom {
            background: var(--primary);
            border: none; color: #fff;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
            transition: var(--transition);
        }
        .btn-primary-custom:hover { background: var(--primary-light); color: #fff; transform: translateY(-1px); }

        /*-- TABLE --*/
        .table thead th {
            background: var(--accent);
            color: var(--primary);
            font-weight: 600;
            font-size: 13px;
            border: none;
        }

        /*-- CONTENT --*/
        .content-area { padding: 24px; }

        /*-- OVERLAY (mobile) --*/
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 999;
        }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; }
            #sidebar.mobile-open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
            #sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity .3s; }
            #sidebar-overlay.active { opacity: 1; pointer-events: auto; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp .4s ease forwards; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

{{-- SIDEBAR --}}
<nav id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <div class="brand-logo" style="background: none; overflow: hidden; padding: 2px;">
            <img src="{{ asset('images/logo.png') }}" class="w-100 h-100 object-contain rounded" style="background: #fff; padding: 2px;" alt="Logo">
        </div>
        <div class="brand-text">
            Café Arborea
            <small>Admin Panel</small>
        </div>
    </a>

    <div class="sidebar-nav">
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-title">Master Data</div>
        <a href="{{ route('admin.kategori.index') }}" class="sidebar-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>Kategori</span>
        </a>
        <a href="{{ route('admin.menu.index') }}" class="sidebar-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i>
            <span>Menu</span>
        </a>
        <a href="{{ route('admin.meja.index') }}" class="sidebar-link {{ request()->routeIs('admin.meja.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>Meja & QR Code</span>
        </a>

        <div class="nav-section-title">Transaksi</div>
        <a href="{{ route('admin.pesanan.index') }}" class="sidebar-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Pesanan</span>
        </a>

        <div class="nav-section-title">Laporan</div>
        <a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i>
            <span>Laporan & Export</span>
        </a>

        <div class="nav-section-title">Akun</div>
        <a href="{{ route('profile.edit') }}" class="sidebar-link">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link w-100 border-0" style="background:none; cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<div id="main-content">
    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm border-0 bg-transparent" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4 text-secondary"></i>
            </button>
            <span class="page-title">@yield('title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-block">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold" style="width:36px;height:36px;font-size:14px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="fw-500 small d-none d-md-block">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // CSRF for AJAX
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Sidebar toggle
    let sidebarCollapsed = false;
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main    = document.getElementById('main-content');
        const overlay = document.getElementById('sidebar-overlay');
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            sidebarCollapsed = !sidebarCollapsed;
            sidebar.classList.toggle('collapsed', sidebarCollapsed);
            main.classList.toggle('expanded', sidebarCollapsed);
        }
    }

    // SweetAlert toast helper
    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end',
            showConfirmButton: false, timer: 3000,
            timerProgressBar: true,
        });
        Toast.fire({ icon: type, title: message });
    }
</script>

@stack('scripts')
</body>
</html>
