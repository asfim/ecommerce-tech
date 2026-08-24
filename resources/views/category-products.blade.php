@extends('layouts.app')

@section('title', $selectedSubCategory ? $selectedSubCategory->name : $category->name)

@push('styles')
    <style>
        /* ── Page Layout ─────────────────────────────── */
        .cat-page {
            background: #f4f6f8;
            min-height: 80vh;
            padding: 28px 0 48px;
        }

        /* ── Filter Sidebar ──────────────────────────── */
        .filter-sidebar {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e6e9ef;
            overflow: hidden;
            position: sticky;
            top: 18px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .06);
        }

        .filter-head {
            background: #1a73e8;
            color: #fff;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            letter-spacing: .02em;
        }

        .filter-head .reset-link {
            font-size: 11px;
            color: #b3d1ff;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
        }

        .filter-head .reset-link:hover {
            color: #fff;
        }

        .filter-section {
            border-bottom: 1px solid #f0f0f0;
        }

        .filter-section:last-child {
            border-bottom: none;
        }

        .filter-section-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background: none;
            border: none;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #1a1a2e;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .filter-section-btn i {
            transition: transform .2s;
            font-size: 12px;
            color: #888;
        }

        .filter-section-btn.collapsed i {
            transform: rotate(-90deg);
        }

        .filter-section-body {
            padding: 4px 16px 14px;
        }

        /* Price range */
        .price-inputs {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .price-input-box {
            flex: 1;
            border: 1px solid #dde;
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 13px;
            text-align: center;
            color: #333;
            width: 0;
        }

        .price-input-box:focus {
            outline: none;
            border-color: #1a73e8;
        }

        .price-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 4px;
            border-radius: 2px;
            outline: none;
            cursor: pointer;
        }

        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #1a73e8;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 1px 5px rgba(26, 115, 232, .5);
        }

        .btn-price-go {
            width: 100%;
            margin-top: 10px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-price-go:hover {
            background: #1558b0;
        }

        /* Brand checkboxes */
        .brand-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            cursor: pointer;
            font-size: 13px;
            color: #333;
            border-radius: 5px;
            transition: background .12s;
        }

        .brand-item:hover {
            background: #f5f7ff;
            padding-left: 4px;
        }

        .brand-item input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #1a73e8;
            cursor: pointer;
        }

        /* ── Products Area ───────────────────────────── */
        .products-area-header {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .05);
        }

        .prod-count {
            font-size: 14px;
            color: #555;
        }

        .prod-count span {
            color: #1a73e8;
            font-weight: 700;
        }

        .sort-select {
            border: 1px solid #dde;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            background: #f9f9f9;
        }

        .sort-select:focus {
            outline: none;
            border-color: #1a73e8;
        }

        .view-toggle {
            display: flex;
            gap: 4px;
        }

        .view-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dde;
            border-radius: 6px;
            background: #f9f9f9;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 14px;
            transition: all .15s;
        }

        .view-btn.active,
        .view-btn:hover {
            background: #1a73e8;
            color: #fff;
            border-color: #1a73e8;
        }

        /* ── Product Grid Card ───────────────────────── */
        .pcat-card {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            transition: box-shadow .2s, transform .2s;
            display: flex;
            flex-direction: column;
        }

        .pcat-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, .1);
            transform: translateY(-3px);
        }

        .pcat-img-wrap {
            position: relative;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 180px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pcat-img {
            max-height: 160px;
            max-width: 90%;
            object-fit: contain;
            transition: transform .3s;
        }

        .pcat-card:hover .pcat-img {
            transform: scale(1.05);
        }

        .pcat-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #dc3545;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 4px;
            padding: 2px 7px;
        }

        .pcat-badge-stock {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 9px;
            font-weight: 700;
            border-radius: 4px;
            padding: 2px 7px;
        }

        .pcat-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .pcat-name {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.4;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pcat-name:hover {
            color: #1a73e8;
        }

        .pcat-price {
            font-size: 16px;
            font-weight: 800;
            color: #dc3545;
            margin-bottom: 4px;
        }

        .pcat-price .tk-sym {
            font-size: 1.2em;
            font-weight: 900;
        }

        .pcat-price-old {
            font-size: 12px;
            color: #aaa;
            text-decoration: line-through;
            margin-left: 6px;
            font-weight: 500;
        }

        .pcat-stock-in {
            font-size: 11px;
            color: #28a745;
            font-weight: 600;
        }

        .pcat-stock-out {
            font-size: 11px;
            color: #dc3545;
            font-weight: 600;
        }

        .pcat-actions {
            margin-top: auto;
            padding-top: 10px;
            display: flex;
            gap: 6px;
        }

        .pcat-btn-cart {
            flex: 1;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .pcat-btn-cart:hover {
            background: #1558b0;
            color: #fff;
        }

        .pcat-btn-buy {
            flex: 1;
            background: #ff6d00;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .pcat-btn-buy:hover {
            background: #e55d00;
            color: #fff;
        }

        /* ── List View ───────────────────────────────── */
        .list-view-card {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px;
            margin-bottom: 10px;
            transition: box-shadow .2s;
        }

        .list-view-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, .09);
        }

        .lvc-img-wrap {
            width: 130px;
            min-width: 130px;
            height: 120px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .lvc-img {
            max-width: 110px;
            max-height: 110px;
            object-fit: contain;
        }

        .lvc-info {
            flex: 1;
            min-width: 0;
        }

        .lvc-name {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .lvc-name:hover {
            color: #1a73e8;
        }

        .lvc-price {
            font-size: 18px;
            font-weight: 800;
            color: #dc3545;
        }

        .lvc-price-old {
            font-size: 13px;
            color: #aaa;
            text-decoration: line-through;
            margin-left: 6px;
        }

        .lvc-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 110px;
        }

        /* ── Filter Pills ────────────────────────────── */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }

        .filter-pill {
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .filter-pill .rm {
            cursor: pointer;
            font-size: 14px;
        }

        /* ── Loader ──────────────────────────────────── */
        #productArea {
            position: relative;
        }

        #filterLoader {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .8);
            border-radius: 10px;
            z-index: 10;
            align-items: center;
            justify-content: center;
        }

        #filterLoader.active {
            display: flex;
        }

        .spin-ring {
            width: 38px;
            height: 38px;
            border: 4px solid #eee;
            border-top-color: #1a73e8;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Mobile Toggle ───────────────────────────── */
        .btn-filter-mobile {
            display: none;
            align-items: center;
            gap: 6px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 12px;
        }

        @media (max-width: 991px) {
            .btn-filter-mobile {
                display: flex;
            }

            #filterSidebar {
                display: none;
            }

            #filterSidebar.open {
                display: block;
            }
        }

        #productsGrid.list-mode {
            display: block !important;
        }

        /* ── Ryans-style Category Card ───────────────────── */
        .rcat-card {
            background: #fff;
            border: 1px solid #e2e5ea;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: box-shadow .22s, border-color .22s;
        }

        .rcat-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, .13);
            border-color: #c5cad4;
        }

        /* Discount ribbon */
        .rcat-ribbon {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 4;
            background: #FF5521;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 8px 0 8px 0;
            letter-spacing: .3px;
        }

        /* Image */
        .rcat-img-link {
            display: block;
        }

        .rcat-img-wrap {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            padding: 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .rcat-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform .32s ease;
            display: block;
        }

        .rcat-card:hover .rcat-img {
            transform: scale(1.05);
        }

        /* Body */
        .rcat-body {
            padding: 10px 12px 6px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .rcat-name {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.45;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 38px;
            transition: color .15s;
        }

        .rcat-name:hover {
            color: #FF5521;
        }

        .rcat-stock-row {
            margin-bottom: 6px;
        }

        .rcat-in-stock {
            font-size: 11.5px;
            color: #2e7d32;
            font-weight: 600;
        }

        .rcat-out-stock {
            font-size: 11.5px;
            color: #c62828;
            font-weight: 600;
        }

        .rcat-price-row {
            display: flex;
            align-items: baseline;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 2px;
        }

        .rcat-price {
            font-size: 17px;
            font-weight: 800;
            color: #FF5521;
        }

        .rcat-tk {
            font-size: 1.1em;
            font-weight: 900;
        }

        .rcat-price-old {
            font-size: 12px;
            color: #9e9e9e;
            text-decoration: line-through;
            font-weight: 500;
        }

        .rcat-tk-old {
            font-size: 1em;
        }

        /* Footer button */
        .rcat-footer {
            padding: 8px 12px 10px;
        }

        .rcat-btn-cart {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            padding: 8px 10px;
            background: #FF5521;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s;
        }

        .rcat-btn-cart:hover {
            background: #111826;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="cat-page">
        <div class="container">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a>
                    </li>
                    @if ($selectedSubCategory)
                        <li class="breadcrumb-item"><a href="{{ route('category.products', $category->id) }}"
                                class="text-decoration-none text-muted">{{ $category->name }}</a></li>
                        <li class="breadcrumb-item active fw-semibold">{{ $selectedSubCategory->name }}</li>
                    @else
                        <li class="breadcrumb-item active fw-semibold">{{ $category->name }}</li>
                    @endif
                </ol>
            </nav>

            {{-- Mobile filter btn --}}
            <button class="btn-filter-mobile" id="filterToggleBtn"><i class="bi bi-sliders2"></i> Filters</button>

            <div class="row g-3">
                
                {{-- ── PRODUCTS ── --}}
                <div class="col-12">

                    {{-- Header bar --}}
                    <div class="products-area-header">
                        <div class="prod-count">
                            <span id="totalCount">{{ $products->total() }}</span> Products found
                        </div>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @php
                                $sMin = (int) ($priceRange->min_price ?? 0);
                                $sMax = (int) ($priceRange->max_price ?? 10000);
                                $cMax = (int) request('max_price', $sMax);
                                $cMin = (int) request('min_price', $sMin);
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size: 13px; font-weight: 600; color: #555;">Price:</span>
                                <input type="number" class="sort-select" id="minPriceInput"
                                    value="{{ $cMin }}" min="{{ $sMin }}" max="{{ $sMax }}"
                                    placeholder="Min" style="width: 80px; padding: 4px 8px;">
                                <span class="text-muted">-</span>
                                <input type="number" class="sort-select" id="maxPriceInput"
                                    value="{{ $cMax }}" min="{{ $sMin }}" max="{{ $sMax }}"
                                    placeholder="Max" style="width: 80px; padding: 4px 8px;">
                                <button class="btn btn-sm btn-primary" id="applyFilterBtn" style="padding: 4px 10px; font-size: 13px;" title="Apply"><i class="bi bi-search"></i></button>
                                <button class="btn btn-sm btn-secondary" id="clearFilterBtn" style="padding: 4px 10px; font-size: 13px;" title="Clear"><i class="bi bi-x-circle"></i></button>
                            </div>

                            <select class="sort-select" id="sortSelect">
                                <option value="">Sort By: Default</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price:
                                    Low to High</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First
                                </option>
                            </select>
                            <div class="view-toggle">
                                <button class="view-btn active" id="gridViewBtn" title="Grid View"><i
                                        class="bi bi-grid"></i></button>
                                <button class="view-btn" id="listViewBtn" title="List View"><i
                                        class="bi bi-list-ul"></i></button>
                            </div>
                        </div>
                    </div>

                    {{-- Active filter pills --}}
                    <div class="active-filters" id="activePills"></div>

                    {{-- Product grid / list --}}
                    <div id="productArea">
                        <div id="filterLoader">
                            <div class="spin-ring"></div>
                        </div>

                        <div id="emptyState" style="display:none;" class="text-center py-5 bg-white rounded-3 border">
                            <i class="bi bi-box2 text-muted d-block mb-2" style="font-size:3rem;"></i>
                            <h5 class="text-muted fw-bold">No Products Found</h5>
                            <p class="text-muted small">Try adjusting or clearing filters.</p>
                        </div>

                        <div class="row g-3" id="productsGrid">
                            @forelse($products as $product)
                                <div class="col-6 col-md-4 col-lg-3">
                                    @include('frontend.partials.category_product_card', [
                                        'product' => $product,
                                    ])
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-white rounded-3 border">
                                        <i class="bi bi-box2 text-muted d-block mb-2" style="font-size:3rem;"></i>
                                        <h5 class="text-muted fw-bold">No Products Found</h5>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4" id="paginationWrap">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const baseUrl = '{{ route('category.products', $category->id) }}';
            const sMax = {{ $sMax }};
            const sMin = {{ $sMin }};

            const slider = document.getElementById('priceSlider');
            const minInput = document.getElementById('minPriceInput');
            const maxInput = document.getElementById('maxPriceInput');
            const applyBtn = document.getElementById('applyFilterBtn');
            const clearBtn = document.getElementById('clearFilterBtn');
            const sortSel = document.getElementById('sortSelect');
            const grid = document.getElementById('productsGrid');
            const loader = document.getElementById('filterLoader');
            const totalCount = document.getElementById('totalCount');
            const pillsWrap = document.getElementById('activePills');
            const emptyState = document.getElementById('emptyState');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            const filterToggle = document.getElementById('filterToggleBtn');
            const sidebar = document.getElementById('filterSidebar');

            const urlP = new URLSearchParams(window.location.search);
            let curMin = parseInt(urlP.get('min_price') || sMin);
            let curMax = parseInt(urlP.get('max_price') || sMax);
            let curSort = urlP.get('sort') || '';
            let curBrands = urlP.get('brands') ? urlP.get('brands').split(',') : [];

            // Sync inputs
            if (minInput) minInput.value = curMin;
            if (maxInput) maxInput.value = curMax;
            if (slider) {
                slider.value = curMax;
                updateTrack();
            }
            updatePills();

            // Slider <-> maxInput sync
            slider && slider.addEventListener('input', function() {
                curMax = parseInt(this.value);
                if (maxInput) maxInput.value = curMax;
                updateTrack();
            });

            maxInput && maxInput.addEventListener('input', function() {
                let v = parseInt(this.value) || sMax;
                v = Math.min(Math.max(v, sMin), sMax);
                curMax = v;
                if (slider) {
                    slider.value = v;
                    updateTrack();
                }
            });

            minInput && minInput.addEventListener('input', function() {
                let v = parseInt(this.value) || sMin;
                curMin = Math.min(Math.max(v, sMin), sMax);
            });


            // Apply
            applyBtn && applyBtn.addEventListener('click', doFilter);

            // Sort
            sortSel && sortSel.addEventListener('change', function() {
                curSort = this.value;
                doFilter();
            });

            // Clear
            clearBtn && clearBtn.addEventListener('click', function() {
                curMin = sMin;
                curMax = sMax;
                curSort = '';
                if (slider) {
                    slider.value = sMax;
                    updateTrack();
                }
                if (minInput) minInput.value = sMin;
                if (maxInput) maxInput.value = sMax;
                if (sortSel) sortSel.value = '';
                updatePills();
                doFilter();
            });

            // Mobile toggle
            filterToggle && filterToggle.addEventListener('click', () => sidebar && sidebar.classList.toggle('open'));

            // View toggle
            gridBtn && gridBtn.addEventListener('click', () => {
                grid.classList.remove('list-mode');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            });
            listBtn && listBtn.addEventListener('click', () => {
                grid.classList.add('list-mode');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            });

            function buildParams() {
                const p = new URLSearchParams();
                if (curMin > sMin) p.set('min_price', curMin);
                if (curMax < sMax) p.set('max_price', curMax);
                if (curSort) p.set('sort', curSort);
                return p;
            }

            function doFilter() {
                const params = buildParams();
                const url = baseUrl + (params.toString() ? '?' + params : '');
                loader && loader.classList.add('active');
                if (grid) grid.style.opacity = '.4';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (grid) {
                            grid.innerHTML = data.html || '';
                            grid.style.opacity = '1';
                        }
                        const pWrap = document.getElementById('paginationWrap');
                        if (pWrap && data.pagination !== undefined) {
                            pWrap.innerHTML = data.pagination;
                        }
                        if (totalCount) totalCount.textContent = data.total || 0;
                        if (emptyState) emptyState.style.display = (data.total === 0) ? 'block' : 'none';
                        updatePills();
                        window.history.replaceState({}, '', url);
                    })
                    .catch(() => {
                        if (grid) grid.style.opacity = '1';
                    })
                    .finally(() => loader && loader.classList.remove('active'));
            }

            function updatePills() {
                if (!pillsWrap) return;
                pillsWrap.innerHTML = '';
                if (curMax < sMax || curMin > sMin) {
                    pillsWrap.appendChild(pill('৳' + curMin.toLocaleString() + ' - ৳' + curMax.toLocaleString(), () => {
                        curMin = sMin;
                        curMax = sMax;
                        if (slider) {
                            slider.value = sMax;
                            updateTrack();
                        }
                        if (minInput) minInput.value = sMin;
                        if (maxInput) maxInput.value = sMax;
                        doFilter();
                    }));
                }
                curBrands.forEach(id => {
                    const cb = document.querySelector('.brand-filter-cb[value="' + id + '"]');
                    const nm = cb ? cb.closest('label').textContent.trim() : id;
                    pillsWrap.appendChild(pill(nm, () => {
                        curBrands = curBrands.filter(b => b !== id);
                        if (cb) cb.checked = false;
                        doFilter();
                    }));
                });
            }

            function pill(txt, fn) {
                const el = document.createElement('span');
                el.className = 'filter-pill';
                el.innerHTML = txt + ' <span class="rm">&times;</span>';
                el.querySelector('.rm').addEventListener('click', fn);
                return el;
            }

            function updateTrack() {
                if (!slider) return;
                const pct = ((parseFloat(slider.value) - sMin) / (sMax - sMin)) * 100;
                slider.style.background =
                    `linear-gradient(to right,#1a73e8 0%,#1a73e8 ${pct}%,#e0e0e0 ${pct}%,#e0e0e0 100%)`;
            }
        })();
    </script>
@endpush
