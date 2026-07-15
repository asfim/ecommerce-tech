@extends('layouts.backend.app')

@section('title', 'Edit Product')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
        <span class="role">eCommerce</span>
      </span>
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
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
      <div class="col-md-3 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" id="categorySelect" class="form-select" required>
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Subcategory</label>
        <select name="sub_category_id" id="subCategorySelect" class="form-select">
          <option value="">Select Subcategory</option>
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Brand</label>
        <select name="brand_id" class="form-select" required>
          <option value="">Select Brand</option>
          @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Main Image</label>
        <input type="file" name="image" id="mainImageInput" class="form-control" accept="image/*">
        <div id="mainImagePreview" class="mt-2">
          @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="rounded border" style="height:60px; object-fit:cover;">
          @endif
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2 mb-3">
        <label class="form-label">purchase Price</label>
        <input type="number" name="buy_price" step="0.01" class="form-control" value="{{ old('buy_price', $product->buy_price) }}">
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Sell Price</label>
        <input type="number" name="price" id="priceInput" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        <div class="form-text text-success fw-bold" id="discountedPriceText" style="display:none;">After Discount: $0.00</div>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
      </div>
      {{-- <div class="col-md-2 mb-3">
        <label class="form-label">Sales Count</label>
        <input type="number" name="sales_count" class="form-control" value="{{ old('sales_count', $product->sales_count) }}" required>
      </div> --}}
      <div class="col-md-4 mb-3">
        <label class="form-label">Gallery Images <small class="text-muted">(multiple)</small></label>
        <input type="file" name="images[]" id="galleryImagesInput" class="form-control" multiple accept="image/*">
        <div id="galleryImagesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_type" id="discountTypeSelect" class="form-select">
          <option value="">No Discount</option>
          <option value="percent" {{ old('discount_type', $product->discount_type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
          <option value="fixed" {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number" name="discount_value" id="discountValueInput" step="0.01" class="form-control" value="{{ old('discount_value', $product->discount_value) }}">
      </div>
    </div>

    @if(!empty($product->images))
      <div class="mb-3">
        <label class="form-label fw-semibold d-block">Current Gallery Images <small class="text-muted">(Click 'X' to delete image on save)</small></label>
        <div class="d-flex flex-wrap gap-3">
          @foreach($product->images as $img)
            <div class="position-relative border rounded p-1 bg-white gallery-img-box" style="width: 80px; height: 80px;">
              <img src="{{ asset('storage/' . $img) }}" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
              <label class="position-absolute top-0 end-0 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-sm" style="width: 22px; height: 22px; font-size: 11px; transform: translate(40%, -40%); transition: background-color 0.15s;" title="Delete image">
                <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="d-none">
                <i class="bi bi-x-lg"></i>
              </label>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <div class="mb-3 form-check">
      <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" name="is_new_arrival" value="1" class="form-check-input" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}>
      <label class="form-check-label">New Arrival</label>
    </div>

    <hr>
    <h5 class="fw-bold mb-3"><i class="bi bi-palette me-2 text-primary"></i>Attributes</h5>

    {{-- Attributes list with directly displayed values --}}
    <div class="card border-0 bg-light p-3 mb-3 rounded-3">
      <div class="row g-3">
        @foreach($attributes as $attribute)
          <div class="col-12 pb-2 @if(!$loop->last) border-bottom @endif">
            <label class="form-label fw-bold text-dark mb-2">{{ $attribute->name }}</label>
            <div class="p-2 rounded border bg-white d-flex flex-wrap gap-3 align-items-center" style="min-height:44px;">
              @forelse($attribute->values as $val)
                @php $uid = 'chk_' . $attribute->id . '_' . $val->id; @endphp
                <label class="form-check-label d-flex align-items-center gap-1 cursor-pointer" style="font-weight: 500;" for="{{ $uid }}">
                  <input type="checkbox"
                         class="form-check-input attribute-value-checkbox"
                         id="{{ $uid }}"
                         data-attr-name="{{ $attribute->name }}"
                         value="{{ $val->value }}">
                  {{ $val->value }}
                </label>
              @empty
                <span class="text-muted small">No values for this attribute.</span>
              @endforelse
            </div>
          </div>
        @endforeach
      </div>
    </div>



    {{-- Hidden inputs (sent with form) --}}
    <div id="variantsContainer"></div>

    <hr class="mt-4">
    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection

@push('styles')
<style>
  .attr-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #e8f0fe;
    color: #1a73e8;
    border: 1px solid #c5d8fc;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 12px;
    font-weight: 500;
    margin: 2px 3px;
  }
  #valueCheckboxes label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin: 3px 8px 3px 0;
    font-size: 13px;
    cursor: pointer;
    user-select: none;
  }
  #valueCheckboxes input[type=checkbox] {
    cursor: pointer;
    width: 15px;
    height: 15px;
    accent-color: #1a73e8;
  }
  .gallery-img-box { transition: all 0.2s; position: relative; }
  .gallery-img-box label:has(input:checked) {
    background: #222 !important;
  }
  .gallery-img-box:has(input:checked) img {
    opacity: 0.25;
    filter: grayscale(1) blur(1px);
  }
</style>
@endpush

@push('scripts')
<script>
  // ─── Dynamic Subcategories Filtering ───────────────────────────────
  const subCategoriesData = @json($subCategories);
  const categorySelect = document.getElementById('categorySelect');
  const subCategorySelect = document.getElementById('subCategorySelect');

  categorySelect.addEventListener('change', function () {
    const selectedCategoryId = this.value;
    subCategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

    if (selectedCategoryId) {
      const filtered = subCategoriesData.filter(sub => sub.category_id == selectedCategoryId);
      filtered.forEach(sub => {
        const option = document.createElement('option');
        option.value = sub.id;
        option.textContent = sub.name;
        subCategorySelect.appendChild(option);
      });
    }
  });

  // Restore existing subcategory or old input on validation failure
  (function () {
    const currentSubCategoryId = @json(old('sub_category_id', $product->sub_category_id));
    if (categorySelect.value) {
      categorySelect.dispatchEvent(new Event('change'));
      if (currentSubCategoryId) {
        subCategorySelect.value = currentSubCategoryId;
      }
    }
  })();

  // ─── Slug Auto-generate ────────────────────────────────────────────
  document.getElementById('productName').addEventListener('input', function () {
    document.getElementById('productSlug').value = this.value
      .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
  });

  const container         = document.getElementById('variantsContainer');

  // Global state for selected variants: { "Color": ["Red", "Blue"], "Size": ["L"] }
  let selectedVariants = {};

  // ─── Sync global state to UI and hidden inputs ──────────────────────
  function syncVariants() {
    container.innerHTML = '';

    // Check/uncheck checkbox elements on screen to match selectedVariants state
    document.querySelectorAll('.attribute-value-checkbox').forEach(chk => {
      const attrName = chk.dataset.attrName;
      const val = chk.value;
      chk.checked = !!(selectedVariants[attrName] && selectedVariants[attrName].includes(val));
    });

    const keys = Object.keys(selectedVariants);
    if (keys.length === 0) {
      return;
    }

    keys.forEach(attrName => {
      const vals = selectedVariants[attrName];
      if (!vals || vals.length === 0) return;

      // Add hidden inputs
      vals.forEach(v => {
        container.insertAdjacentHTML('beforeend',
          `<input type="hidden" name="variant_labels[]" value="${attrName}">` +
          `<input type="hidden" name="variant_values[]" value="${v}">`
        );
      });
    });
  }

  // ─── Bind change event to checkboxes ───────────────────────────────
  document.querySelectorAll('.attribute-value-checkbox').forEach(chk => {
    chk.addEventListener('change', function () {
      const attrName = this.dataset.attrName;
      const checkedBoxes = [...document.querySelectorAll(`.attribute-value-checkbox[data-attr-name="${attrName}"]:checked`)];
      if (checkedBoxes.length > 0) {
        selectedVariants[attrName] = checkedBoxes.map(c => c.value);
      } else {
        delete selectedVariants[attrName];
      }
      syncVariants();
    });
  });

  // ─── Restore existing database variants & old() inputs ─────────────
  (function restoreExistingAndOld() {
    const existing  = @json($product->variants ?? []);
    const oldLabels = @json(old('variant_labels', []));
    const oldValues = @json(old('variant_values', []));

    let items = [];
    if (oldLabels.length > 0) {
      oldLabels.forEach((l, i) => {
        items.push({ label: l, value: oldValues[i] });
      });
    } else {
      items = existing.map(v => ({ label: v.label, value: v.value }));
    }

    if (!items.length) return;

    items.forEach(item => {
      (selectedVariants[item.label] = selectedVariants[item.label] || []).push(item.value);
    });

    syncVariants();
  })();

  // ─── Image Previews ───────────────────────────────────────────────
  const mainImageInput = document.getElementById('mainImageInput');
  const mainImagePreview = document.getElementById('mainImagePreview');
  const galleryImagesInput = document.getElementById('galleryImagesInput');
  const galleryImagesPreview = document.getElementById('galleryImagesPreview');

  if (mainImageInput) {
    mainImageInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          mainImagePreview.innerHTML = `<img src="${e.target.result}" class="rounded border" style="height:60px; object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (galleryImagesInput) {
    galleryImagesInput.addEventListener('change', function () {
      galleryImagesPreview.innerHTML = '';
      if (this.files) {
        [...this.files].forEach(file => {
          const reader = new FileReader();
          reader.onload = function (e) {
            const imgWrapper = document.createElement('div');
            imgWrapper.className = 'border rounded p-1 bg-white';
            imgWrapper.style.width = '60px';
            imgWrapper.style.height = '60px';
            imgWrapper.innerHTML = `<img src="${e.target.result}" class="rounded" style="width:100%; height:100%; object-fit:cover;">`;
            galleryImagesPreview.appendChild(imgWrapper);
          };
          reader.readAsDataURL(file);
        });
      }
    });
  }

  // ─── Discount Calculation ──────────────────────────────────────────
  const priceInput = document.getElementById('priceInput');
  const discountTypeSelect = document.getElementById('discountTypeSelect');
  const discountValueInput = document.getElementById('discountValueInput');
  const discountedPriceText = document.getElementById('discountedPriceText');

  function calculateDiscountedPrice() {
    const price = parseFloat(priceInput.value) || 0;
    const discountType = discountTypeSelect.value;
    const discountValue = parseFloat(discountValueInput.value) || 0;

    if (price <= 0 || !discountType || discountValue <= 0) {
      discountedPriceText.style.display = 'none';
      return;
    }

    let discountedPrice = price;
    if (discountType === 'percent') {
      discountedPrice = price - (price * (discountValue / 100));
    } else if (discountType === 'fixed') {
      discountedPrice = price - discountValue;
    }

    if (discountedPrice < 0) {
      discountedPrice = 0;
    }

    discountedPriceText.textContent = `After Discount: $${discountedPrice.toFixed(2)}`;
    discountedPriceText.style.display = 'block';
  }

  if (priceInput && discountTypeSelect && discountValueInput && discountedPriceText) {
    priceInput.addEventListener('input', calculateDiscountedPrice);
    discountTypeSelect.addEventListener('change', calculateDiscountedPrice);
    discountValueInput.addEventListener('input', calculateDiscountedPrice);

    // Run once on load
    calculateDiscountedPrice();
  }
</script>
@endpush
