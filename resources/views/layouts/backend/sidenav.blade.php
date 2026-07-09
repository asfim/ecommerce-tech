<!-- Sidebar -->
<div class="sidebar">
  <div class="brand">
    @php
      $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
      $companyName = $companySettings['name'] ?? 'eCommerce';
      $companyLogo = $companySettings['logo'] ?? null;
    @endphp
    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
      @if($companyLogo)
        <img src="{{ asset('storage/' . $companyLogo) }}" alt="" style="max-height: 32px; border-radius: 4px;">
      @else
        <span class="logo-box" style="width:32px;height:32px;background:#1a73e8;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
      @endif
    </a>
  </div>
  <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
  @can('manage-products')
    <a href="#" data-bs-toggle="collapse" data-bs-target="#productsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-box-seam"></i> Products</a>
  @endcan
  <div class="collapse {{ request()->routeIs(['admin.products.*', 'admin.categories.*', 'admin.sub-categories.*', 'admin.brands.*', 'admin.attributes.*', 'admin.attribute-values.*']) ? 'show' : '' }}" id="productsSubmenu">
    @can('manage-products')
      <a href="{{ route('admin.products.index') }}" class="ps-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> All Products</a>
      <a href="{{ route('admin.products.create') }}" class="ps-4 {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i> Add Product</a>
    @endcan
    @can('manage-categories')
      <a href="{{ route('admin.categories.index') }}" class="ps-4 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
      <a href="{{ route('admin.sub-categories.index') }}" class="ps-4 {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Sub Categories</a>
    @endcan
    @can('manage-brands')
      <a href="{{ route('admin.brands.index') }}" class="ps-4 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Brands</a>
    @endcan
    @can('manage-attributes')
      <a href="{{ route('admin.attributes.index') }}" class="ps-4 {{ request()->routeIs('admin.attributes.index', 'admin.attributes.create', 'admin.attributes.edit') ? 'active' : '' }}"><i class="bi bi-palette"></i> Attributes</a>
      <a href="{{ route('admin.attribute-values.index') }}" class="ps-4 {{ request()->routeIs('admin.attribute-values.index', 'admin.attributes.values.*') ? 'active' : '' }}"><i class="bi bi-list-ul"></i> Attribute Values</a>
    @endcan
  </div>
  @can('manage-orders')
    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Orders</a>
  @endcan
  @can('manage-coupons')
    <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><i class="bi bi-ticket-perforated"></i> Coupons</a>
  @endcan
  @can('manage-reviews')
    <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><i class="bi bi-star"></i> Reviews</a>
  @endcan
  @can('manage-blogs')
    <a href="{{ route('admin.blog-posts.index') }}" class="{{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Blog Posts</a>
  @endcan
  @can('view-reports')
    <a href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu" aria-expanded="false" class="dropdown-toggle {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Reports</a>
    <div class="collapse {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}" id="reportsSubmenu">
      <a href="{{ route('admin.reports.sales') }}" class="ps-4 {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Sales Report</a>
      <a href="{{ route('admin.reports.stock') }}" class="ps-4 {{ request()->routeIs('admin.reports.stock') ? 'active' : '' }}"><i class="bi bi-boxes"></i> Stock Report</a>
    </div>
  @endcan
  @role('Super Admin')
    <a href="#" data-bs-toggle="collapse" data-bs-target="#staffSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-shield-lock"></i> Staff Management</a>
    <div class="collapse {{ request()->routeIs(['admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.activity-logs.*']) ? 'show' : '' }}" id="staffSubmenu">
      <a href="{{ route('admin.users.admins') }}" class="ps-4 {{ request()->routeIs('admin.users.admins') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Staffs</a>
      <a href="{{ route('admin.users.staff') }}" class="ps-4 {{ request()->routeIs('admin.users.staff') ? 'active' : '' }}"><i class="bi bi-people"></i> Customers</a>
      <a href="{{ route('admin.roles.index') }}" class="ps-4 {{ request()->routeIs(['admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? 'active' : '' }}"><i class="bi bi-shield-check"></i> Roles</a>
      <a href="{{ route('admin.permissions.index') }}" class="ps-4 {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}"><i class="bi bi-key"></i> Permissions</a>
      <a href="{{ route('admin.activity-logs.index') }}" class="ps-4 {{ request()->routeIs('admin.activity-logs.index') ? 'active' : '' }}"><i class="bi bi-list-columns-reverse"></i> Activity Logs</a>
    </div>
  @endrole
  @role('Super Admin')
    <a href="{{ route('admin.settings.homepage') }}" class="{{ request()->routeIs('admin.settings.homepage') ? 'active' : '' }}"><i class="bi bi-house"></i> Homepage Settings</a>
    <a href="{{ route('admin.settings.company') }}" class="{{ request()->routeIs('admin.settings.company') ? 'active' : '' }}"><i class="bi bi-building"></i> Company Settings</a>
  @endrole
</div>
