@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="category-page py-5" style="background: #f8f9fa; min-height: 70vh;">
    <div class="wrap container">
        <!-- Breadcrumb / Header -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h1 class="fw-bold mb-1 text-dark" style="font-size: 2.2rem; letter-spacing: -0.5px;">{{ $category->name }}</h1>
                <p class="text-muted mb-0">Browse our collection of premium quality products in {{ $category->name }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-dark px-3 py-2 fs-6 rounded-pill">{{ $products->total() }} Products</span>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                <i class="bi bi-box2 text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                <h4 class="text-muted fw-bold">No Products Found</h4>
                <p class="text-muted">There are no products in this category at the moment. Please check back later!</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 mt-2 rounded-pill fw-semibold">Back to Home</a>
            </div>
        @else
            <!-- Products Grid -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all" style="border-radius: 12px; overflow: hidden; background: #fff;">
                            <a href="{{ route('product.details', $product->slug) }}" class="d-block text-decoration-none">
                                <div class="position-relative overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 200px; padding: 15px;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid product-img transition-all" alt="{{ $product->name }}" style="max-height: 100%; object-fit: contain;">
                                    @else
                                        <img src="https://placehold.co/200x200/eee/aaa?text={{ urlencode(Str::limit($product->name, 8, '')) }}" class="img-fluid product-img transition-all" alt="{{ $product->name }}" style="max-height: 100%; object-fit: contain;">
                                    @endif
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-2">
                                    <span class="badge bg-light text-muted fw-normal px-2.5 py-1 text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">{{ $category->name }}</span>
                                </div>
                                <h5 class="card-title mb-2" style="font-size: 14.5px; font-weight: 600; line-height: 1.4; min-height: 40px;">
                                    <a href="{{ route('product.details', $product->slug) }}" class="text-dark text-decoration-none hover-blue">{{ Str::limit($product->name, 48) }}</a>
                                </h5>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fs-5 fw-bold text-dark">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-primary btn-custom-cart w-100 add-to-cart-btn py-2 px-1 d-inline-flex align-items-center justify-content-center gap-1.5 transition-all"
                                                style="border-radius: 8px; font-weight: 600; font-size: 11px;"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/200x200/eee/aaa?text=' . urlencode(Str::limit($product->name, 8, '')) }}">
                                                <i class="bi bi-cart-plus" style="font-size: 13px;"></i> Add
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-primary btn-custom-buy w-100 btn-bid py-2 px-1 d-inline-flex align-items-center justify-content-center gap-1.5 transition-all"
                                                style="border-radius: 8px; font-weight: 600; font-size: 11px;"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}"
                                                data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/200x200/eee/aaa?text=' . urlencode(Str::limit($product->name, 8, '')) }}">
                                                Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    .hover-blue:hover {
        color: #1a73e8 !important;
    }
</style>
@endsection
