@extends('layouts.backend.app')

@section('title', 'Products')

@section('content')
    <div class="clearfix mb-4">
        <div class="dropdown float-end">
            <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}"
                    class="rounded-circle">
                <span>
                    <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
                    <span class="role">eCommerce</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        <h4>Products</h4>
    </div>

    <div class="stat-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 fw-semibold small">Show</label>
                    <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;">
                        @foreach (['all' => 'All', 10 => '10', 20 => '20', 50 => '50', 100 => '100'] as $value => $label)
                            <option value="{{ $value }}" {{ (string) $perPage === (string) $value ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    <label class="form-label mb-0 fw-semibold small">entries</label>
                </div>

                <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2">
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                    <input style="border-color: #a1a1a1 !important; width: 230px !important;" type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search products..." style="width:230px;">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.products.index', request('per_page') ? ['per_page' => request('per_page')] : []) }}" class="btn btn-outline-secondary btn-sm" title="Clear Search"><i class="bi bi-x-lg"></i></a>
                    @endif
                </form>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm" style="display: none;">
                    <i class="bi bi-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add
                    Product</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"><input type="checkbox" id="selectAllProducts" class="form-check-input"></th>
                    <th style="width: 60px;">#</th>
                    <th style="width: 60px;">Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Buy Price</th>
                    <th>Sell Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="text-center"><input type="checkbox" class="product-checkbox form-check-input" value="{{ $product->id }}"></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @php
                                $displayImage = $product->image;
                                if (!$displayImage && !empty($product->variants)) {
                                    foreach ($product->variants as $v) {
                                        if (isset($v['combo']) && !empty($v['image'])) {
                                            $displayImage = $v['image'];
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if($displayImage)
                                <img src="{{ asset('storage/' . $displayImage) }}" alt="Product Image" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 40px; height: 40px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">No Img</div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $product->name }}</span>
                                @php
                                    $isVariantProduct = false;
                                    $totalStock = $product->stock;
                                    $displayBuyPrice = (float)$product->buy_price;
                                    $displaySellPrice = (float)$product->price;

                                    if(!empty($product->variants)) {
                                        $variantStock = 0;
                                        $variantBuyPriceSum = 0;
                                        $variantSellPriceSum = 0;
                                        foreach($product->variants as $v) {
                                            if(isset($v['combo'])) { 
                                                $isVariantProduct = true;
                                                $variantStock += (int)($v['stock'] ?? 0);
                                                $variantBuyPriceSum += (float)($v['buy_price'] ?? 0);
                                                $variantSellPriceSum += (float)($v['price'] ?? 0);
                                            }
                                        }
                                        if($isVariantProduct) {
                                            $totalStock = $variantStock;
                                            $displayBuyPrice = $variantBuyPriceSum;
                                            $displaySellPrice = $variantSellPriceSum;
                                        }
                                    }
                                @endphp
                                @if($isVariantProduct)
                                    <span class="badge bg-success mt-1 px-2 py-1 fw-normal" style="width: fit-content; font-size: 10px; letter-spacing: 0.5px;">Variant</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $displayBuyPrice ? '৳' . number_format($displayBuyPrice, 2) : '-' }}</td>
                        <td>৳{{ number_format($displaySellPrice, 2) }}</td>
                        <td>{{ $totalStock }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input active-toggle" type="checkbox" data-id="{{ $product->id }}"
                                    {{ $product->is_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info text-white" title="View Product"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit Product"><i
                                        class="bi bi-pencil"></i></a>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Landing Page">
                                        <i class="bi bi-layout-text-window-reverse"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.landing-page.create', $product) }}">
                                                <i class="bi bi-plus-circle me-2"></i>Create
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('landing.show', $product->slug) }}" target="_blank">
                                                <i class="bi bi-eye me-2"></i>View
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline-block m-0"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete Product"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($perPage !== 'all' && $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <form id="bulkDeleteForm" action="{{ route('admin.products.bulk-destroy') }}" method="POST" style="display: none;">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>

    <script>
        document.getElementById('perPageSelect').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });

        document.querySelectorAll('.featured-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const productId = this.dataset.id;
                fetch(`/admin/products/${productId}/toggle-featured`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        // toggle reflects server state
                    })
                    .catch(() => {
                        // revert on error
                        this.checked = !this.checked;
                    });
            });
        });

        document.querySelectorAll('.active-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const productId = this.dataset.id;
                fetch(`/admin/products/${productId}/toggle-active`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        // toggle reflects server state
                    })
                    .catch(() => {
                        // revert on error
                        this.checked = !this.checked;
                    });
            });
        });

        document.querySelectorAll('.new-arrival-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const productId = this.dataset.id;
                fetch(`/admin/products/${productId}/toggle-new-arrival`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        // toggle reflects server state
                    })
                    .catch(() => {
                        // revert on error
                        this.checked = !this.checked;
                    });
            });
        });

        // Bulk delete logic
        const selectAllCheckbox = document.getElementById('selectAllProducts');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

        function updateBulkDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            const count = checkedBoxes.length;

            if (count > 0) {
                bulkDeleteBtn.style.display = 'inline-block';
                selectedCountSpan.textContent = count;
            } else {
                bulkDeleteBtn.style.display = 'none';
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
                updateBulkDeleteButton();
            });
        }

        productCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!this.checked && selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                const allChecked = Array.from(productCheckboxes).every(c => c.checked);
                if (allChecked && selectAllCheckbox) {
                    selectAllCheckbox.checked = true;
                }
                updateBulkDeleteButton();
            });
        });

        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete selected products?')) {
                    const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
                    bulkDeleteInputs.innerHTML = '';
                    checkedBoxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkDeleteInputs.appendChild(input);
                    });
                    bulkDeleteForm.submit();
                }
            });
        }
    </script>
@endsection
