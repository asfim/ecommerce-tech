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
            <div class="row g-3">
                @foreach($products as $product)
                    @include('frontend.partials.product_card', ['product' => $product])
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
