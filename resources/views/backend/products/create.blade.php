@extends('layouts.backend.app')

@section('title', 'Add Product')

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
  <h4>Add Product</h4>
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

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-md-12 mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" id="productName" class="form-control" value="{{ old('name') }}" required style="border-color: #a1a1a1 !important;">
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" id="categorySelect" class="form-select" required>
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Subcategory</label>
        <select name="sub_category_id" id="subCategorySelect" class="form-select">
          <option value="">Select Subcategory</option>
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Brand</label>
        <select name="brand_id" class="form-select" required>
          <option value="">Select Brand</option>
          @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Buy Price</label>
        <input type="number" name="buy_price" step="0.01" class="form-control" value="{{ old('buy_price') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" id="priceInput" step="0.01" class="form-control" value="{{ old('price') }}" required style="border-color: #a1a1a1 !important;">
        <div class="form-text text-success fw-bold" id="discountedPriceText" style="display:none;">After Discount: $0.00</div>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-3 mb-3 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
          <label class="form-check-label fw-bold" for="isActive">Active</label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_type" id="discountTypeSelect" class="form-select">
          <option value="">No Discount</option>
          <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
          <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number" name="discount_value" id="discountValueInput" step="0.01" class="form-control" value="{{ old('discount_value', 0) }}" style="border-color: #a1a1a1 !important;">
      </div>
    </div>

    <div class="row" id="discountDatesRow" style="display: none;">
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Start Date</label>
        <input type="datetime-local" name="discount_start_date" class="form-control" value="{{ old('discount_start_date') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Expiry Date</label>
        <input type="datetime-local" name="discount_expiry_date" class="form-control" value="{{ old('discount_expiry_date') }}" style="border-color: #a1a1a1 !important;">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Main Image</label>
        <input type="file" name="image" id="mainImageInput" class="form-control" accept="image/*" style="border-color: #a1a1a1 !important;">
        <div id="mainImagePreview" class="mt-2" style="display:none;"></div>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Gallery Images <small class="text-muted">(multiple)</small></label>
        <input type="file" name="images[]" id="galleryImagesInput" class="form-control" multiple accept="image/*" style="border-color: #a1a1a1 !important;">
        <div id="galleryImagesPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
      </div>
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

    <div id="variantsConfigurationsWrapper" class="mt-4 card p-3" style="display: none;">
      <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-gear me-2 text-primary"></i>Configure Variant Prices &amp; Images</h6>
      <div id="variantsConfigurationsList" class="row g-3">
        <!-- Dynamically populated via JS -->
      </div>
    </div>

    <hr class="mt-4">
    <button type="submit" class="btn btn-primary">Save Product</button>
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

  // Restore old() on validation failure
  (function () {
    const oldSubCategoryId = @json(old('sub_category_id'));
    if (categorySelect.value) {
      categorySelect.dispatchEvent(new Event('change'));
      if (oldSubCategoryId) {
        subCategorySelect.value = oldSubCategoryId;
      }
    }
  })();


  const container         = document.getElementById('variantsContainer');
  const existingVariantsData = [];
  const storageBaseUrl = "{{ asset('storage') }}";

  // Global state for selected variants: { "Color": ["Red", "Blue"], "Size": ["L"] }
  let selectedVariants = {};

  // ─── Sync global state to UI and hidden inputs ──────────────────────
  function syncVariants() {
    container.innerHTML = '';
    const configList = document.getElementById('variantsConfigurationsList');
    const configWrapper = document.getElementById('variantsConfigurationsWrapper');

    // Save current values entered in input fields to restore them after rebuilding
    const currentPrices = {};
    document.querySelectorAll('[name^="variant_prices"]').forEach(input => {
      const matches = input.name.match(/variant_prices\[([^\]]+)\]\[([^\]]+)\]/);
      if (matches) {
        currentPrices[`${matches[1]}_${matches[2]}`] = input.value;
      }
    });

    // Clean list
    configList.innerHTML = '';

    // Check/uncheck checkbox elements on screen to match selectedVariants state
    document.querySelectorAll('.attribute-value-checkbox').forEach(chk => {
      const attrName = chk.dataset.attrName;
      const val = chk.value;
      chk.checked = !!(selectedVariants[attrName] && selectedVariants[attrName].includes(val));
    });

    const keys = Object.keys(selectedVariants);
    if (keys.length === 0) {
      configWrapper.style.display = 'none';
      return;
    }

    configWrapper.style.display = 'block';

    keys.forEach(attrName => {
      const vals = selectedVariants[attrName];
      if (!vals || vals.length === 0) return;

      vals.forEach(v => {
        // Add hidden inputs
        container.insertAdjacentHTML('beforeend',
          `<input type="hidden" name="variant_labels[]" value="${attrName}">` +
          `<input type="hidden" name="variant_values[]" value="${v}">`
        );

        const key = `${attrName}_${v}`;
        let existingPrice = '';
        if (currentPrices[key] !== undefined) {
          existingPrice = currentPrices[key];
        }

        const html = `
          <div class="col-12 col-md-6 variant-config-item">
            <div class="p-3 border rounded bg-white">
              <span class="badge bg-secondary mb-2">${attrName}: ${v}</span>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Price Override (৳)</label>
                  <input type="number" step="0.01" name="variant_prices[${attrName}][${v}]" class="form-control form-control-sm" placeholder="Price (Optional)" value="${existingPrice}" style="border-color: #a1a1a1 !important;">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Variant Image</label>
                  <input type="file" name="variant_images[${attrName}][${v}]" class="form-control form-control-sm" accept="image/*" style="border-color: #a1a1a1 !important;">
                </div>
              </div>
            </div>
          </div>
        `;
        configList.insertAdjacentHTML('beforeend', html);
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

  // ─── Bind change event to variant configurations file inputs for preview ───
  const configListElement = document.getElementById('variantsConfigurationsList');
  if (configListElement) {
    configListElement.addEventListener('change', function(e) {
      if (e.target && e.target.type === 'file' && e.target.name.startsWith('variant_images')) {
        const fileInput = e.target;
        const parentDiv = fileInput.closest('.variant-config-item');
        if (parentDiv && fileInput.files && fileInput.files[0]) {
          const reader = new FileReader();
          reader.onload = function(ev) {
            let previewWrapper = parentDiv.querySelector('.variant-preview-wrapper');
            if (!previewWrapper) {
              previewWrapper = document.createElement('div');
              previewWrapper.className = 'mt-2 variant-preview-wrapper';
              previewWrapper.innerHTML = `<img class="rounded border variant-preview-img" style="max-height:60px;"><small class="d-block text-muted">New image preview</small>`;
              fileInput.parentNode.appendChild(previewWrapper);
            }
            const previewImg = previewWrapper.querySelector('.variant-preview-img');
            previewImg.src = ev.target.result;
          };
          reader.readAsDataURL(fileInput.files[0]);
        }
      }
    });
  }

  // ─── Restore old() on validation failure ──────────────────────────
  (function () {
    const labels = @json(old('variant_labels', []));
    const values = @json(old('variant_values', []));
    if (!labels.length) return;

    labels.forEach((l, i) => {
      (selectedVariants[l] = selectedVariants[l] || []).push(values[i]);
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
          mainImagePreview.style.display = 'block';
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

    discountedPriceText.textContent = `After Discount: ৳${discountedPrice.toFixed(2)}`;
    discountedPriceText.style.display = 'block';
  }

  const discountDatesRow = document.getElementById('discountDatesRow');
  function toggleDiscountDates() {
    if (discountTypeSelect && discountDatesRow) {
      if (discountTypeSelect.value) {
        discountDatesRow.style.display = 'flex';
      } else {
        discountDatesRow.style.display = 'none';
      }
    }
  }

  if (priceInput && discountTypeSelect && discountValueInput && discountedPriceText) {
    priceInput.addEventListener('input', calculateDiscountedPrice);
    discountTypeSelect.addEventListener('change', calculateDiscountedPrice);
    discountValueInput.addEventListener('input', calculateDiscountedPrice);
    discountTypeSelect.addEventListener('change', toggleDiscountDates);

    // Run once on load
    calculateDiscountedPrice();
    toggleDiscountDates();
  }
</script>
@endpush

