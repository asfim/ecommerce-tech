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
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Product Variants</h5>
      <span class="badge bg-primary-subtle text-primary rounded-pill fs-6 px-3" id="variantCountBadge" style="display:none;"></span>
    </div>

    {{-- Step 1: Attribute Value Chip Selectors --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3" id="attributeBuilderCard">
      <div class="card-body p-4">
        <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i>Select values for each attribute, then click <strong>Generate Variants</strong>.</p>

        <div id="attributeChipsContainer">
          @foreach($attributes as $attribute)
          <div class="mb-3 attribute-chip-group" data-attr-name="{{ $attribute->name }}">
            <label class="fw-semibold text-dark small mb-2 d-block">
              @if(strtolower($attribute->name) === 'color')
                <i class="bi bi-palette2 me-1 text-primary"></i>
              @elseif(strtolower($attribute->name) === 'size')
                <i class="bi bi-rulers me-1 text-primary"></i>
              @else
                <i class="bi bi-tag me-1 text-primary"></i>
              @endif
              {{ $attribute->name }}
            </label>
            <div class="d-flex flex-wrap gap-2">
              @forelse($attribute->values as $val)
                @php
                  $isColor = strtolower($attribute->name) === 'color';
                  $uid = 'chip_' . $attribute->id . '_' . $val->id;
                @endphp
                <label class="variant-chip @if($isColor) variant-chip-color @endif" for="{{ $uid }}">
                  <input type="checkbox" id="{{ $uid }}"
                         class="variant-chip-input attribute-value-checkbox"
                         data-attr-name="{{ $attribute->name }}"
                         value="{{ $val->value }}" hidden>
                  @if($isColor)
                    <span class="color-swatch" data-color="{{ strtolower($val->value) }}"></span>
                  @endif
                  <span class="chip-label">{{ $val->value }}</span>
                  <i class="bi bi-check2 chip-check"></i>
                </label>
              @empty
                <span class="text-muted small fst-italic">No values configured.</span>
              @endforelse
            </div>
          </div>
          @endforeach
        </div>

        <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
          <div class="input-group" style="max-width:240px;">
            <span class="input-group-text bg-light border-end-0 text-muted small">SKU Prefix</span>
            <input type="text" id="skuPrefix" class="form-control border-start-0" placeholder="e.g. SHIRT" style="border-color:#dee2e6!important;">
          </div>
          <button type="button" id="generateVariantsBtn" class="btn btn-primary rounded-3 px-4 fw-semibold">
            <i class="bi bi-lightning-fill me-2"></i>Generate Variants
          </button>
          <span id="variantGeneratedMsg" class="text-success fw-semibold small" style="display:none;"></span>
        </div>
      </div>
    </div>

    {{-- Step 2: Variant Combination Table --}}
    <div id="variantBuilderSection" style="display:none;">
      {{-- Bulk Actions Bar --}}
      <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="text-muted small fw-semibold me-1"><i class="bi bi-lightning me-1 text-warning"></i>Bulk:</span>
            <div class="input-group input-group-sm" style="max-width:210px;">
              <input type="number" id="bulkPrice" class="form-control" placeholder="Set all prices" step="0.01" min="0" style="border-color:#dee2e6!important;">
              <button type="button" class="btn btn-outline-secondary" onclick="applyBulkPrice()">Apply</button>
            </div>
            <div class="input-group input-group-sm" style="max-width:210px;">
              <input type="number" id="bulkStock" class="form-control" placeholder="Set all stock" min="0" style="border-color:#dee2e6!important;">
              <button type="button" class="btn btn-outline-secondary" onclick="applyBulkStock()">Apply</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="activateAll()"><i class="bi bi-check-all me-1"></i>Activate All</button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deactivateAll()"><i class="bi bi-x-circle me-1"></i>Deactivate All</button>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" onclick="clearAllVariants()"><i class="bi bi-trash me-1"></i>Clear All</button>
          </div>
        </div>
      </div>

      {{-- Variant Table --}}
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="variantTable">
              <thead class="table-light">
                <tr>
                  <th class="ps-4" style="min-width:140px;">Variant</th>
                  <th style="min-width:120px;">SKU</th>
                  <th style="min-width:100px;">Price (৳)</th>
                  <th style="min-width:100px;">Discount</th>
                  <th style="min-width:130px;">Start Date</th>
                  <th style="min-width:130px;">End Date</th>
                  <th style="min-width:90px;">Stock</th>
                  <th style="min-width:140px;">Image</th>
                  <th class="text-center" style="min-width:70px;">Active</th>
                  <th class="text-center" style="min-width:50px;"></th>
                </tr>
              </thead>
              <tbody id="variantTableBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Hidden inputs container (populated on submit) --}}
    <div id="variantsHiddenContainer"></div>
    {{-- Backward-compat hidden inputs --}}
    <div id="variantsContainer"></div>

    <hr class="mt-4">
    <button type="submit" class="btn btn-primary">Save Product</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection

@push('styles')
<style>
  /* ── Variant Chip Selectors ────────────────────────────────────── */
  .variant-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border: 1.5px solid #dee2e6;
    border-radius: 30px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    color: #495057;
    background: #fff;
    transition: all 0.15s ease;
    user-select: none;
    position: relative;
  }
  .variant-chip:hover {
    border-color: #1a73e8;
    background: #f0f5ff;
    color: #1a73e8;
  }
  .variant-chip:has(.variant-chip-input:checked) {
    border-color: #1a73e8;
    background: #e8f0fe;
    color: #1a73e8;
    font-weight: 600;
  }
  .chip-check { display: none; font-size: 11px; }
  .variant-chip:has(.variant-chip-input:checked) .chip-check { display: inline; }

  /* ── Color Swatch ─────────────────────────────────────────────── */
  .color-swatch {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 1.5px solid rgba(0,0,0,0.15);
    display: inline-block;
    flex-shrink: 0;
  }

  /* ── Variant Table Image Upload ───────────────────────────────── */
  .vt-img-wrap { display: flex; align-items: center; gap: 8px; }
  .vt-img-preview {
    width: 46px; height: 46px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    cursor: pointer;
    transition: opacity 0.2s;
  }
  .vt-img-preview:hover { opacity: 0.75; }
  .vt-img-upload-label {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px;
    border: 1.5px dashed #adb5bd;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    color: #6c757d;
    transition: all 0.15s;
    white-space: nowrap;
  }
  .vt-img-upload-label:hover { border-color: #1a73e8; color: #1a73e8; background: #f0f5ff; }

  /* ── Combo Badge in Table ─────────────────────────────────────── */
  .combo-badge { display: inline-flex; align-items: center; gap: 4px; flex-wrap: wrap; }
  .combo-attr {
    font-size: 11px; background: #f8f9fa;
    border: 1px solid #e9ecef; border-radius: 4px;
    padding: 2px 7px; color: #495057; font-weight: 500;
  }
  .combo-color-dot {
    width: 10px; height: 10px; border-radius: 50%;
    border: 1px solid rgba(0,0,0,.2); display: inline-block; flex-shrink: 0;
  }

  /* ── Variant Table ────────────────────────────────────────────── */
  #variantTable thead th { font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.04em; }
  #variantTable tbody tr { border-bottom: 1px solid #f0f0f0; }
  #variantTable tbody tr:last-child { border-bottom: none; }
  #variantTable .form-control-sm { font-size: 13px; }

  /* ── Responsive card fallback on very small screens ──────────── */
  @media (max-width: 576px) {
    .variant-chip { padding: 5px 10px; font-size: 12px; }
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


  // ═══════════════════════════════════════════════════════════════════
  // Professional Variant Builder
  // ═══════════════════════════════════════════════════════════════════

  // CSS color name → hex map
  const COLOR_MAP = {
    'black':'#1a1a1a','white':'#f8f9fa','red':'#dc3545','blue':'#0d6efd',
    'green':'#198754','yellow':'#ffc107','orange':'#fd7e14','purple':'#6f42c1',
    'pink':'#d63384','grey':'#6c757d','gray':'#6c757d','brown':'#795548',
    'navy':'#001f5b','teal':'#20c997','cyan':'#0dcaf0','maroon':'#800000',
    'gold':'#ffd700','silver':'#c0c0c0','beige':'#f5f5dc','cream':'#fffdd0',
    'violet':'#8b00ff','indigo':'#6610f2','lime':'#d4edda','khaki':'#c3b17d',
  };
  function getCssColor(name) { return COLOR_MAP[name.toLowerCase()] || name.toLowerCase(); }

  // Initialize color swatches from data-color attribute
  document.querySelectorAll('.color-swatch[data-color]').forEach(el => {
    el.style.background = getCssColor(el.dataset.color);
  });

  // ── State ──────────────────────────────────────────────────────────
  let selectedAttrs = {};   // { Color: ['Black', 'White'], Size: ['M', 'L'] }
  let variantState  = [];   // Array of combination objects

  // ── Cartesian Product ──────────────────────────────────────────────
  function generateCombinations(attrs) {
    const keys = Object.keys(attrs).filter(k => attrs[k] && attrs[k].length > 0);
    if (!keys.length) return [];
    let result = [{}];
    keys.forEach(key => {
      const temp = [];
      result.forEach(combo => {
        attrs[key].forEach(val => { temp.push({ ...combo, [key]: val }); });
      });
      result = temp;
    });
    return result;
  }

  // ── Auto-SKU ───────────────────────────────────────────────────────
  function generateSku(prefix, combo) {
    if (!prefix) return '';
    const parts = [prefix.toUpperCase().replace(/\s+/g, '-')];
    Object.values(combo).forEach(val => {
      parts.push(val.toString().toUpperCase().replace(/\s+/g, '').substring(0, 4));
    });
    return parts.join('-');
  }

  // ── Combo Label HTML ───────────────────────────────────────────────
  function comboLabelHtml(combo) {
    return Object.entries(combo).map(([k, v], i) => {
      const isColor = k.toLowerCase() === 'color';
      let html = '';
      if (i > 0) html += '<span class="text-muted mx-1" style="font-size:11px;">/</span>';
      if (isColor) {
        html += `<span class="combo-attr d-inline-flex align-items-center gap-1">
          <span class="combo-color-dot" style="background:${getCssColor(v)};"></span>${v}</span>`;
      } else {
        html += `<span class="combo-attr">${v}</span>`;
      }
      return html;
    }).join('');
  }

  // ── Generate Variants (Additive) ───────────────────────────────────
  function generateVariants() {
    const combos   = generateCombinations(selectedAttrs);
    const prefix   = document.getElementById('skuPrefix').value.trim();

    if (!combos.length) {
      alert("Please select at least one attribute value to generate variants.");
      return;
    }

    let addedCount = 0;

    combos.forEach(combo => {
      const key = Object.entries(combo).map(([k,v]) => `${k}:${v}`).join('|');
      
      // Check if this combination already exists in the table
      const exists = variantState.find(p =>
        Object.entries(p.combo).map(([k,v]) => `${k}:${v}`).join('|') === key
      );
      
      if (!exists) {
        variantState.push({
          combo,
          sku:            generateSku(prefix, combo),
          price:          '',
          discount:       '',
          discount_start: '',
          discount_end:   '',
          stock:          '',
          imagePreview:   null,
          imageFile:      null,
          active:         true,
        });
        addedCount++;
      }
    });

    if (addedCount > 0) {
      renderVariants();
      document.getElementById('variantBuilderSection').style.display = 'block';
      const msg = document.getElementById('variantGeneratedMsg');
      msg.textContent = `✓ ${addedCount} new variant${addedCount !== 1 ? 's' : ''} added`;
      msg.className = 'text-success fw-semibold small';
      msg.style.display = 'inline';
      setTimeout(() => { msg.style.display = 'none'; }, 4000);
      updateVariantCountBadge();
    } else {
      const msg = document.getElementById('variantGeneratedMsg');
      msg.textContent = `Selected variants already exist in the list.`;
      msg.className = 'text-warning fw-semibold small';
      msg.style.display = 'inline';
      setTimeout(() => { msg.style.display = 'none'; }, 4000);
    }
  }

  // ── Render Table ───────────────────────────────────────────────────
  function renderVariants() {
    const tbody = document.getElementById('variantTableBody');
    tbody.innerHTML = '';

    variantState.forEach((v, idx) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="ps-4 py-3">
          <div class="combo-badge">${comboLabelHtml(v.combo)}</div>
        </td>
        <td>
          <input type="text" class="form-control form-control-sm vt-sku"
                 value="${escHtml(v.sku)}" data-idx="${idx}"
                 placeholder="SKU" style="border-color:#dee2e6!important;min-width:100px;">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-price"
                 value="${v.price}" data-idx="${idx}"
                 step="0.01" min="0" placeholder="Price"
                 style="border-color:#dee2e6!important;min-width:80px;">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-discount"
                 value="${v.discount}" data-idx="${idx}"
                 step="0.01" min="0" placeholder="Discount"
                 style="border-color:#dee2e6!important;min-width:80px;">
        </td>
        <td>
          <input type="date" class="form-control form-control-sm vt-discount-start"
                 value="${v.discount_start}" data-idx="${idx}"
                 style="border-color:#dee2e6!important;min-width:120px;">
        </td>
        <td>
          <input type="date" class="form-control form-control-sm vt-discount-end"
                 value="${v.discount_end}" data-idx="${idx}"
                 style="border-color:#dee2e6!important;min-width:120px;">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-stock"
                 value="${v.stock}" data-idx="${idx}"
                 min="0" placeholder="Stock"
                 style="border-color:#dee2e6!important;min-width:70px;">
        </td>
        <td>
          <div class="vt-img-wrap">
            ${v.imagePreview
              ? `<img src="${v.imagePreview}" class="vt-img-preview" id="vt-preview-${idx}"
                     title="Click to remove" onclick="removeVariantImage(${idx})">`
              : `<span id="vt-preview-${idx}"></span>`}
            <label class="vt-img-upload-label" for="vt-file-${idx}">
              <i class="bi bi-image"></i> ${v.imagePreview ? 'Change' : 'Upload'}
            </label>
            <input type="file" id="vt-file-${idx}"
                   name="variant_images[${idx}]"
                   class="d-none" accept="image/*" data-idx="${idx}"
                   onchange="previewVariantImage(${idx}, this)">
          </div>
        </td>
        <td class="text-center">
          <div class="form-check form-switch d-inline-flex m-0">
            <input class="form-check-input vt-active" type="checkbox"
                   data-idx="${idx}" ${v.active ? 'checked' : ''}
                   style="cursor:pointer;width:2.2em;height:1.1em;">
          </div>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-light border-0 p-1 text-danger"
                  onclick="removeVariant(${idx})" title="Remove">
            <i class="bi bi-x-lg"></i>
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });

    // Restore file objects (DataTransfer) after innerHTML wipe
    variantState.forEach((v, idx) => {
      if (v.imageFile) {
        try {
          const dt = new DataTransfer();
          dt.items.add(v.imageFile);
          const fi = document.getElementById(`vt-file-${idx}`);
          if (fi) fi.files = dt.files;
        } catch(e) {}
      }
    });

    // Live bindings
    tbody.querySelectorAll('.vt-sku').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].sku = e.target.value; });
    });
    tbody.querySelectorAll('.vt-price').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].price = e.target.value; });
    });
    tbody.querySelectorAll('.vt-discount').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].discount = e.target.value; });
    });
    tbody.querySelectorAll('.vt-discount-start').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].discount_start = e.target.value; });
    });
    tbody.querySelectorAll('.vt-discount-end').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].discount_end = e.target.value; });
    });
    tbody.querySelectorAll('.vt-stock').forEach(el => {
      el.addEventListener('input', e => { variantState[+e.target.dataset.idx].stock = e.target.value; });
    });
    tbody.querySelectorAll('.vt-active').forEach(el => {
      el.addEventListener('change', e => { variantState[+e.target.dataset.idx].active = e.target.checked; });
    });

    updateVariantCountBadge();
  }

  function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

  // ── Image Actions ──────────────────────────────────────────────────
  function previewVariantImage(idx, input) {
    if (!input.files || !input.files[0]) return;
    variantState[idx].imageFile = input.files[0];
    const reader = new FileReader();
    reader.onload = e => {
      variantState[idx].imagePreview = e.target.result;
      const wrap = input.closest('.vt-img-wrap');
      const previewEl = document.getElementById(`vt-preview-${idx}`);
      if (previewEl) {
        previewEl.outerHTML = `<img src="${e.target.result}" class="vt-img-preview"
          id="vt-preview-${idx}" title="Click to remove" onclick="removeVariantImage(${idx})">`;
      }
      const lbl = wrap.querySelector('.vt-img-upload-label');
      if (lbl) lbl.innerHTML = '<i class="bi bi-image"></i> Change';
    };
    reader.readAsDataURL(input.files[0]);
  }

  function removeVariantImage(idx) {
    variantState[idx].imagePreview = null;
    variantState[idx].imageFile    = null;
    const fi = document.getElementById(`vt-file-${idx}`);
    if (fi) fi.value = '';
    renderVariants();
  }

  // ── Row Actions ────────────────────────────────────────────────────
  function removeVariant(idx) { variantState.splice(idx, 1); renderVariants(); }

  function clearAllVariants() {
    if (!confirm('Clear all variants?')) return;
    variantState = [];
    renderVariants();
    document.getElementById('variantBuilderSection').style.display = 'none';
    document.getElementById('variantGeneratedMsg').style.display   = 'none';
  }

  // ── Bulk Actions ───────────────────────────────────────────────────
  function applyBulkPrice() {
    const v = document.getElementById('bulkPrice').value;
    if (v === '') return;
    variantState.forEach(x => { x.price = v; }); renderVariants();
  }
  function applyBulkStock() {
    const v = document.getElementById('bulkStock').value;
    if (v === '') return;
    variantState.forEach(x => { x.stock = v; }); renderVariants();
  }
  function activateAll()   { variantState.forEach(x => { x.active = true;  }); renderVariants(); }
  function deactivateAll() { variantState.forEach(x => { x.active = false; }); renderVariants(); }

  function updateVariantCountBadge() {
    const badge = document.getElementById('variantCountBadge');
    if (variantState.length > 0) {
      badge.textContent = variantState.length + ' variant' + (variantState.length !== 1 ? 's' : '');
      badge.style.display = '';
    } else {
      badge.style.display = 'none';
    }
  }

  // ── Chip Binding ───────────────────────────────────────────────────
  document.querySelectorAll('.attribute-value-checkbox').forEach(chk => {
    chk.addEventListener('change', function() {
      const attr    = this.dataset.attrName;
      const checked = [...document.querySelectorAll(`.attribute-value-checkbox[data-attr-name="${attr}"]:checked`)].map(c => c.value);
      if (checked.length > 0) { selectedAttrs[attr] = checked; } else { delete selectedAttrs[attr]; }
    });
  });

  document.getElementById('generateVariantsBtn').addEventListener('click', generateVariants);

  // ── Form Submit: Serialize variants[] for controller ───────────────
  document.querySelector('form').addEventListener('submit', function() {
    const container = document.getElementById('variantsHiddenContainer');
    container.innerHTML = '';

    const make = (name, value) => {
      const inp = document.createElement('input');
      inp.type = 'hidden'; inp.name = name; inp.value = value;
      container.appendChild(inp);
    };

    // To satisfy the existing controller logic, we must extract unique label-value pairs
    // and attach the first matching price/image to them.
    const uniqueVariants = {}; // key: "Label|Value"
    
    variantState.forEach((v, idx) => {
      // Ensure file inputs are properly named for the controller: variant_images[Label][Value]
      // Because combinations have multiple attributes, we'll assign the image/price to the FIRST attribute of the combo.
      // E.g. {Color: Red, Size: M} -> we tie price/image to Color: Red
      
      const attrKeys = Object.keys(v.combo);
      if(attrKeys.length === 0) return;
      
      // Let's create entries for ALL attributes in the combo so they exist in the DB
      attrKeys.forEach((k, attrIdx) => {
        const val = v.combo[k];
        const flatKey = `${k}|${val}`;
        
        if (!uniqueVariants[flatKey]) {
           uniqueVariants[flatKey] = {
             label: k,
             value: val,
             price: '',
             discount: '',
             discount_start: '',
             discount_end: '',
             imageIdx: -1
           };
        }
        
        // Tie the price, discount, and image to the primary attribute (first one)
        if (attrIdx === 0) {
           if (v.price && !uniqueVariants[flatKey].price) uniqueVariants[flatKey].price = v.price;
           if (v.discount && !uniqueVariants[flatKey].discount) uniqueVariants[flatKey].discount = v.discount;
           if (v.discount_start && !uniqueVariants[flatKey].discount_start) uniqueVariants[flatKey].discount_start = v.discount_start;
           if (v.discount_end && !uniqueVariants[flatKey].discount_end) uniqueVariants[flatKey].discount_end = v.discount_end;
           if (v.imageFile && uniqueVariants[flatKey].imageIdx === -1) uniqueVariants[flatKey].imageIdx = idx;
        }
      });
    });

    Object.values(uniqueVariants).forEach(uv => {
      make('variant_labels[]', uv.label);
      make('variant_values[]', uv.value);
      
      if (uv.price) {
        make(`variant_prices[${uv.label}][${uv.value}]`, uv.price);
      }
      if (uv.discount) {
        make(`variant_discounts[${uv.label}][${uv.value}]`, uv.discount);
      }
      if (uv.discount_start) {
        make(`variant_discount_starts[${uv.label}][${uv.value}]`, uv.discount_start);
      }
      if (uv.discount_end) {
        make(`variant_discount_ends[${uv.label}][${uv.value}]`, uv.discount_end);
      }
      
      if (uv.imageIdx !== -1) {
        // Rename the original file input so PHP receives it in the expected nested array structure
        const fi = document.getElementById(`vt-file-${uv.imageIdx}`);
        if (fi && fi.files.length > 0) {
          fi.name = `variant_images[${uv.label}][${uv.value}]`;
        }
      }
    });

    // Clear names of unused file inputs so they don't upload
    variantState.forEach((v, idx) => {
      const fi = document.getElementById(`vt-file-${idx}`);
      if (fi && !fi.name.includes('[' + Object.keys(v.combo)[0] + ']')) {
        fi.name = ''; 
      }
    });
  });

  // ── Restore old() on validation failure ───────────────────────────
  (function() {
    const labels = @json(old('variant_labels', []));
    const values = @json(old('variant_values', []));
    if (!labels.length) return;
    labels.forEach((l, i) => {
      if (i === 0 || l !== labels[i - 1]) {
        (selectedAttrs[l] = selectedAttrs[l] || []);
      }
      if (!selectedAttrs[l].includes(values[i])) selectedAttrs[l].push(values[i]);
      const chk = document.querySelector(`.attribute-value-checkbox[data-attr-name="${l}"][value="${values[i]}"]`);
      if (chk) chk.checked = true;
    });
    if (Object.keys(selectedAttrs).length) generateVariants();
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

