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
      <div class="col-md-6 mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" id="productName" class="form-control" value="{{ old('name') }}" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" id="productSlug" class="form-control" value="{{ old('slug') }}" required>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" id="categorySelect" class="form-select" required>
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Main Image</label>
        <input type="file" name="image" class="form-control">
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Sales Count</label>
        <input type="number" name="sales_count" class="form-control" value="{{ old('sales_count', 0) }}" required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Gallery Images <small class="text-muted">(multiple)</small></label>
        <input type="file" name="images[]" class="form-control" multiple>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Type</label>
        <select name="discount_type" class="form-select">
          <option value="">No Discount</option>
          <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
          <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Discount Value</label>
        <input type="number" name="discount_value" step="0.01" class="form-control" value="{{ old('discount_value', 0) }}">
      </div>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>

    <hr>
    <h5 class="fw-bold mb-3"><i class="bi bi-palette me-2 text-primary"></i>Attributes</h5>

    {{-- Attribute + Checkbox Picker --}}
    <div class="card border-0 bg-light p-3 mb-3 rounded-3">
      <div class="row g-3 align-items-start">

        {{-- Step 1: Choose attribute --}}
        <div class="col-md-4">
          <label class="form-label fw-semibold small">1. Select Attribute</label>
          <select id="attributeSelect" class="form-select form-select-sm">
            <option value="">— Choose —</option>
            @foreach($attributes as $attribute)
              <option value="{{ $attribute->id }}" data-name="{{ $attribute->name }}">{{ $attribute->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Step 2: Checkbox values (shown dynamically) --}}
        <div class="col-md-8">
          <label class="form-label fw-semibold small">2. Select Values</label>
          <div id="valueCheckboxes" class="p-2 rounded border bg-white" style="min-height:44px;">
            <span class="text-muted small" id="valuePlaceholder">Select an attribute first…</span>
          </div>
        </div>

      </div>
    </div>

    {{-- Summary table --}}
    <table class="table table-bordered table-sm align-middle mb-3" id="attributeChipsTable" style="display:none;">
      <thead class="table-light">
        <tr>
          <th style="width:160px;">Attribute</th>
          <th>Selected Values</th>
          <th style="width:50px;"></th>
        </tr>
      </thead>
      <tbody id="attributeChipsTbody"></tbody>
    </table>

    {{-- Hidden inputs (sent with form) --}}
    <div id="variantsContainer"></div>

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

  // ─── Slug Auto-generate ────────────────────────────────────────────
  document.getElementById('productName').addEventListener('input', function () {
    document.getElementById('productSlug').value = this.value
      .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
  });

  // ─── Attribute Data map ────────────────────────────────────────────
  const attributeData = @json(
    $attributes->mapWithKeys(fn($attr) => [
      $attr->id => $attr->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->values()
    ])
  );

  const attributeSelect   = document.getElementById('attributeSelect');
  const valueCheckboxes   = document.getElementById('valueCheckboxes');
  const valuePlaceholder  = document.getElementById('valuePlaceholder');
  const tbody             = document.getElementById('attributeChipsTbody');
  const table             = document.getElementById('attributeChipsTable');
  const container         = document.getElementById('variantsContainer');

  // Global state for selected variants: { "Color": ["Red", "Blue"], "Size": ["L"] }
  let selectedVariants = {};

  // ─── Sync global state to UI and hidden inputs ──────────────────────
  function syncVariants() {
    container.innerHTML = '';
    tbody.innerHTML = '';

    const keys = Object.keys(selectedVariants);

    if (keys.length === 0) {
      table.style.display = 'none';
      return;
    }

    keys.forEach(attrName => {
      const vals = selectedVariants[attrName];
      if (!vals || vals.length === 0) return;

      // 1. Add hidden inputs
      vals.forEach(v => {
        container.insertAdjacentHTML('beforeend',
          `<input type="hidden" name="variant_labels[]" value="${attrName}">` +
          `<input type="hidden" name="variant_values[]" value="${v}">`
        );
      });

      // 2. Add table row with chips
      const chipsHtml = vals.map(v =>
        `<span class="attr-chip"><i class="bi bi-check2"></i>${v}</span>`
      ).join('');

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${attrName}</strong></td>
        <td>${chipsHtml}</td>
        <td class="text-center">
          <button type="button" class="btn btn-outline-danger btn-sm" title="Remove">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      tbody.appendChild(tr);

      tr.querySelector('button').addEventListener('click', function () {
        delete selectedVariants[attrName];
        
        const currentAttrName = attributeSelect.options[attributeSelect.selectedIndex]?.dataset?.name;
        if (currentAttrName === attrName) {
          [...valueCheckboxes.querySelectorAll('input[type=checkbox]')].forEach(chk => chk.checked = false);
        }

        syncVariants();
      });
    });

    table.style.display = '';
  }

  // ─── Show checkboxes when attribute selected ───────────────────────
  attributeSelect.addEventListener('change', function () {
    const attrId = this.value;
    const attrName = this.options[this.selectedIndex]?.dataset?.name;
    valueCheckboxes.innerHTML = '';

    if (!attrId || !attributeData[attrId] || attributeData[attrId].length === 0) {
      valueCheckboxes.appendChild(Object.assign(document.createElement('span'), {
        className: 'text-muted small',
        textContent: attrId ? 'No values for this attribute.' : 'Select an attribute first…'
      }));
      return;
    }

    attributeData[attrId].forEach(function (item) {
      const uid = 'chk_' + attrId + '_' + item.id;
      const label = document.createElement('label');
      label.htmlFor = uid;

      const chk = document.createElement('input');
      chk.type = 'checkbox';
      chk.id   = uid;
      chk.value = item.value;

      if (selectedVariants[attrName] && selectedVariants[attrName].includes(item.value)) {
        chk.checked = true;
      }

      chk.addEventListener('change', function () {
        const checked = [...valueCheckboxes.querySelectorAll('input[type=checkbox]:checked')];
        if (checked.length > 0) {
          selectedVariants[attrName] = checked.map(c => c.value);
        } else {
          delete selectedVariants[attrName];
        }
        syncVariants();
      });

      label.appendChild(chk);
      label.appendChild(document.createTextNode(' ' + item.value));
      valueCheckboxes.appendChild(label);
    });
  });

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
</script>
@endpush

  