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
      <a href="{{ route('admin.attributes.index') }}" class="ps-4 {{ request()->routeIs(['admin.attributes.index', 'admin.attributes.create', 'admin.attributes.edit']) ? 'active' : '' }}"><i class="bi bi-palette"></i> Attributes</a>
      <a href="{{ route('admin.attribute-values.index') }}" class="ps-4 {{ request()->routeIs(['admin.attribute-values.index', 'admin.attributes.values.*']) ? 'active' : '' }}"><i class="bi bi-list-ul"></i> Attribute Values</a>
    @endcan
  </div>
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
    <a href="#" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-gear"></i> Settings</a>
    <div class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}" id="settingsSubmenu">
      <a href="{{ route('admin.settings.homepage') }}" class="ps-4 {{ request()->routeIs('admin.settings.homepage') ? 'active' : '' }}"><i class="bi bi-house"></i> Homepage Settings</a>
    </div>
  @endrole
</div>
