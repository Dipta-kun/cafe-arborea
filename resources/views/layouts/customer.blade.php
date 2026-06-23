<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Menu') – Café Arborea Jatijajar</title>
    <meta name="description" content="Pesan menu lezat dari Café Arborea Jatijajar secara digital. Scan QR Code dan nikmati kemudahan memesan.">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:       #1B4332;
            --primary-light: #2D6A4F;
            --primary-dark:  #0D2B20;
            --secondary:     #8B5E3C;
            --accent:        #F8F5F0;
            --text:          #333333;
            --radius-card:   16px;
            --shadow-card:   0 4px 20px rgba(0,0,0,.08);
            --transition:    all .25s ease;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--accent); color: var(--text); min-height: 100vh; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        /*-- NAVBAR --*/
        .customer-navbar {
            background: var(--primary);
            position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 2px 16px rgba(0,0,0,.2);
        }
        .navbar-brand-custom { font-family: 'Poppins', sans-serif; font-weight: 700; color: #fff !important; font-size: 18px; }
        .meja-badge {
            background: var(--secondary);
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /*-- HERO BANNER --*/
        .hero-banner {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            color: #fff;
            padding: 40px 0 32px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /*-- KATEGORI TABS --*/
        .kategori-tabs { background: #fff; padding: 12px 0; position: sticky; top: 60px; z-index: 900; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
        .kategori-tabs .tab-scroll { display: flex; gap: 8px; overflow-x: auto; padding: 0 16px; scrollbar-width: none; }
        .kategori-tabs .tab-scroll::-webkit-scrollbar { display: none; }
        .cat-tab {
            flex-shrink: 0;
            padding: 8px 20px;
            border-radius: 24px;
            border: 2px solid #e5e7eb;
            background: #fff;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }
        .cat-tab.active, .cat-tab:hover { border-color: var(--primary); background: var(--primary); color: #fff; }

        /*-- SEARCH --*/
        .search-wrap { padding: 12px 16px; background: #fff; border-bottom: 1px solid #f3f4f6; }
        .search-input {
            border-radius: 24px;
            border: 2px solid #e5e7eb;
            padding: 10px 20px;
            font-size: 14px;
            transition: var(--transition);
            width: 100%;
        }
        .search-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(27,67,50,.08); }

        /*-- MENU CARDS --*/
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; padding: 20px 16px; }
        @media (min-width: 768px) { .menu-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 24px; } }

        .menu-card {
            background: #fff;
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
        }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 12px 40px rgba(0,0,0,.14); }
        .menu-card:active { transform: translateY(-2px); }

        .menu-card-img {
            width: 100%; aspect-ratio: 1/1;
            object-fit: cover;
            display: block;
        }
        .menu-card-img-placeholder {
            width: 100%; aspect-ratio: 1/1;
            background: linear-gradient(135deg, var(--accent) 0%, #e8e0d8 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 48px;
        }
        .menu-card-body { padding: 12px; }
        .menu-card-name { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; margin-bottom: 4px; line-height: 1.3; }
        .menu-card-desc { font-size: 12px; color: #6b7280; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .menu-card-price { font-weight: 700; color: var(--primary); font-size: 15px; margin-top: 8px; font-family: 'Poppins', sans-serif; }
        .menu-card-footer { padding: 0 12px 12px; }

        .badge-tersedia { background: #D1FAE5; color: #065F46; font-size: 11px; padding: 3px 10px; border-radius: 20px; }
        .badge-habis    { background: #FEE2E2; color: #991B1B; font-size: 11px; padding: 3px 10px; border-radius: 20px; }

        .btn-add {
            background: var(--primary);
            color: #fff; border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
        }
        .btn-add:hover:not(:disabled) { background: var(--primary-light); transform: scale(1.03); }
        .btn-add:disabled { opacity: .5; cursor: not-allowed; }

        /*-- FLOATING CART --*/
        #floatingCart {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 1050;
            animation: popIn .4s ease;
        }
        @keyframes popIn { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .cart-btn {
            background: var(--primary);
            color: #fff; border: none;
            width: 60px; height: 60px;
            border-radius: 50%;
            font-size: 24px;
            box-shadow: 0 6px 24px rgba(27,67,50,.4);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            transition: var(--transition);
        }
        .cart-btn:hover { transform: scale(1.1); background: var(--primary-light); }
        .cart-btn.has-items { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { box-shadow: 0 6px 24px rgba(27,67,50,.4); } 50% { box-shadow: 0 6px 32px rgba(27,67,50,.7); } }

        .cart-badge {
            position: absolute; top: -4px; right: -4px;
            background: var(--secondary);
            color: #fff;
            width: 22px; height: 22px;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }
        .cart-total-mini {
            background: #fff;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            margin-bottom: 8px;
            text-align: center;
        }

        /*-- OFFCANVAS CART --*/
        .offcanvas-cart { width: 360px !important; }
        @media (max-width: 480px) { .offcanvas-cart { width: 100% !important; } }

        .cart-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            animation: fadeInRight .2s ease;
        }
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(16px); } to { opacity: 1; transform: translateX(0); } }
        .cart-item img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; }
        .qty-btn { width: 30px; height: 30px; border-radius: 8px; border: 1.5px solid #e5e7eb; background: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; cursor: pointer; transition: var(--transition); }
        .qty-btn:hover { border-color: var(--primary); color: var(--primary); }

        /*-- SKELETON --*/
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; }
        @keyframes shimmer { to { background-position: -200% 0; } }
    </style>

    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="customer-navbar">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between py-2">
            <a href="#" class="navbar-brand-custom text-decoration-none">
                🌿 Café Arborea
            </a>
            <div class="meja-badge">
                <i class="bi bi-geo-alt me-1"></i>
                @yield('meja-info', 'Meja')
            </div>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
@yield('content')

{{-- FLOATING CART --}}
@yield('floating-cart')

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    function showToast(type, msg) {
        const Toast = Swal.mixin({ toast: true, position: 'top', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        Toast.fire({ icon: type, title: msg });
    }
</script>

@stack('scripts')
</body>
</html>
