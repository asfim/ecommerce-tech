<style>
    :root{ --blue:#1e6fd9; --ink:#1c1c1c; --muted:#8a8a8a; --line:#eee; }
    body{ font-family:'Segoe UI',Arial,sans-serif; color:var(--ink); }
    a{ text-decoration:none; color:inherit; }
    .wrap{ max-width:1260px; margin:0 auto; padding:0 15px; }

    .topline{ height:3px; background:linear-gradient(90deg,var(--blue),#5aa0ff); }

    /* ===== Header row ===== */
    .header-row{ padding:16px 0; border-bottom:1px solid var(--line); }
    .logo-box{ width:38px; height:38px; background:var(--blue); border-radius:8px; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:17px; }
    .brand-text b{ font-size:15px; }
    .brand-text small{ letter-spacing:1px; }

    .search-input{ max-width:560px; margin:0 auto; }
    .search-input .form-control{ border-radius:24px 0 0 24px; border:1.5px solid #304db9; border-right:none; padding:10px 18px; }
    .search-input .form-control:focus{ box-shadow:none; border-color:var(--blue); }
    .search-input .btn{ border-radius:0 24px 24px 0; background:var(--blue); color:#fff; padding:0 22px; border:1.5px solid var(--blue); }

    .icon-btn{ position:relative; font-size:19px; color:#444; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:background .15s; }
    .icon-btn:hover{ background:#f2f6fd; color:var(--blue); }
    .icon-btn .badge-num{ position:absolute; top:1px; right:1px; background:var(--blue); color:#fff; font-size:9.5px; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; }

    .user-chip{ display:flex; align-items:center; gap:8px; padding:5px 12px 5px 5px; border-radius:24px; border:1.5px solid var(--line); }
    .user-chip:hover{ border-color:var(--blue); }
    .user-chip img{ width:28px; height:28px; object-fit:cover; }
    .user-chip .name{ font-size:13px; font-weight:600; }
    .user-chip .role{ font-size:10.5px; color:var(--muted); display:block; margin-top:-2px; }

    /* ===== Nav row ===== */
    .navbar2{ border-bottom:1px solid var(--line); background:#fff; position:relative; z-index:50; }
    .navbar2 .inner{ display:flex; align-items:center; }

    .cat-trigger{ background:var(--ink); color:#fff; border:none; font-size:13.5px; font-weight:600; padding:13px 20px; display:flex; align-items:center; gap:10px; white-space:nowrap; }
    .cat-trigger:hover{ background:#000; }

    .nav-links{ display:flex; align-items:center; }
    .nav-links a{ font-size:13.5px; font-weight:500; padding:14px 18px; color:#333; position:relative; }
    .nav-links a:hover, .nav-links a.active{ color:var(--blue); }
    .nav-links a.active::after{ content:''; position:absolute; left:18px; right:18px; bottom:0; height:2px; background:var(--blue); }
    .nav-links .dropdown-toggle::after{ vertical-align:2px; }

    .cart-pill{ margin-left:auto; display:flex; align-items:center; gap:8px; background:#f4f8ff; color:var(--blue); font-weight:700; font-size:13px; padding:9px 16px; border-radius:20px; }

    /* Mega dropdown for categories */
    .mega-panel{ position:absolute; top:100%; left:0; width:640px; background:#fff; border:1px solid var(--line); border-top:none; box-shadow:0 16px 32px rgba(0,0,0,.08); border-radius:0 0 10px 10px; padding:18px; display:none; }
    .cat-dd:hover .mega-panel, .mega-panel:hover{ display:block; }
    .cat-dd{ position:relative; }
    .mega-col-title{ font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); margin-bottom:8px; }
    .mega-list a{ display:flex; align-items:center; gap:10px; padding:7px 8px; font-size:13px; border-radius:6px; }
    .mega-list a:hover{ background:#f4f8ff; color:var(--blue); }
    .mega-list i{ font-size:16px; width:20px; color:var(--blue); }
    .mega-feat{ background:linear-gradient(135deg,#eaf1ff,#f7fbff); border-radius:10px; padding:16px; height:100%; }
    .mega-feat img{ width:100%; height:100px; object-fit:cover; border-radius:8px; margin-bottom:10px; }
    .mega-feat h6{ font-size:13px; font-weight:700; margin-bottom:2px; }
    .mega-feat p{ font-size:11.5px; color:var(--muted); margin-bottom:8px; }
    .mega-feat .btn{ font-size:11.5px; background:var(--blue); color:#fff; padding:5px 12px; border-radius:6px; }

    /* Simple dropdown for "Categories" nav link (alt style) */
    .simple-dd{ position:absolute; top:100%; left:0; min-width:200px; background:#fff; border:1px solid var(--line); box-shadow:0 12px 28px rgba(0,0,0,.08); border-radius:8px; padding:8px; display:none; }
    .nav-links li:hover .simple-dd{ display:block; }
    .simple-dd a{ display:block; padding:8px 12px; font-size:13px; border-radius:6px; }
    .simple-dd a:hover{ background:#f4f8ff; color:var(--blue); }

    .demo-note{ max-width:1260px; margin:26px auto; padding:0 15px; color:#888; font-size:13px; }
</style>
<div class="topline"></div>

<!-- ============ HEADER ============ -->
<div class="header-row">
  <div class="wrap d-flex align-items-center gap-4">
    <a class="d-flex align-items-center gap-2" href="{{ route('home') }}">
      <span class="logo-box">A</span>
      <div class="brand-text">
        <small class="d-block text-muted" style="font-size:9px;letter-spacing:1px;">THE COREST</small>
        <b>eCommerce</b>
      </div>
    </a>

    <div class="search-input flex-grow-1 d-flex">
      <input type="text" class="form-control" placeholder="I am shopping for...">
      <button class="btn"><i class="bi bi-search"></i></button>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="#" class="icon-btn"><i class="bi bi-heart"></i><span class="badge-num">3</span></a>
      <a href="#" class="icon-btn"><i class="bi bi-bag"></i><span class="badge-num">7</span></a>
      @if(auth()->guard('admin')->check())
        <div class="position-relative">
          <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
          <span>
            <span class="name d-block">{{ auth()->guard('admin')->user()->email }}</span>
            <span class="role">eCommerce</span>
          </span>
            <i class="bi bi-chevron-down small ms-1"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
              </form>
            </li>
          </ul>
        </div>
      @elseif(auth()->guard('web')->check())
        <div class="position-relative">
          <a href="{{ route('user.dashboard') }}" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->user()->name, 0, 1)) }}" class="rounded-circle">
            <span>
              <span class="name d-block">{{ auth()->user()->name }}</span>
              <span class="role">eCommerce</span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <a href="{{ route('user.login') }}" class="btn btn-sm btn-outline-primary">Login</a>
        <a href="{{ route('user.register') }}" class="btn btn-sm btn-primary">Register</a>
      @endif
    </div>
  </div>
</div>

<!-- ============ NAV ============ -->
<div class="navbar2">
  <div class="wrap inner">

    <!-- Category mega menu trigger -->
    <div class="cat-dd">
      <button class="cat-trigger">
        <i class="bi bi-grid-3x3-gap-fill"></i> All Categories <i class="bi bi-chevron-down small"></i>
      </button>

      <div class="mega-panel">
        <div class="row g-3">
          @foreach($categories as $category)
            <div class="col-4">
              <div class="mega-col-title">{{ $category->name }}</div>
              <div class="mega-list">
                @foreach($category->subCategories as $subCategory)
                  <a href="#"><i class="bi bi-dot"></i> {{ $subCategory->name }}</a>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Main nav links -->
    <ul class="nav-links list-unstyled d-flex mb-0">
      <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Blog</a></li>
      <li><a href="#">Contact</a></li>
    </ul>


  </div>
</div>
