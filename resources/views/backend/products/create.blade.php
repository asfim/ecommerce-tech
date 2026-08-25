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
    
        <div class="row mb-3">
      <div class="col-md-12">
        <label class="form-label fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Product Type</label>
        <div class="d-flex gap-4 p-3 bg-light rounded-3" style="border: 1px dashed #ccc;">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="product_type" id="typeSimple" value="simple" checked>
            <label class="form-check-label fw-semibold" for="typeSimple">Simple Product</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="product_type" id="typeVariant" value="variant">
            <label class="form-check-label fw-semibold" for="typeVariant">Variant Product</label>
          </div>
        </div>
      </div>
    </div>

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

    <div class="row general-inputs-section">
      <div class="col-md-3 mb-3">
        <label class="form-label">Buy Price</label>
        <input type="number" name="buy_price" step="0.01" class="form-control" value="{{ old('buy_price') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Sell Price</label>
        <input type="number" name="price" id="priceInput" step="0.01" class="form-control" value="{{ old('price') }}" required style="border-color: #a1a1a1 !important;">
        <div class="form-text text-success fw-bold" id="discountedPriceText" style="display:none;">After Discount: $0.00</div>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required style="border-color: #a1a1a1 !important;">
      </div>
    </div>
    
    <div class="row general-inputs-section">
      <div class="col-md-12 mb-3 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
          <label class="form-check-label fw-bold" for="isActive">Active</label>
        </div>
      </div>
    </div>

    <div class="row general-inputs-section">
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

    <div class="row general-inputs-section" id="discountDatesRow" style="display: none;">
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Start Date</label>
        <input type="datetime-local" name="discount_start_date" class="form-control" value="{{ old('discount_start_date') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Expiry Date</label>
        <input type="datetime-local" name="discount_expiry_date" class="form-control" value="{{ old('discount_expiry_date') }}" style="border-color: #a1a1a1 !important;">
      </div>
    </div>

    <div class="row general-inputs-section">
      <div class="col-md-6 mb-3">
        <label class="form-label">Main Image</label>
        <input type="file" name="image" id="mainImageInput" class="form-control" accept="image/*" style="border-color: #a1a1a1 !important;">
        <small class="form-text text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>Recommended size: <strong>600x600 pixels</strong> (1:1 square) for best fit.</small>
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
      <h5 class="fw-bold mb-0"><i class="bi bi-card-text me-2 text-primary"></i>Description</h5>
    </div>
    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body p-4">
        <div id="quill-editor" style="min-height: 200px; border-radius: 8px;"></div>
        <input type="hidden" name="description" id="description-input">
      </div>
    </div>

    <hr>
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Specifications</h5>
      <button type="button" class="btn btn-sm btn-outline-primary rounded-3" id="addSpecRow">
        <i class="bi bi-plus-lg me-1"></i> Add Row
      </button>
    </div>
    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body p-4">
        <div id="specsContainer">
          <div class="spec-row d-flex gap-2 mb-2">
            <input type="text" name="specifications[0][label]" class="form-control spec-label" placeholder="e.g. Brand, Weight, Material" style="border-color:#a1a1a1!important;">
            <input type="text" name="specifications[0][value]" class="form-control spec-value" placeholder="e.g. Samsung, 1.5kg, Plastic" style="border-color:#a1a1a1!important;">
            <button type="button" class="btn btn-outline-danger spec-remove-btn px-3"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <p class="text-muted small mb-0 mt-2"><i class="bi bi-info-circle me-1"></i>Add technical specs like weight, dimensions, material etc.</p>
      </div>
    </div>

    <hr>
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Product Attributes & Variants</h5>
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

        </div></div>
    <div id="variantBuilderWrapper" style="display:none;">
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

    {{-- Step 2: Variant Combination Table --}}
    <div id="variantBuilderSection" style="display:none;">
      {{-- Bulk Actions Bar --}}
      <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
          <div class="d-flex align-items-center flex-wrap gap-2">
            <label class="text-muted small fw-semibold me-2">Bulk Actions:</label>
            <div class="input-group input-group-sm w-auto">
              <span class="input-group-text bg-white"><i class="bi bi-tag"></i></span>
              <input type="number" id="bulkPrice" class="form-control" placeholder="Set all sell prices" step="0.01" min="0" style="border-color:#dee2e6!important;">
              <button type="button" class="btn btn-outline-secondary" onclick="applyBulkPrice()">Apply</button>
            </div>
            <div class="input-group input-group-sm" style="max-width:210px;">
              <input type="number" id="bulkStock" class="form-control" placeholder="Set all stock" min="0" style="border-color:#dee2e6!important;">
              <button type="button" class="btn btn-outline-secondary" onclick="applyBulkStock()">Apply</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="activateAll()"><i class="bi bi-check-all me-1"></i>Activate All</button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deactivateAll()"><i class="bi bi-x-circle me-1"></i>Deactivate All</button>
            <button type="button" class="btn btn-sm btn-danger ms-auto" onclick="clearAllVariants()"><i class="bi bi-trash me-1"></i>Clear All</button>
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
                  <th style="min-width:100px;">Buy Price</th>
                  <th style="min-width:100px;">Sell Price (৳)</th>
                  <th style="min-width:110px;">Disc. Type</th>
                  <th style="min-width:90px;">Discount</th>
                  <th style="min-width:130px;">Start Date</th>
                  <th style="min-width:130px;">End Date</th>
                  <th style="min-width:80px;">Stock</th>
                  <th style="min-width:90px;">Image</th>
                  <th class="text-center" style="min-width:70px;">Active</th>
                  <th class="text-center" style="min-width:80px;"><i class="bi bi-gear"></i></th>
                </tr>
              </thead>
              <tbody id="variantTableBody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Hidden inputs container (populated on submit) --}}
    </div> <!-- end variantBuilderWrapper -->
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

  /* ── Date Input Icon Only (Until Selected) ────────────────────── */
  input[type="date"].vt-date-input:not(.has-value) {
    width: 38px;
    color: transparent;
    padding-left: 6px;
    padding-right: 6px;
    cursor: pointer;
  }
  input[type="date"].vt-date-input:not(.has-value)::-webkit-datetime-edit {
    color: transparent;
  }
  input[type="date"].vt-date-input {
    transition: width 0.2s ease;
  }
</style>
@endpush


@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  // ── Quill Rich Text Editor ─────────────────────────────────────────
  const quill = new Quill('#quill-editor', {
    theme: 'snow',
    placeholder: 'Write product description here...',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link'],
        ['clean']
      ]
    }
  });
  document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('description-input').value = quill.root.innerHTML;
  });

  // ── Dynamic Specification Rows ─────────────────────────────────────
  let specIndex = 1;
  document.getElementById('addSpecRow').addEventListener('click', function() {
    const container = document.getElementById('specsContainer');
    const row = document.createElement('div');
    row.className = 'spec-row d-flex gap-2 mb-2';
    row.innerHTML = `
      <input type="text" name="specifications[${specIndex}][label]" class="form-control spec-label" placeholder="e.g. Brand, Weight, Material" style="border-color:#a1a1a1!important;">
      <input type="text" name="specifications[${specIndex}][value]" class="form-control spec-value" placeholder="e.g. Samsung, 1.5kg, Plastic" style="border-color:#a1a1a1!important;">
      <button type="button" class="btn btn-outline-danger spec-remove-btn px-3"><i class="bi bi-trash"></i></button>
    `;
    container.appendChild(row);
    specIndex++;
  });
  document.getElementById('specsContainer').addEventListener('click', function(e) {
    if (e.target.closest('.spec-remove-btn')) {
      const rows = document.querySelectorAll('.spec-row');
      if (rows.length > 1) {
        e.target.closest('.spec-row').remove();
      }
    }
  });

  // ─── Dynamic Subcategories Filtering ───────────────────────────────
  const subCategoriesData = @json($subCategories);
  const categorySelect = document.getElementById('categorySelect');
  const subCategorySelect = document.getElementById('subCategorySelect');

  if (categorySelect && subCategorySelect) {
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

    (function () {
      const oldSubCategoryId = @json(old('sub_category_id'));
      if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
        if (oldSubCategoryId) {
          subCategorySelect.value = oldSubCategoryId;
        }
      }
    })();
  }

  // ═══════════════════════════════════════════════════════════════════
  // Professional Variant Builder & Product Type Toggle
  // ═══════════════════════════════════════════════════════════════════

  // ── State ──────────────────────────────────────────────────────────
  let selectedAttrs = {};   // { Color: ['Black', 'White'], Size: ['M', 'L'] }
  let variantState  = [];   // Array of combination objects

  // ── Toggle Logic ───────────────────────────────────────────────────
  const togglePriceInput = document.getElementById('priceInput');
  const toggleStockInput = document.querySelector('input[name="stock"]');
  const generalSections = document.querySelectorAll('.general-inputs-section');
  const variantBuilderWrapper = document.getElementById('variantBuilderWrapper');
  const variantTableSection = document.getElementById('variantBuilderSection');
  const variantCountBadge = document.getElementById('variantCountBadge');

  function toggleProductType() {
    const isVariant = document.getElementById('typeVariant') && document.getElementById('typeVariant').checked;
    
    if (isVariant) {
      generalSections.forEach(sec => sec.style.display = 'none');
      if (variantBuilderWrapper) variantBuilderWrapper.style.display = 'block';
      if (variantTableSection) {
          variantTableSection.style.display = variantState.length > 0 ? 'block' : 'none';
      }
      if (togglePriceInput) togglePriceInput.removeAttribute('required');
      if (toggleStockInput) toggleStockInput.removeAttribute('required');
    } else {
      generalSections.forEach(sec => {
        if (sec.id !== 'discountDatesRow') sec.style.display = 'flex';
      });
      if (variantBuilderWrapper) variantBuilderWrapper.style.display = 'none';
      
      if (togglePriceInput) togglePriceInput.setAttribute('required', 'required');
      if (toggleStockInput) toggleStockInput.setAttribute('required', 'required');
      
      const discountType = document.getElementById('discountTypeSelect') ? document.getElementById('discountTypeSelect').value : '';
      const datesRow = document.getElementById('discountDatesRow');
      if (datesRow) {
          datesRow.style.display = (discountType && discountType !== '') ? 'flex' : 'none';
      }
    }
  }

  document.querySelectorAll('input[name="product_type"]').forEach(r => { r.addEventListener('change', toggleProductType); r.addEventListener('click', toggleProductType); });

  // ── Attribute Selection ────────────────────────────────────────────
  document.querySelectorAll('.attribute-value-checkbox').forEach(chk => {
    chk.addEventListener('change', function() {
      const attr = this.dataset.attrName;
      const isVariant = document.getElementById('typeVariant') && document.getElementById('typeVariant').checked;
      
      if (this.checked && isVariant) {
        // Enforce single selection per attribute
        document.querySelectorAll(`.attribute-value-checkbox[data-attr-name="${attr}"]`).forEach(otherChk => {
          if (otherChk !== this) {
            otherChk.checked = false;
          }
        });
      }

      const updateSelectedAttrs = () => {
        const checked = [...document.querySelectorAll(`.attribute-value-checkbox[data-attr-name="${attr}"]:checked`)].map(c => c.value);
        if (checked.length > 0) { selectedAttrs[attr] = checked; } else { delete selectedAttrs[attr]; }
      };

      updateSelectedAttrs();

      // Prevent selecting if the combination already exists
      if (this.checked && isVariant) {
         const combos = generateCombinations(selectedAttrs);
         if (combos.length > 0) {
            const combo = combos[0];
            const key = Object.entries(combo).sort(([k1], [k2]) => k1.localeCompare(k2)).map(([k,v]) => `${k}:${v}`).join('|');
            
            // Check if this exact combination exists in variantState
            const exists = variantState.find(p => {
               const pKey = Object.entries(p.combo).sort(([k1], [k2]) => k1.localeCompare(k2)).map(([k,v]) => `${k}:${v}`).join('|');
               return pKey === key && Object.keys(combo).length === Object.keys(p.combo).length;
            });
            
            if (exists) {
                alert("This attribute combination has already been used to create a variant!");
                this.checked = false;
                updateSelectedAttrs();
            }
         }
      }
    });
  });

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

  function generateSku(prefix, combo) {
    const parts = [prefix.toUpperCase().replace(/\s+/g, '-')];
    Object.values(combo).forEach(v => {
      const isColor = Object.keys(combo).find(k => combo[k] === v && k.toLowerCase() === 'color');
      if (isColor && v.length >= 3) {
        parts.push(v.substring(0,4).toUpperCase());
      } else {
        parts.push(v.toUpperCase().replace(/\s+/g, '-'));
      }
    });
    return parts.filter(Boolean).join('-');
  }

  // ── Generate Button ────────────────────────────────────────────────
  const generateBtn = document.getElementById('generateVariantsBtn');
  if (generateBtn) {
    generateBtn.addEventListener('click', function() {
      const combos = generateCombinations(selectedAttrs);
      const prefixEl = document.getElementById('skuPrefix');
      const prefix = prefixEl ? prefixEl.value.trim() : '';
  
      if (!combos.length) {
        alert("Please select at least one attribute value to generate variants.");
        return;
      }
  
      let addedCount = 0;
  
      combos.forEach(combo => {
        const key = Object.entries(combo).sort(([k1], [k2]) => k1.localeCompare(k2)).map(([k,v]) => `${k}:${v}`).join('|');
        const exists = variantState.find(p => {
            const pKey = Object.entries(p.combo).sort(([k1], [k2]) => k1.localeCompare(k2)).map(([k,v]) => `${k}:${v}`).join('|');
            return pKey === key;
        });
        
        if (!exists) {
          variantState.push({
            combo, sku: generateSku(prefix, combo), buy_price: '', price: '', discount_type: 'percent', discount: '',
            discount_start: '', discount_end: '', stock: '', imagePreview: null, imageFile: null, active: true,
          });
          addedCount++;
        }
      });
  
      if (addedCount > 0) {
        renderVariants();
        if (variantTableSection) variantTableSection.style.display = 'block';
        const msg = document.getElementById('variantGeneratedMsg');
        if (msg) {
            msg.textContent = `+ ${addedCount} new variant${addedCount !== 1 ? 's' : ''} added`;
            msg.style.display = 'inline-block';
            setTimeout(() => msg.style.display = 'none', 3000);
        }

        // Reset selected attributes after generation
        document.querySelectorAll('.attribute-value-checkbox:checked').forEach(c => {
           c.checked = false;
        });
        selectedAttrs = {};

      } else {
        alert("All selected combinations have already been generated.");
      }
    });
  }

  // ── Rendering Variants ─────────────────────────────────────────────
  function formatCombo(combo) {
    return Object.entries(combo).sort(([k1], [k2]) => k1.localeCompare(k2)).map(([k,v]) => {
      let html = '';
      if (k.toLowerCase() === 'color') {
        const c = v.toLowerCase();
        html += `<span class="combo-attr"><span class="combo-color-dot me-1" style="background:${c}"></span>${v}</span>`;
      } else {
        html += `<span class="combo-attr">${v}</span>`;
      }
      return html;
    }).join('');
  }

  function calculateVariantDiscountedPrice(price, discountType, discountValue) {
    const p = parseFloat(price) || 0;
    const dVal = parseFloat(discountValue) || 0;
    if (p <= 0 || !discountType || dVal <= 0) return null;
    let dPrice = p;
    if (discountType === 'percent') dPrice = p - (p * (dVal / 100));
    else if (discountType === 'fixed') dPrice = p - dVal;
    return dPrice < 0 ? 0 : dPrice;
  }

  function renderVariants() {
    const tbody = document.getElementById('variantTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
  
    variantState.forEach((v, idx) => {
      const row = document.createElement('tr');
      if (!v.active) row.classList.add('opacity-50');
      
      let discountedPriceHtml = '';
      const dPrice = calculateVariantDiscountedPrice(v.price, v.discount_type, v.discount);
      if (dPrice !== null) {
          discountedPriceHtml = `<div class="form-text text-success fw-bold mt-1" id="vt-discount-text-${idx}" style="font-size: 11px;">After Discount: ৳${dPrice.toFixed(2)}</div>`;
      } else {
          discountedPriceHtml = `<div class="form-text text-success fw-bold mt-1" id="vt-discount-text-${idx}" style="font-size: 11px; display:none;"></div>`;
      }

      row.innerHTML = `
        <td class="ps-4">
          <div class="combo-badge mb-1">${formatCombo(v.combo)}</div>
        </td>
        <td>
          <input type="text" class="form-control form-control-sm vt-bind" data-idx="${idx}" data-field="sku" value="${v.sku}">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-bind" data-idx="${idx}" data-field="buy_price" value="${v.buy_price}" step="0.01" placeholder="0.00">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-bind" data-idx="${idx}" data-field="price" value="${v.price}" step="0.01" placeholder="0.00">
          ${discountedPriceHtml}
        </td>
        <td>
          <select class="form-select form-select-sm vt-bind" data-idx="${idx}" data-field="discount_type">
            <option value="percent" ${v.discount_type === 'percent' ? 'selected' : ''}>%</option>
            <option value="fixed" ${v.discount_type === 'fixed' ? 'selected' : ''}>Fixed</option>
          </select>
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-bind" data-idx="${idx}" data-field="discount" value="${v.discount}" step="0.01" placeholder="0.00">
        </td>
        <td>
          <input type="date" class="form-control form-control-sm vt-bind vt-date-input ${v.discount_start ? 'has-value' : ''}" data-idx="${idx}" data-field="discount_start" value="${v.discount_start}">
        </td>
        <td>
          <input type="date" class="form-control form-control-sm vt-bind vt-date-input ${v.discount_end ? 'has-value' : ''}" data-idx="${idx}" data-field="discount_end" value="${v.discount_end}">
        </td>
        <td>
          <input type="number" class="form-control form-control-sm vt-bind" data-idx="${idx}" data-field="stock" value="${v.stock}" placeholder="0">
        </td>
        <td>
          <div class="vt-img-wrap position-relative">
            <label class="vt-img-upload-label" for="vt-file-${idx}" id="vt-label-${idx}" style="${v.imagePreview ? 'display:none;' : ''}">
              <i class="bi bi-cloud-arrow-up"></i> Upload
            </label>
            <input type="file" name="variants[${idx}][image]" id="vt-file-${idx}" class="d-none vt-file-input" data-idx="${idx}" accept="image/*">
            <img src="${v.imagePreview || ''}" class="vt-img-preview" id="vt-preview-${idx}" style="${v.imagePreview ? '' : 'display:none;'}" title="Click to change image">
          </div>
        </td>
        <td class="text-center">
          <div class="form-check form-switch d-inline-block m-0 p-0">
            <input class="form-check-input ms-0 vt-active-toggle" type="checkbox" role="switch" data-idx="${idx}" ${v.active ? 'checked' : ''}>
          </div>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger vt-remove-btn" data-idx="${idx}">
            <i class="bi bi-trash"></i> Remove
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });
  
    // Rebind events
    document.querySelectorAll('.vt-bind').forEach(el => {
      el.addEventListener('input', function() {
        variantState[this.dataset.idx][this.dataset.field] = this.value;
        
        if (['price', 'discount', 'discount_type'].includes(this.dataset.field)) {
            const idx = this.dataset.idx;
            const v = variantState[idx];
            const dPrice = calculateVariantDiscountedPrice(v.price, v.discount_type, v.discount);
            const textEl = document.getElementById(`vt-discount-text-${idx}`);
            if (textEl) {
                if (dPrice !== null) {
                    textEl.textContent = `After Discount: ৳${dPrice.toFixed(2)}`;
                    textEl.style.display = 'block';
                } else {
                    textEl.style.display = 'none';
                }
            }
        }

        if (this.classList.contains('vt-date-input')) {
           if (this.value) this.classList.add('has-value');
           else this.classList.remove('has-value');
        }
      });
    });
    
    document.querySelectorAll('.vt-active-toggle').forEach(el => {
      el.addEventListener('change', function() {
        variantState[this.dataset.idx].active = this.checked;
        if (this.checked) {
            this.closest('tr').classList.remove('opacity-50');
        } else {
            this.closest('tr').classList.add('opacity-50');
        }
      });
    });
  
    document.querySelectorAll('.vt-remove-btn').forEach(el => {
      el.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove this variant?')) {
            variantState.splice(this.dataset.idx, 1);
            renderVariants();
        }
      });
    });
  
    // File upload logic
    document.querySelectorAll('.vt-file-input').forEach(input => {
      input.addEventListener('change', function() {
        const file = this.files[0];
        const idx = this.dataset.idx;
        if (file) {
          variantState[idx].imageFile = file;
          const reader = new FileReader();
          reader.onload = e => {
            variantState[idx].imagePreview = e.target.result;
            document.getElementById(`vt-preview-${idx}`).src = e.target.result;
            document.getElementById(`vt-preview-${idx}`).style.display = 'inline-block';
            document.getElementById(`vt-label-${idx}`).style.display = 'none';
          };
          reader.readAsDataURL(file);
        }
      });
    });
    
    document.querySelectorAll('.vt-img-preview').forEach(img => {
      img.addEventListener('click', function() {
        const idx = this.id.split('-')[2];
        document.getElementById(`vt-file-${idx}`).click();
      });
    });
  
    if (variantCountBadge) {
      if (variantState.length > 0) {
        variantCountBadge.textContent = variantState.length + ' variant' + (variantState.length !== 1 ? 's' : '');
        variantCountBadge.style.display = '';
      } else {
        variantCountBadge.style.display = 'none';
        if (variantTableSection) variantTableSection.style.display = 'none';
      }
    }
  }

  // ── Bulk Actions ───────────────────────────────────────────────────
  window.applyBulkPrice = function() {
    const v = document.getElementById('bulkPrice').value;
    if (v !== '') { variantState.forEach(x => { x.price = v; }); renderVariants(); }
  };
  
  window.applyBulkStock = function() {
    const v = document.getElementById('bulkStock').value;
    if (v !== '') { variantState.forEach(x => { x.stock = v; }); renderVariants(); }
  };

  window.clearAllVariants = function() {
    if (confirm('Are you sure you want to remove all generated variants?')) {
        variantState = [];
        renderVariants();
    }
  };

  window.activateAll = function() { variantState.forEach(x => { x.active = true;  }); renderVariants(); };
  window.deactivateAll = function() { variantState.forEach(x => { x.active = false; }); renderVariants(); };

  // ── Form Submission ────────────────────────────────────────────────
  const form = document.getElementById('variantsHiddenContainer') ? document.getElementById('variantsHiddenContainer').closest('form') : document.querySelector('form');
  if (form) {
      form.addEventListener('submit', function(e) {
        const container = document.getElementById('variantsHiddenContainer');
        if(container) container.innerHTML = ''; 
        
        const isVariant = document.getElementById('typeVariant') && document.getElementById('typeVariant').checked;
        
        // Auto-fill general price and stock for Variant Products to pass backend validation
        if (isVariant) {
            let minPrice = 0;
            let totalStock = 0;
            let hasVariants = false;
            
            variantState.forEach(v => {
                if (v.price) {
                    const p = parseFloat(v.price);
                    if (!hasVariants || p < minPrice) minPrice = p;
                    hasVariants = true;
                }
                if (v.stock) {
                    totalStock += parseInt(v.stock) || 0;
                }
            });
            
            if (togglePriceInput) togglePriceInput.value = minPrice;
            if (toggleStockInput) toggleStockInput.value = totalStock;
        }
    
        const make = (name, value) => {
          const inp = document.createElement('input');
          inp.type = 'hidden'; inp.name = name; inp.value = value;
          if(container) container.appendChild(inp);
        };
    
        // Only submit variant details if Product Type is Variant
        if (isVariant) {
            variantState.forEach((v, idx) => {
                if (v.sku) make(`variants[${idx}][sku]`, v.sku);
                if (v.buy_price) make(`variants[${idx}][buy_price]`, v.buy_price);
                if (v.price) make(`variants[${idx}][price]`, v.price);
                if (v.discount_type) make(`variants[${idx}][discount_type]`, v.discount_type);
                if (v.discount) make(`variants[${idx}][discount]`, v.discount);
                if (v.discount_start) make(`variants[${idx}][discount_start]`, v.discount_start);
                if (v.discount_end) make(`variants[${idx}][discount_end]`, v.discount_end);
                if (v.stock) make(`variants[${idx}][stock]`, v.stock);
                make(`variants[${idx}][active]`, v.active ? 1 : 0);
                
                if (v.combo) {
                    Object.entries(v.combo).forEach(([label, value]) => {
                        make(`variants[${idx}][combo][${label}]`, value);
                    });
                }

                const fi = document.getElementById(`vt-file-${idx}`);
                if (fi && fi.files.length > 0) {
                    fi.name = `variants[${idx}][image]`;
                }
                
                if (v.removedDbImage) {
                    make(`variants[${idx}][removedDbImage]`, 1);
                }
            });
        } else {
            let idx = 0;
            Object.entries(selectedAttrs).forEach(([label, values]) => {
                values.forEach(val => {
                    make(`variants[${idx}][label]`, label);
                    make(`variants[${idx}][value]`, val);
                    idx++;
                });
            });
        }
      });
  }

    // ── Restore old() on validation failure & Edit Initialization ───────
  (function() {
    let labels = @json(old('variant_labels', []));
    let values = @json(old('variant_values', []));
    
    const dbVariants = [];
    
    // If no old() inputs (not a validation fail), try loading from DB
    if (labels.length === 0 && dbVariants.length > 0) {
        dbVariants.forEach(v => {
            labels.push(v.label);
            values.push(v.value);
        });
    }
    
    if (labels.length > 0) {
        labels.forEach((l, i) => {
          if (i === 0 || l !== labels[i - 1]) {
            (selectedAttrs[l] = selectedAttrs[l] || []);
          }
          if (!selectedAttrs[l].includes(values[i])) selectedAttrs[l].push(values[i]);
          const chk = document.querySelector(`.attribute-value-checkbox[data-attr-name="${l}"][value="${values[i]}"]`);
          if (chk) chk.checked = true;
        });
        
        // Auto-generate variants for Variant Products on Edit
        const isVariant = document.getElementById('typeVariant') && document.getElementById('typeVariant').checked;
        if (isVariant && typeof generateCombinations === 'function') {
            const generateBtn = document.getElementById('generateVariantsBtn');
            if (generateBtn) generateBtn.click();
            
            // Map prices/images from DB back to generated combos
            if (dbVariants.length > 0) {
                setTimeout(() => {
                    variantState.forEach(v => {
                        dbVariants.forEach(dbV => {
                            if (v.combo[dbV.label] === dbV.value) {
                                if (dbV.price && !v.price) v.price = dbV.price;
                                if (dbV.discount && !v.discount) v.discount = dbV.discount;
                                if (dbV.discount_start && !v.discount_start) v.discount_start = dbV.discount_start;
                                if (dbV.discount_end && !v.discount_end) v.discount_end = dbV.discount_end;
                                if (dbV.image && !v.imagePreview) {
                                    v.imagePreview = '/storage/' + dbV.image;
                                }
                            }
                        });
                    });
                    if (typeof renderVariants === 'function') renderVariants();
                }, 300);
            }
        }
    }
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
  const calcPriceInput = document.getElementById('priceInput');
  const discountTypeSelect = document.getElementById('discountTypeSelect');
  const discountValueInput = document.getElementById('discountValueInput');
  const discountedPriceText = document.getElementById('discountedPriceText');

  function calculateDiscountedPrice() {
    const price = parseFloat(calcPriceInput.value) || 0;
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

    if (discountedPrice < 0) discountedPrice = 0;

    discountedPriceText.textContent = `After Discount: ৳${discountedPrice.toFixed(2)}`;
    discountedPriceText.style.display = 'block';
  }

  const discountDatesRow = document.getElementById('discountDatesRow');
  function toggleDiscountDates() {
    if (discountTypeSelect && discountDatesRow) {
      discountDatesRow.style.display = discountTypeSelect.value ? 'flex' : 'none';
    }
  }

  if (calcPriceInput && discountTypeSelect && discountValueInput && discountedPriceText) {
    calcPriceInput.addEventListener('input', calculateDiscountedPrice);
    discountTypeSelect.addEventListener('change', calculateDiscountedPrice);
    discountValueInput.addEventListener('input', calculateDiscountedPrice);
    discountTypeSelect.addEventListener('change', toggleDiscountDates);

    calculateDiscountedPrice();
    toggleDiscountDates();
  }

  // Final Initialization
  setTimeout(() => {
    if ((typeof variantState !== 'undefined' && variantState.length > 0) || (document.getElementById('typeVariant') && document.getElementById('typeVariant').checked)) {
        const typeVar = document.getElementById('typeVariant');
        if (typeVar) {
            typeVar.checked = true;
            toggleProductType();
        }
    } else {
        const typeSimp = document.getElementById('typeSimple');
        if(typeSimp) typeSimp.checked = true;
        toggleProductType();
    }
  }, 150);

</script>
@endpush

