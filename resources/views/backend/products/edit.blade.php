<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
        .stat-card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
        .variant-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
        .color-preview { width:32px; height:32px; border-radius:6px; border:1px solid #ccc; cursor:pointer; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin Panel</div>
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#" data-bs-toggle="collapse" data-bs-target="#productsSubmenu" aria-expanded="false" class="dropdown-toggle"><i class="bi bi-box-seam"></i> Products</a>
        <div class="collapse" id="productsSubmenu">
          <a href="{{ route('admin.products.index') }}" class="ps-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-grid"></i> All Products</a>
          <a href="{{ route('admin.products.create') }}" class="ps-4 {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i> Add Product</a>
          <a href="{{ route('admin.categories.index') }}" class="ps-4 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
          <a href="{{ route('admin.brands.index') }}" class="ps-4 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Brands</a>
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
        <h4>Edit Product</h4>
      </div>

      <div class="stat-card">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Product Name</label>
              <input type="text" name="name" id="productName" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Slug</label>
              <input type="text" name="slug" id="productSlug" class="form-control" value="{{ old('slug', $product->slug) }}" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Category</label>
              <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Brand</label>
              <select name="brand_id" class="form-select" required>
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control">
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="mt-2" style="height:60px;">
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Price</label>
              <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Stock</label>
              <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            </div>
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
          </div>

          <hr>
          <h5>Variants</h5>
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="hasVariants" name="has_variants" value="1" {{ old('has_variants', !empty($product->variants)) ? 'checked' : '' }}>
            <label class="form-check-label" for="hasVariants">Enable Variants</label>
          </div>

          <div id="variantsSection" style="display: {{ !empty($product->variants) ? 'block' : 'none' }};">
            <div id="variantsContainer">
              @if(!empty($product->variants))
                @foreach($product->variants as $variant)
                  <div class="variant-row">
                    <input type="text" name="variant_labels[]" class="form-control" placeholder="Label" value="{{ $variant['label'] ?? '' }}" style="width:150px;">
                    <input type="text" name="variant_values[]" class="form-control" placeholder="Value" value="{{ $variant['value'] ?? '' }}" style="width:200px;">
                    <input type="color" name="variant_colors[]" class="form-control form-control-color colorPicker" style="width:50px; display:{{ strtolower($variant['label'] ?? '') === 'color' ? 'block' : 'none' }};" value="{{ $variant['color'] ?? '#000000' }}">
                    <button type="button" class="btn btn-sm btn-danger remove-variant"><i class="bi bi-trash"></i></button>
                  </div>
                @endforeach
              @else
                <div class="variant-row">
                  <input type="text" name="variant_labels[]" class="form-control" placeholder="Label" style="width:150px;">
                  <input type="text" name="variant_values[]" class="form-control" placeholder="Value" style="width:200px;">
                  <input type="color" name="variant_colors[]" class="form-control form-control-color colorPicker" style="width:50px; display:none;" value="#000000">
                  <button type="button" class="btn btn-sm btn-danger remove-variant"><i class="bi bi-trash"></i></button>
                </div>
              @endif
            </div>
            <button type="button" class="btn btn-sm btn-secondary mt-2" id="addVariant"><i class="bi bi-plus"></i> Add Variant</button>
          </div>

          <hr class="mt-4">
          <button type="submit" class="btn btn-primary">Update Product</button>
          <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('productName').addEventListener('input', function() {
            document.getElementById('productSlug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });

        const hasVariants = document.getElementById('hasVariants');
        const variantsSection = document.getElementById('variantsSection');
        hasVariants.addEventListener('change', function() {
            variantsSection.style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('addVariant').addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'variant-row';
            row.innerHTML = `
              <input type="text" name="variant_labels[]" class="form-control" placeholder="Label" style="width:150px;">
              <input type="text" name="variant_values[]" class="form-control" placeholder="Value" style="width:200px;">
              <input type="color" name="variant_colors[]" class="form-control form-control-color colorPicker" style="width:50px; display:none;" value="#000000">
              <button type="button" class="btn btn-sm btn-danger remove-variant"><i class="bi bi-trash"></i></button>
            `;
            document.getElementById('variantsContainer').appendChild(row);
            bindRowEvents(row);
        });

        document.querySelectorAll('.remove-variant').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.variant-row').remove();
            });
        });

        function bindRowEvents(row) {
            row.querySelector('.remove-variant').addEventListener('click', function() {
                this.closest('.variant-row').remove();
            });
            const labelInput = row.querySelector('input[name="variant_labels[]"]');
            const colorInput = row.querySelector('.colorPicker');
            labelInput.addEventListener('input', function() {
                colorInput.style.display = this.value.toLowerCase() === 'color' ? 'block' : 'none';
            });
        }

        document.querySelectorAll('.variant-row').forEach(bindRowEvents);
    </script>
</body>
</html>
