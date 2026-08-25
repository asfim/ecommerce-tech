@extends('layouts.backend.app')

@section('title', 'Product Details')

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
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left"></i> Back to Products</a>
    <h4 class="d-inline-block mb-0">View Product</h4>
</div>

<div class="row g-4">
    @php
        $isVariantProduct = false;
        $totalStock = $product->stock;
        $displayBuyPrice = (float)$product->buy_price;
        $displaySellPrice = (float)$product->price;

        if(!empty($product->variants)) {
            $variantStock = 0;
            $variantBuyPriceSum = 0;
            $variantSellPriceSum = 0;
            $hasCombo = false;
            
            foreach($product->variants as $v) {
                if(isset($v['combo'])) { 
                    $hasCombo = true;
                    $isVariantProduct = true;
                    $variantStock += (int)($v['stock'] ?? 0);
                    $variantBuyPriceSum += (float)($v['buy_price'] ?? 0);
                    $variantSellPriceSum += (float)($v['price'] ?? 0);
                }
            }
            
            if($hasCombo) {
                $totalStock = $variantStock;
                $displayBuyPrice = $variantBuyPriceSum;
                $displaySellPrice = $variantSellPriceSum;
            }
        }
    @endphp
    <!-- Left Column: Details -->
    <div class="col-md-8">
        <div class="stat-card mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 class="fw-bold mb-1">{{ $product->name }}</h4>
                    <div class="text-muted small">
                        <i class="bi bi-tag me-1"></i> {{ $product->category->name ?? 'Uncategorized' }} 
                        @if($product->subCategory)
                            <i class="bi bi-chevron-right mx-1"></i> {{ $product->subCategory->name }}
                        @endif
                        <span class="mx-2">|</span>
                        <i class="bi bi-award me-1"></i> {{ $product->brand->name ?? 'No Brand' }}
                    </div>
                </div>
                <div>
                    @if($product->is_active)
                        <span class="badge bg-success fs-6 px-3"><i class="bi bi-check-circle me-1"></i> Active</span>
                    @else
                        <span class="badge bg-danger fs-6 px-3"><i class="bi bi-x-circle me-1"></i> Inactive</span>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted small mb-1">Buy Price</div>
                        <div class="fw-bold fs-5">৳{{ number_format($displayBuyPrice, 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted small mb-1">Sell Price</div>
                        <div class="fw-bold fs-5 text-primary">৳{{ number_format($displaySellPrice, 2) }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="text-muted small mb-1">Stock</div>
                        <div class="fw-bold fs-5 {{ $totalStock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $totalStock }} {{ $totalStock > 0 ? 'Units' : 'Out of Stock' }}
                        </div>
                    </div>
                </div>
            </div>

            @if($product->description)
                <h6 class="fw-bold border-bottom pb-2 mb-3">Description</h6>
                <div class="mb-4 text-secondary" style="font-size: 0.95rem;">
                    {!! $product->description !!}
                </div>
            @endif

            @if(!empty($product->specifications))
                <h6 class="fw-bold border-bottom pb-2 mb-3">Specifications</h6>
                <table class="table table-sm table-bordered">
                    <tbody>
                        @foreach($product->specifications as $spec)
                            <tr>
                                <th class="bg-light" style="width: 30%;">{{ $spec['label'] }}</th>
                                <td>{{ $spec['value'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if($isVariantProduct)
        <div class="stat-card mb-4">
            <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-grid me-2 text-primary"></i>Product Variants</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">Image</th>
                            <th>Variant Combination</th>
                            <th>SKU</th>
                            <th class="text-end">Buy Price (৳)</th>
                            <th class="text-end">Sell Price (৳)</th>
                            <th class="text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                            @if(isset($variant['combo']))
                            <tr>
                                <td>
                                    @if(!empty($variant['image']))
                                        <img src="{{ asset('storage/' . $variant['image']) }}" alt="Variant Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div style="width:50px;height:50px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @foreach($variant['combo'] as $key => $val)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($key) }}: {{ $val }}</span>
                                    @endforeach
                                </td>
                                <td><span class="text-muted">{{ $variant['sku'] ?? 'N/A' }}</span></td>
                                <td class="text-end text-muted fw-semibold">
                                    {{ !empty($variant['buy_price']) ? number_format((float)$variant['buy_price'], 2) : '-' }}
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    {{ !empty($variant['price']) ? number_format((float)$variant['price'], 2) : 'Default' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ ($variant['stock'] ?? 0) > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $variant['stock'] ?? 0 }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Images & Features -->
    <div class="col-md-4">
        <div class="stat-card mb-4">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-images me-2 text-primary"></i>Main Image</h6>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Main Image" class="img-fluid rounded border mb-3 w-100" style="object-fit: cover; max-height: 250px;">
            @else
                <div class="alert alert-secondary text-center mb-3 py-4">No main image uploaded</div>
            @endif

            @if(!empty($product->gallery_images) && is_array($product->gallery_images))
                <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Gallery Images</h6>
                <div class="row g-2">
                    @foreach($product->gallery_images as $img)
                        <div class="col-4">
                            <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded border w-100" style="object-fit: cover; aspect-ratio: 1/1;" alt="Gallery">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="stat-card">
            <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-star me-2 text-primary"></i>Highlights</h6>
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="text-muted">Featured Product</span>
                @if($product->is_featured)
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                @else
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">New Arrival</span>
                @if($product->is_new_arrival)
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                @else
                    <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                @endif
            </div>
        </div>
        
        <div class="mt-4 d-grid gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning fw-bold"><i class="bi bi-pencil me-2"></i>Edit Product</a>
            <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="btn btn-info text-white fw-bold"><i class="bi bi-eye me-2"></i>View in Storefront</a>
        </div>
    </div>
</div>
@endsection
