<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; margin:0; }
        .sidebar { width:240px; background:#111; color:#fff; min-height:100vh; position:fixed; left:0; top:0; padding:20px 0; }
        .sidebar .brand { font-size:18px; font-weight:800; padding:0 20px 20px; border-bottom:1px solid #333; margin-bottom:16px; }
        .sidebar a { display:block; padding:10px 20px; color:#aaa; font-size:13px; text-decoration:none; }
        .sidebar a:hover, .sidebar a.active { color:#fff; background:#222; text-decoration:none; }
        .sidebar a i { width:20px; text-align:center; margin-right:8px; }
        .main { margin-left:240px; padding:30px; position:relative; }
        .stat-card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
        .stat-card h2 { font-weight:800; margin:0; }
        .stat-card p { color:#888; margin:0; font-size:13px; }
        .user-chip { display:flex; align-items:center; gap:8px; padding:5px 12px 5px 5px; border-radius:24px; border:1.5px solid #eee; text-decoration:none; color:inherit; float:right; }
        .user-chip:hover { border-color:#1a73e8; }
        .user-chip img { width:32px; height:32px; object-fit:cover; }
        .user-chip .name { font-size:13px; font-weight:600; }
        .user-chip .role { font-size:10.5px; color:#888; display:block; margin-top:-2px; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
          <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="logo-box" style="width:32px;height:32px;background:#1a73e8;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;">A</span>
            <div>
              <small style="font-size:9px;color:#aaa;letter-spacing:1px;display:block;margin-top:-2px;">THE COREST</small>
              <b style="font-size:14px;color:#fff;">eCommerce</b>
            </div>
          </a>
        </div>
        <a href="{{ route('user.dashboard') }}" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#"><i class="bi bi-box-seam"></i> Orders</a>
        <a href="#"><i class="bi bi-heart"></i> Wishlist</a>
        <a href="#"><i class="bi bi-gear"></i> Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main">
      <div class="clearfix mb-4">
        <div class="dropdown float-end">
          <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" class="rounded-circle">
            <span>
              <span class="name d-block">{{ Auth::user()->name }}</span>
              <span class="role">My Account</span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
              </form>
            </li>
          </ul>
        </div>
        <h4>Dashboard</h4>
      </div>

      <div class="row g-3">
          <div class="col-md-3">
              <div class="stat-card">
                  <p>Total Orders</p>
                  <h2>12</h2>
              </div>
          </div>
          <div class="col-md-3">
              <div class="stat-card">
                  <p>Wishlist</p>
                  <h2>5</h2>
              </div>
          </div>
          <div class="col-md-3">
              <div class="stat-card">
                  <p>Coupons</p>
                  <h2>3</h2>
              </div>
          </div>
          <div class="col-md-3">
              <div class="stat-card">
                  <p>Reviews</p>
                  <h2>8</h2>
              </div>
          </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
