<style>
  .sidebar { width:240px; background:#111; color:#fff; min-height:100vh; position:fixed; left:0; top:0; padding:20px 0; }
  .sidebar .brand { font-size:18px; font-weight:800; padding:0 20px 20px; border-bottom:1px solid #333; margin-bottom:16px; }
  .sidebar a { display:block; padding:10px 20px; color:#aaa; font-size:13px; text-decoration:none; }
  .sidebar a:hover, .sidebar a.active { color:#fff; background:#222; text-decoration:none; }
  .sidebar a i { width:20px; text-align:center; margin-right:8px; }
  .main { margin-left:240px; padding-left:20px; position:relative; }
</style>

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
  <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="#" data-bs-toggle="collapse" data-bs-target="#productsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-box-seam"></i> Products</a>
  <div class="collapse {{ request()->routeIs(['admin.products.*', 'admin.categories.*', 'admin.brands.*']) ? 'show' : '' }}" id="productsSubmenu">
    <a href="{{ route('admin.products.index') }}" class="ps-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> All Products</a>
    <a href="{{ route('admin.products.create') }}" class="ps-4 {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i> Add Product</a>
    <a href="{{ route('admin.categories.index') }}" class="ps-4 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
    <a href="{{ route('admin.brands.index') }}" class="ps-4 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Brands</a>
  </div>
  <a href="#"><i class="bi bi-people"></i> Users</a>
  <a href="#"><i class="bi bi-gear"></i> Settings</a>
</div>
