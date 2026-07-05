<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; margin:0; }
        .sidebar { width:240px; background:#111; color:#fff; min-height:100vh; position:fixed; left:0; top:0; padding:20px 0; }
        .sidebar .brand { font-size:18px; font-weight:800; padding:0 20px 20px; border-bottom:1px solid #333; margin-bottom:16px; }
        .sidebar a { display:block; padding:10px 20px; color:#aaa; font-size:13px; text-decoration:none; }
        .sidebar a:hover, .sidebar a.active { color:#fff; background:#222; text-decoration:none; }
        .sidebar a i { width:20px; text-align:center; margin-right:8px; }
        .main { margin-left:240px; padding:30px; }
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
        <div class="brand">Admin Panel</div>
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#" data-bs-toggle="collapse" data-bs-target="#productsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-box-seam"></i> Products</a>
        <div class="collapse" id="productsSubmenu">
          <a href="{{ route('admin.categories.index') }}" class="ps-4 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> Categories</a>
          <a href="{{ route('admin.brands.index') }}" class="ps-4 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Brands</a>
          <a href="#" class="ps-4"><i class="bi bi-plus-circle"></i> Add Product</a>
        </div>
        <a href="#"><i class="bi bi-people"></i> Users</a>
        <a href="#"><i class="bi bi-gear"></i> Settings</a>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-3 px-3">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main">
      <div class="clearfix mb-4">
        <div class="dropdown float-end">
          <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
            <span>
              <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
              <span class="role">eCommerce</span>
            </span>
            <i class="bi bi-chevron-down small ms-1"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
              </form>
            </li>
          </ul>
        </div>
        <h4>Edit Category</h4>
      </div>

      <div class="stat-card">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
            @if($category->image)
              <img src="{{ asset('storage/' . $category->image) }}" class="mt-2" style="height:60px;">
            @endif
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
