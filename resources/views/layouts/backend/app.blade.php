<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
        $siteName = $companySettings['site_name'] ?? 'eCommerce';
        $favicon = $companySettings['favicon'] ?? null;
    @endphp
    <title>@hasSection('title')@yield('title') - @endif{{ $siteName }}</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; margin:0; }
        .dash-header { background:#fff; border-bottom:1px solid #eee; padding:16px 0; }
        .dash-header .wrap { max-width:1260px; margin:0 auto; padding:0 15px; display:flex; align-items:center; justify-content:space-between; }
        .user-chip { display:flex; align-items:center; gap:8px; padding:5px 12px 5px 5px; border-radius:24px; border:1.5px solid #eee; text-decoration:none; color:inherit; }
        .user-chip:hover { border-color:#1a73e8; }
        .user-chip img { width:32px; height:32px; object-fit:cover; }
        .user-chip .name { font-size:13px; font-weight:600; }
        .user-chip .role { font-size:10.5px; color:#888; display:block; margin-top:-2px; }
        .stat-card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
        .stat-card h2 { font-weight:800; margin:0; }
        .stat-card p { color:#888; margin:0; font-size:13px; }
        .sidebar { width:240px; background:#111; color:#fff; height:100vh; position:fixed; left:0; top:0; padding:20px 0; overflow-y:auto; transition: left 0.3s ease; }
        .sidebar .brand { font-size:18px; font-weight:800; padding:0 20px 20px; border-bottom:1px solid #333; margin-bottom:16px; }
        .sidebar a { display:block; padding:10px 20px; color:#aaa; font-size:13px; text-decoration:none; }
        .sidebar a:hover, .sidebar a.active { color:#fff; background:#222; text-decoration:none; }
        .sidebar a i { width:20px; text-align:center; margin-right:8px; }
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-track { background:transparent; }
        .sidebar::-webkit-scrollbar-thumb { background:#333; border-radius:4px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background:#555; }
        .main { margin-left:240px; padding-left:20px; padding-right:20px; padding-top: 20px; position:relative; min-height: 100vh; }
        
        /* Custom Global Button Styling Override (Make all buttons blue background, white text & slight zoom on hover) */
        button:not(.btn-close):not(.icon-btn):not(.navbar-toggler):not(.user-chip),
        .btn:not(.btn-close):not(.icon-btn):not(.navbar-toggler):not(.user-chip),
        input[type="button"],
        input[type="submit"] {
            background-color: #1a73e8 !important;
            border-color: #1a73e8 !important;
            color: #fff !important;
            transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out !important;
        }
        button:not(.btn-close):not(.icon-btn):not(.navbar-toggler):not(.user-chip):hover,
        .btn:not(.btn-close):not(.icon-btn):not(.navbar-toggler):not(.user-chip):hover,
        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: #1e6fd9 !important;
            border-color: #1e6fd9 !important;
            color: #fff !important;
            transform: scale(1.03) !important;
        }

        /* Mobile responsive styles */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -240px;
                z-index: 1050;
            }
            .sidebar.show {
                left: 0;
            }
            .main {
                margin-left: 0 !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
                padding-top: 80px !important; /* Make room for the mobile top bar */
            }
            .mobile-header {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                background: #111;
                color: #fff;
                padding: 0 15px;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 60px;
                z-index: 1040;
                border-bottom: 1px solid #222;
            }
            .mobile-header .brand-title {
                font-weight: 700;
                font-size: 16px;
                margin: 0;
                color: #fff;
                text-decoration: none;
            }
            .mobile-header .toggle-btn {
                background: transparent !important;
                border: none !important;
                color: #fff !important;
                font-size: 24px;
                padding: 0;
                transform: none !important;
            }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1045;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
        @media (min-width: 992px) {
            .mobile-header {
                display: none !important;
            }
            .sidebar-backdrop {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <button class="toggle-btn" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <a href="{{ route('home') }}" class="brand-title">{{ $siteName }}</a>
        <div style="width: 24px;"></div>
    </div>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    @include('layouts.backend.sidenav')

    <!-- Main Content -->
    <div class="main">
      @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggle && sidebar && backdrop) {
                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                }

                toggle.addEventListener('click', toggleSidebar);
                backdrop.addEventListener('click', toggleSidebar);

                sidebar.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        if (sidebar.classList.contains('show')) {
                            toggleSidebar();
                        }
                    });
                });
            }

            // Dynamically wrap all tables in table-responsive
            document.querySelectorAll("table.table").forEach(function(table) {
                if (!table.parentElement.classList.contains("table-responsive")) {
                    const wrapper = document.createElement("div");
                    wrapper.className = "table-responsive mb-3";
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
