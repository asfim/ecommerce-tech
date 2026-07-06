<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
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
    </style>
    @stack('styles')
</head>
<body>

    @include('layouts.backend.sidenav')

    <!-- Main Content -->
    <div class="main">
      @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
