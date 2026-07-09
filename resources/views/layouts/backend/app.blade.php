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
        .sidebar { width:240px; background:#111; color:#fff; height:100vh; position:fixed; left:0; top:0; padding:20px 0; overflow-y:auto; }
        .sidebar .brand { font-size:18px; font-weight:800; padding:0 20px 20px; border-bottom:1px solid #333; margin-bottom:16px; }
        .sidebar a { display:block; padding:10px 20px; color:#aaa; font-size:13px; text-decoration:none; }
        .sidebar a:hover, .sidebar a.active { color:#fff; background:#222; text-decoration:none; }
        .sidebar a i { width:20px; text-align:center; margin-right:8px; }
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-track { background:transparent; }
        .sidebar::-webkit-scrollbar-thumb { background:#333; border-radius:4px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background:#555; }
        .main { margin-left:240px; padding-left:20px; padding-top: 20px; position:relative; }
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
