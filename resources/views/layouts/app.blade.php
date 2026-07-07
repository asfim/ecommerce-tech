<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'eCommerce - Fashion Store')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{
            --blue:#1a73e8;
            --dark:#1c1c1c;
            --muted:#8a8a8a;
        }
        body{ font-family:'Segoe UI',Arial,sans-serif; color:#222; font-size:14px; min-width:1300px; background:#fff; }
        a{ text-decoration:none; }
        .wrap{ max-width:1260px; margin:0 auto; padding:0 15px; }
        .topline{ height:6px; background:#111; }
        .header-row{ padding:14px 0; border-bottom:1px solid #eee; }
        .logo-box{ width:34px;height:34px;background:#111;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700; }
        .search-input{ max-width:560px; }
        .search-input input{ border-radius:20px 0 0 20px; }
        .search-input button{ border-radius:0 20px 20px 0; background:#111; color:#fff; border:1px solid #111; }
        .navbar2{ border-bottom:1px solid #eee; padding:8px 0; }
        .navbar2 a{ color:#333; font-size:13px; margin-right:22px; }
        .navbar2 a:hover{ color:var(--blue); }

        /* Hero grid */
        .hero-sec{ background:#f3f3f3; padding:16px 0; }
        .brand-banner{ position:relative; height:260px; border-radius:6px; overflow:hidden; background:#ccc; }
        .brand-banner img{ width:100%; height:100%; object-fit:cover; }
        .brand-banner .cap{ position:absolute; top:24px; left:24px; font-weight:800; font-size:1.6rem; line-height:1.1; text-transform:uppercase; }
        .season-banner{ height:260px; border-radius:6px; overflow:hidden; position:relative; background:linear-gradient(135deg,#1a3fbf,#2a5be8); color:#fff; }
        .season-banner img{ position:absolute; bottom:0; right:0; height:100%; object-fit:cover; opacity:.9; }
        .season-banner .cap{ position:relative; z-index:2; padding:20px; }
        .hotcat-panel{ background:#fff; border-radius:6px; padding:14px; height:260px; }
        .hotcat-panel h6{ font-weight:700; font-size:13px; }
        .hotcat-item img{ width:100%; height:60px; object-fit:cover; border-radius:6px; margin-bottom:4px; }
        .hotcat-item .name{ font-size:10.5px; text-align:center; color:#555; }

        .featured-strip{ background:#fff; border-radius:6px; padding:12px 16px; margin-top:14px; }
        .fs-item{ display:flex; align-items:center; gap:10px; }
        .fs-item img{ width:48px; height:48px; object-fit:cover; border-radius:4px; background:#f2f2f2; }
        .fs-item .t{ font-size:11.5px; font-weight:600; }
        .fs-item .p{ font-size:12px; font-weight:700; }
        .fs-item .p .old{ text-decoration:line-through; color:#bbb; font-weight:400; font-size:11px; }

        .trending-box{ border:2px solid var(--blue); border-radius:6px; padding:16px; margin:20px 0; display:flex; align-items:center; gap:20px; }
        .trending-box h6{ font-weight:700; margin-bottom:2px; }
        .trending-box p{ font-size:12px; color:var(--muted); margin-bottom:8px; }
        .tcat-item{ text-align:center; }
        .tcat-item img{ width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px; }
        .tcat-item .name{ font-size:11.5px; }

        .panel-title{ display:flex; justify-content:space-between; align-items:center; padding:12px 16px; font-weight:700; font-size:13px; }
        .panel-title .arrow{ width:26px;height:26px;border-radius:50%;background:#111;color:#fff;display:flex;align-items:center;justify-content:center; }

        .bestselling-panel{ background:#eceee6; border-radius:6px; }
        .todaydeal-panel{ background:#fff; border:1px solid #eee; border-radius:6px; }
        .mini-prod{ text-align:center; padding:0 6px; }
        .mini-prod img{ width:100%; height:90px; object-fit:contain; margin-bottom:6px; background:#fff; border-radius:4px;}
        .mini-prod .t{ font-size:11px; color:#444; min-height:28px; }
        .mini-prod .p{ font-size:12px; font-weight:700; }

        .promo3{ height:170px; border-radius:6px; overflow:hidden; position:relative; border: 1px solid #ddd; }
        .promo3 img{ width:100%; height:100%; object-fit:cover; }

        .auction-panel{ background:#d8cdb8; border-radius:6px; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:20px; position:relative; }
        .auction-panel img{ width:150px; margin-top:10px; }
        .auction-panel .cap{ font-size:2rem; font-weight:800; letter-spacing:6px; color:#fff; text-shadow:0 2px 4px rgba(0,0,0,.3); writing-mode:vertical-rl; position:absolute; left:20px; top:20px; }
        .auction-list-panel{ background:#e6dfd0; border-radius:6px; }
        .auction-item{ display:flex; gap:10px; align-items:center; padding:8px; background:#fff; border-radius:6px; margin-bottom:8px; }
        .auction-item img{ width:50px; height:50px; object-fit:contain; }
        .auction-item .t{ font-size:11.5px; font-weight:600; }
        .auction-item .bid{ font-size:10.5px; color:var(--muted); }
        .btn-bid{ background:#c9a45c; color:#fff; font-size:10px; padding:3px 10px; border-radius:3px; border:none; }

        .classified-sec{ background:#f5f5f5; padding:26px 0; }
        .classified-card{ background:#fff; border:1px solid #eee; border-radius:6px; padding:10px; text-align:center; position:relative; }
        .classified-card img{ width:100%; height:90px; object-fit:contain; margin-bottom:6px; }
        .classified-card .p{ font-weight:700; font-size:12.5px; }
        .badge-used{ position:absolute; bottom:8px; left:50%; transform:translateX(-50%); background:#dfeaff; color:#1a73e8; font-size:10px; padding:2px 10px; border-radius:3px; }
        .badge-new{ position:absolute; bottom:8px; left:50%; transform:translateX(-50%); background:#111; color:#fff; font-size:10px; padding:2px 10px; border-radius:3px; }

        .preorder-panel{ border:1px solid #eee; border-radius:6px; padding:14px; }
        .preorder-hero{ background:#111; color:#fff; border-radius:6px; height:100%; padding:20px; position:relative; overflow:hidden; }
        .preorder-hero .badge-limit{ background:#ff5722; font-size:11px; padding:4px 10px; border-radius:3px; }
        .rating i{ font-size:11px; color:#f2b01e; }

        .shops-banner{ background:linear-gradient(120deg,#eef3fb,#dbe7fb); border-radius:6px; padding:30px; display:flex; align-items:center; justify-content:space-between; margin:26px 0; }
        .shops-banner h3{ font-weight:800; color:#1c2b4a; }
        .shops-banner img{ height:120px; border-radius:6px; object-fit:cover; }

        .prod-card{ border:1px solid #eee; border-radius:6px; padding:10px; text-align:center; height:100%; position:relative; }
        .prod-card img{ width:100%; height:150px; object-fit:contain; margin-bottom:8px; }
        .prod-card .t{ font-size:12px; color:#444; min-height:32px; }
        .prod-card .p{ font-weight:700; font-size:13px; }
        .prod-card .p .old{ text-decoration:line-through; color:#bbb; font-weight:400; font-size:11.5px; }
        .disc-badge{ position:absolute; top:8px; left:8px; font-size:10px; padding:3px 7px; border-radius:3px; color:#fff; }

        .about-sec{ background:#fafafa; padding:30px 0; color:#999; font-size:12px; line-height:1.7; }
        .about-sec h6{ color:#666; font-weight:700; margin-top:14px; }

        .footlinks{ background:#fff; padding:20px 0; border-top:1px solid #eee; }
        .footlinks .item{ text-align:center; font-size:13px; font-weight:600; }

        footer.main-footer{ background:#111; color:#aaa; padding:40px 0 15px; }
        footer.main-footer a{ color:#aaa; }
        footer.main-footer a:hover{ color:#fff; }
        footer.main-footer h6{ color:#fff; font-weight:700; margin-bottom:12px; font-size:13px; text-transform:uppercase; }
        .social-ic{ width:30px;height:30px;border-radius:50%;background:#2a2a2a;display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:6px; }
    </style>
    @stack('styles')
</head>
<body>

    @include('layouts.header.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
