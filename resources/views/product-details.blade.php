@extends('layouts.app')

@section('title', $product->name . ' - Product Details')

@push('styles')
<style>
.main-image {
    overflow: hidden;
    border-radius: 12px;
    position: relative;
    cursor: crosshair;
    background: #fff;
    border: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 450px;
}

.product-main {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
    user-select: none;
    pointer-events: none;
}

/* Daraz-style zoom result panel */
#zoomResult {
    display: none;
    position: absolute;
    top: 0;
    left: calc(100% + 16px);
    width: 420px;
    height: 420px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    background-color: #fff;
    background-repeat: no-repeat;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
    z-index: 200;
    overflow: hidden;
    pointer-events: none;
}

/* Zoom lens indicator on main image */
#zoomLens {
    display: none;
    position: absolute;
    border: 2px solid #1a73e8;
    background: rgba(26,115,232,0.10);
    pointer-events: none;
    cursor: crosshair;
    z-index: 10;
    border-radius: 4px;
}

.gallery-wrap {
    position: relative;
}

@media (max-width: 991px) {
    #zoomResult { display: none !important; }
    .main-image { cursor: default; }
}

.thumbs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.thumb {
    width: 80px;
    height: 80px;
    object-fit: contain;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    opacity: 0.7;
}

.thumb:hover {
    border-color: #1a73e8;
    opacity: 1;
    transform: scale(1.05);
}

.thumb.active {
    border-color: #1a73e8;
    opacity: 1;
    box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.25);
}

.description {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #eee;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
}

#lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 1050;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

#lightbox.active {
    display: flex;
}

#lightbox img {
    max-width: 90%;
    max-height: 80%;
    object-fit: contain;
    border-radius: 8px;
    transition: transform 0.15s ease;
    transform-origin: center center;
}

.lightbox-tools {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    background: rgba(0, 0, 0, 0.55);
    padding: 8px 14px;
    border-radius: 8px;
    backdrop-filter: blur(4px);
}

.lightbox-tools button {
    color: #fff;
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.6);
    height: 36px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.lightbox-tools button:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #fff;
}

#closeLightbox {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 40px;
    color: #fff;
    cursor: pointer;
    font-weight: bold;
}

#prevImage,
#nextImage {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 40px;
    color: #fff;
    background: rgba(0, 0, 0, 0.5);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

#prevImage {
    left: 20px;
}

#nextImage {
    right: 20px;
}

#prevImage:hover,
#nextImage:hover {
    background: rgba(0, 0, 0, 0.8);
}

.color-btn.active {
    background-color: #1a73e8 !important;
    color: #fff;
    border-color: #1a73e8;
}

.variant-btn {
    background-color: #ffffff !important;
    color: #000000 !important;
    border-color: #000000 !important;
    transition: all 0.2s ease-in-out;
}

.variant-btn:hover {
    background-color: #f8f9fa !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.variant-btn.active {
    background-color: #1a73e8 !important;
    color: #ffffff !important;
    border-color: #1a73e8 !important;
}

.variant-btn.active:hover {
    background-color: #155cb0 !important;
    color: #ffffff !important;
    border-color: #155cb0 !important;
}

.variant-btn.variant-disabled,
.variant-btn:disabled {
    opacity: 0.35 !important;
    cursor: not-allowed !important;
    background-color: #f5f5f5 !important;
    color: #aaa !important;
    border-color: #ddd !important;
    text-decoration: line-through !important;
    pointer-events: none !important;
}

.tab-content-panel {
    display: none;
    padding: 20px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 8px 8px;
}

.tab-content-panel.active {
    display: block;
}

.review-item {
    margin-bottom: 15px;
}

#starRating {
    font-size: 24px;
    cursor: pointer;
}

#starRating i {
    margin-right: 5px;
    transition: color 0.2s;
}

#starRating i:hover,
#starRating i.hovered {
    color: #ffc107 !important;
}

#productDetailTabs button.nav-link {
    cursor: pointer !important;
    border: none !important;
    background-color: transparent !important;
    color: #000000 !important;
    padding: 10px 20px !important;
    border-radius: 6px 6px 0 0 !important;
    transition: background-color 0.3s ease, color 0.3s ease !important;
    transform: none !important;
}

#productDetailTabs button.nav-link:hover {
    background-color: #1a73e8 !important;
    color: #ffffff !important;
    border: none !important;
    transform: none !important;
}

#productDetailTabs button.nav-link.active {
    background-color: #1a73e8 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    border: none !important;
    transform: none !important;
}

.related-card {
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.related-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
}

.related-card .card-img-top {
    height: 180px;
    object-fit: contain;
    background: #fff;
}

.cart-btn {
    transition: all 0.3s ease;
}

.cart-btn:active {
    transform: scale(0.97);
}

</style>
@endpush

@section('content')
@php
    $allImages = [];
    if ($product->image) {
        $allImages[] = asset('storage/' . $product->image);
    }
    if (is_array($product->images)) {
        foreach ($product->images as $img) {
            $allImages[] = asset('storage/' . $img);
        }
    }
    if (is_array($product->variants)) {
        foreach ($product->variants as $variant) {
            if (!empty($variant['image'])) {
                $variantImageUrl = asset('storage/' . $variant['image']);
                if (!in_array($variantImageUrl, $allImages)) {
                    $allImages[] = $variantImageUrl;
                }
            }
        }
    }
    if (empty($allImages)) {
        $allImages[] = 'https://placehold.co/500x500/eee/aaa?text=' . urlencode($product->name);
    }

    $hasDiscount = $product->has_active_discount;
    $finalPrice = $product->price;
    if ($hasDiscount) {
        if ($product->discount_type === 'percent') {
            $finalPrice = $product->price - ($product->price * $product->discount_value) / 100;
        } elseif ($product->discount_type === 'fixed') {
            $finalPrice = $product->price - $product->discount_value;
        }
    }

    $groupedVariants = [];
    if (is_array($product->variants)) {
        foreach ($product->variants as $variant) {
            if (isset($variant['combo']) && is_array($variant['combo'])) {
                foreach ($variant['combo'] as $label => $val) {
                    $labelKey = strtolower(trim($label));
                    if (!isset($groupedVariants[$labelKey])) {
                        $groupedVariants[$labelKey] = [];
                    }
                    if (!in_array($val, $groupedVariants[$labelKey])) {
                        $groupedVariants[$labelKey][] = $val;
                    }
                }
            } elseif (isset($variant['label']) && isset($variant['value'])) {
                $labelKey = strtolower(trim($variant['label']));
                if (!isset($groupedVariants[$labelKey])) {
                    $groupedVariants[$labelKey] = [];
                }
                if (!in_array($variant['value'], $groupedVariants[$labelKey])) {
                    $groupedVariants[$labelKey][] = $variant['value'];
                }
            }
        }
    }

    $reviewCount = $product->reviews->count();
    $averageRating = $reviewCount > 0 ? round($product->reviews->avg('rating')) : 0;
@endphp

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- LEFT: Gallery -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="gallery-wrap">
                    <div class="main-image" id="mainImageWrap">
                        <img id="mainImage" src="{{ $allImages[0] }}" class="img-fluid rounded product-main">
                        <div id="zoomLens"></div>
                    </div>
                    <div id="zoomResult"></div>
                </div>
                <div class="thumbs mt-3">
                    @foreach($allImages as $idx => $imgUrl)
                        <img src="{{ $imgUrl }}" class="thumb {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}">
                    @endforeach
                </div>
            </div>

            <!-- RIGHT: Details -->
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->category->name ?? 'Product' }}</li>
                    </ol>
                </nav>
                <h2 class="fw-bold">{{ $product->name }}</h2>

                @if($reviewCount > 0)
                <div class="mb-3 d-flex align-items-center gap-2">
                    <div class="rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted small">({{ $reviewCount }} Review{{ $reviewCount === 1 ? '' : 's' }})</span>
                </div>
                @endif

                <h2 class="text-danger fw-bold d-flex align-items-center gap-2 flex-wrap">
                    ৳{{ number_format($finalPrice, 2) }}
                    @if ($hasDiscount)
                        <del class="text-muted fs-5">৳{{ number_format($product->price, 2) }}</del>
                        @if ($product->discount_type === 'percent')
                            <span class="badge bg-danger fs-6" style="padding: 4px 10px; border-radius: 4px; font-weight: 700;">{{ round($product->discount_value) }}% OFF</span>
                        @else
                            <span class="badge bg-danger fs-6" style="padding: 4px 10px; border-radius: 4px; font-weight: 700;">৳{{ round($product->discount_value) }} OFF</span>
                        @endif
                    @endif
                </h2>



                <hr>

                <!-- Product Variants Selector -->
                @if (count($groupedVariants) > 0)
                    @foreach($groupedVariants as $label => $values)
                        <div class="mb-3 variant-group" data-label="{{ $label }}">
                            <label class="fw-bold text-dark small mb-2">{{ ucfirst($label) }}</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($values as $vIdx => $val)
                                    <button type="button" class="btn btn-sm btn-outline-dark variant-btn" data-value="{{ $val }}">{{ $val }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Quantity input -->
                <div class="mb-4">
                    <label class="fw-bold text-dark small mb-2">Quantity</label>
                    <div class="d-flex align-items-center">
                        <button id="minus" class="btn btn-sm btn-outline-dark" style="width:36px; height:36px;">-</button>
                        <input id="qty" type="text" value="1" class="form-control text-center mx-2 form-control-sm" style="width:60px; height:36px; border-color: #a1a1a1 !important;" readonly>
                        <button id="plus" class="btn btn-sm btn-outline-dark" style="width:36px; height:36px;">+</button>
                    </div>
                </div>

                <!-- Cart Actions -->
                <div class="d-flex gap-2 align-items-center details-actions">
                    <button type="button" class="btn btn-custom-cart btn-lg flex-fill add-to-cart-detail d-inline-flex align-items-center justify-content-center"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $finalPrice }}"
                        data-original-price="{{ $product->price }}"
                        data-image="{{ $allImages[0] }}"
                        style="font-size: 15px; font-weight: 600; border-radius: 8px;"
                        title="Add to Cart">
                        <i class="bi bi-cart3 me-md-2"></i><span>Add To Cart</span>
                    </button>
                    <button type="button" class="btn btn-custom-buy btn-lg flex-fill buy-now-detail d-inline-flex align-items-center justify-content-center"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $finalPrice }}"
                        data-original-price="{{ $product->price }}"
                        data-image="{{ $allImages[0] }}"
                        style="font-size: 15px; font-weight: 600; border-radius: 8px;"
                        title="Buy Now">
                        <i class="bi bi-lightning-fill me-md-2"></i><span>Buy Now</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= DESCRIPTION & REVIEWS ================= -->
<section class="container pb-5">
    <ul class="nav nav-tabs" id="productDetailTabs">
        <li class="nav-item">
            <button class="nav-link active" data-tab="0">Description</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-tab="1">Specifications</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-tab="2">Reviews</button>
        </li>
    </ul>

    <!-- Tab 0: Description -->
    <div class="tab-content-panel active">
        <h5 class="fw-bold mb-3">Description</h5>
        @if($product->description)
            <div class="product-description-content" style="line-height: 1.8; color: #444;">
                {!! $product->description !!}
            </div>
        @else
            <p class="text-muted fst-italic">No description available for this product.</p>
        @endif
    </div>

    <!-- Tab 1: Specifications -->
    <div class="tab-content-panel">
        <h5 class="fw-bold mb-3">Specifications</h5>
        <table class="table table-striped table-sm text-muted" style="border-color: #a1a1a1 !important;">
            <tbody>
                <tr><td style="width:40%;"><strong>Category</strong></td><td>{{ optional($product->category)->name ?? '-' }}</td></tr>
                @if(optional($product->subCategory)->name)
                <tr><td><strong>Sub Category</strong></td><td>{{ $product->subCategory->name }}</td></tr>
                @endif
                <tr><td><strong>Brand</strong></td><td>{{ optional($product->brand)->name ?? '-' }}</td></tr>
                <tr><td><strong>Stock</strong></td><td>{{ $product->stock > 0 ? $product->stock . ' units available' : 'Out of Stock' }}</td></tr>
                @foreach($product->specifications ?? [] as $spec)
                    @if(!empty($spec['label']))
                        <tr><td><strong>{{ $spec['label'] }}</strong></td><td>{{ $spec['value'] ?? '-' }}</td></tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tab 2: Reviews -->
    <div class="tab-content-panel">
        <h5 class="fw-bold mb-3">Customer Reviews</h5>
        <div id="reviewsList">
            @forelse($product->reviews as $review)
                <div class="mb-3 review-item">
                    <strong>{{ $review->name }}</strong>
                    <div class="rating my-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-muted small">{{ $review->comment }}</p>
                    <hr>
                </div>
            @empty
                <div class="mb-3 review-item text-muted">
                    <p>No reviews yet. Be the first to leave a review.</p>
                </div>
            @endforelse
        </div>

        <h5 class="mt-4 fw-bold">Write a Review</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @auth
            <form id="reviewForm" method="POST" action="{{ route('product.review.store', $product) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Your Name</label>
                    <input type="text" class="form-control form-control-sm" id="reviewName" name="name" value="{{ auth()->user()->name }}" readonly required style="border-color: #a1a1a1 !important;">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold d-block">Rating</label>
                    <div id="starRating">
                        <i class="bi bi-star text-warning" data-value="1"></i>
                        <i class="bi bi-star text-warning" data-value="2"></i>
                        <i class="bi bi-star text-warning" data-value="3"></i>
                        <i class="bi bi-star text-warning" data-value="4"></i>
                        <i class="bi bi-star text-warning" data-value="5"></i>
                    </div>
                    <input type="hidden" id="ratingValue" name="rating" value="{{ old('rating', 0) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Your Review</label>
                    <textarea class="form-control form-control-sm" id="reviewText" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3">Submit Review</button>
            </form>
        @else
            <div class="alert alert-light border text-center py-4 my-3" style="border-radius: 8px;">
                <i class="bi bi-info-circle text-muted fs-3 d-block mb-2"></i>
                <p class="text-muted mb-3">You must be logged in to submit a review for this product.</p>
                <a href="{{ route('user.login') }}" class="btn btn-primary btn-sm px-4">Log In Here</a>
            </div>
        @endauth
    </div>
</section>

<!-- ================= RELATED PRODUCTS ================= -->
@if ($relatedProducts->count() > 0)
<section class="related-products-section py-5">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="related-section-bar"></div>
            <h4 class="fw-bold mb-0">Related Products</h4>
        </div>
        <div class="row g-4">
            @foreach($relatedProducts as $rp)
                @php
                    $rpHasDiscount = $rp->discount_type && $rp->discount_value > 0;
                    $rpFinalPrice = $rp->price;
                    if ($rpHasDiscount) {
                        if ($rp->discount_type === 'percent') {
                            $rpFinalPrice = $rp->price - ($rp->price * $rp->discount_value) / 100;
                        } elseif ($rp->discount_type === 'fixed') {
                            $rpFinalPrice = $rp->price - $rp->discount_value;
                        }
                    }
                    $rpImage = $rp->image;
                    if (!$rpImage && !empty($rp->variants)) {
                        foreach ($rp->variants as $v) {
                            if (isset($v['combo']) && !empty($v['image'])) {
                                $rpImage = $v['image'];
                                break;
                            }
                        }
                    }
                @endphp
                <div class="col-6 col-md-3">
                    <a href="{{ route('product.details', $rp->slug) }}" class="text-decoration-none">
                        <div class="related-card">
                            <div class="related-card-img-wrap">
                                @if ($rpImage)
                                    <img src="{{ asset('storage/' . $rpImage) }}" alt="{{ $rp->name }}" class="related-card-img">
                                @else
                                    <div class="related-card-no-img">
                                        <i class="bi bi-image text-muted" style="font-size:2rem;"></i>
                                    </div>
                                @endif
                                @if ($rpHasDiscount && $rp->discount_type === 'percent')
                                    <span class="related-card-badge">{{ round($rp->discount_value) }}% OFF</span>
                                @endif
                            </div>
                            <div class="related-card-body">
                                <h6 class="related-card-name">{{ $rp->name }}</h6>
                                <div class="related-card-pricing">
                                    <span class="related-card-price">৳{{ number_format($rpFinalPrice, 0) }}</span>
                                    @if ($rpHasDiscount)
                                        <span class="related-card-original">৳{{ number_format($rp->price, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ================= LIGHTBOX ================= -->
<div id="lightbox">
    <span id="closeLightbox">&times;</span>
    <button id="prevImage">&#10094;</button>
    <img id="lightboxImage">
    <button id="nextImage">&#10095;</button>
    <div class="lightbox-tools">
        <button id="zoomOut" title="Zoom Out">−</button>
        <button id="zoomReset" title="Reset">100%</button>
        <button id="zoomIn" title="Zoom In">+</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const mainImage = document.getElementById('mainImage');
    const thumbs = document.querySelectorAll('.thumb');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const closeLightbox = document.getElementById('closeLightbox');
    const prevBtn = document.getElementById('prevImage');
    const nextBtn = document.getElementById('nextImage');
    const minusBtn = document.getElementById('minus');
    const plusBtn = document.getElementById('plus');
    const qtyInput = document.getElementById('qty');
    const tabBtns = document.querySelectorAll('.nav-tabs .nav-link');
    const tabContents = document.querySelectorAll('.tab-content-panel');
    const productVariants = @json($product->variants ?? []);

    function getSelectedVariants() {
        const selections = {};
        document.querySelectorAll('.variant-group').forEach(group => {
            const label = group.dataset.label;
            const activeBtn = group.querySelector('.variant-btn.active');
            if (activeBtn) {
                selections[label] = activeBtn.dataset.value;
            }
        });
        return selections;
    }

    let currentIndex = 0;
    const images = [];

    thumbs.forEach((thumb, index) => {
        images.push(thumb.src);
        thumb.addEventListener('click', () => {
            currentIndex = index;
            updateMainImage();

            // Find if any variant has this image and auto-select its button
            const thumbUrl = thumb.src;
            const matchedVariant = productVariants.find(v => {
                if (!v.image) return false;
                return thumbUrl.endsWith(v.image);
            });

            if (matchedVariant) {
                if (matchedVariant.combo) {
                    for (const [label, val] of Object.entries(matchedVariant.combo)) {
                        const group = document.querySelector(`.variant-group[data-label="${label.toLowerCase().trim()}"]`);
                        if (group) {
                            const btn = group.querySelector(`.variant-btn[data-value="${val}"]`);
                            if (btn) {
                                group.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
                                btn.classList.add('active');
                            }
                        }
                    }
                } else if (matchedVariant.label && matchedVariant.value) {
                    const group = document.querySelector(`.variant-group[data-label="${matchedVariant.label.toLowerCase().trim()}"]`);
                    if (group) {
                        const btn = group.querySelector(`.variant-btn[data-value="${matchedVariant.value}"]`);
                        if (btn) {
                            group.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                        }
                    }
                }
                updateVariantDisplay(true);
            } else {
                // Clear all selected variant buttons
                document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
                updateVariantDisplay(true);
            }
        });
    });

    function updateMainImage() {
        mainImage.src = images[currentIndex];
        thumbs.forEach(t => t.classList.remove('active'));
        thumbs[currentIndex].classList.add('active');
    }

    mainImage.addEventListener('click', () => {
        lightboxImage.src = images[currentIndex];
        lightbox.classList.add('active');
    });

    closeLightbox.addEventListener('click', () => {
        lightbox.classList.remove('active');
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.remove('active');
        }
    });

    function showImage(direction) {
        currentIndex = (currentIndex + direction + images.length) % images.length;
        lightboxImage.src = images[currentIndex];
        currentZoom = 1;
        applyZoom();
        updateZoomLabel();
    }

    prevBtn.addEventListener('click', () => showImage(-1));
    nextBtn.addEventListener('click', () => showImage(1));

    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('active')) return;
        if (e.key === 'Escape') lightbox.classList.remove('active');
        if (e.key === 'ArrowLeft') showImage(-1);
        if (e.key === 'ArrowRight') showImage(1);
    });

    const zoomInBtn = document.getElementById('zoomIn');
    const zoomOutBtn = document.getElementById('zoomOut');
    const zoomResetBtn = document.getElementById('zoomReset');
    let currentZoom = 1;

    function applyZoom() {
        lightboxImage.style.transform = `scale(${currentZoom})`;
    }

    function updateZoomLabel() {
        zoomResetBtn.textContent = Math.round(currentZoom * 100) + '%';
    }

    zoomInBtn.addEventListener('click', () => {
        currentZoom = Math.min(currentZoom + 0.5, 5);
        applyZoom();
        updateZoomLabel();
    });

    zoomOutBtn.addEventListener('click', () => {
        currentZoom = Math.max(currentZoom - 0.5, 0.5);
        applyZoom();
        updateZoomLabel();
    });

    zoomResetBtn.addEventListener('click', () => {
        currentZoom = 1;
        applyZoom();
        updateZoomLabel();
    });

    lightbox.addEventListener('wheel', (e) => {
        e.preventDefault();
        if (e.deltaY < 0) {
            currentZoom = Math.min(currentZoom + 0.2, 5);
        } else {
            currentZoom = Math.max(currentZoom - 0.2, 0.5);
        }
        applyZoom();
        updateZoomLabel();
    });

    minusBtn.addEventListener('click', () => {
        let value = parseInt(qtyInput.value) || 1;
        if (value > 1) {
            qtyInput.value = value - 1;
        }
    });

    plusBtn.addEventListener('click', () => {
        let value = parseInt(qtyInput.value) || 1;
        qtyInput.value = value + 1;
    });

    const baseProductPrice = {{ $finalPrice }};
    const baseProductOriginalPrice = {{ $product->price }};
    const baseProductImage = "{{ $allImages[0] }}";
    const hasDiscount = {{ $hasDiscount ? 'true' : 'false' }};

    function updateVariantDisplay(keepCurrentMainImage = false) {
        const selections = getSelectedVariants();
        
        let activePrice = baseProductPrice;
        let originalPriceForVariant = null;
        let activeImage = baseProductImage;
        let priceOverridden = false;
        let imageOverridden = false;

        let isComboProduct = productVariants.some(v => v.combo);

        if (isComboProduct) {
            const selectionKeys = Object.keys(selections);
            if (selectionKeys.length > 0) {
                const exactComboMatch = productVariants.find(v => {
                    if (!v.combo) return false;
                    return selectionKeys.every(label => {
                        const val = selections[label];
                        for (const [cLabel, cVal] of Object.entries(v.combo)) {
                            if (cLabel.toLowerCase().trim() === label.toLowerCase().trim() && cVal.toLowerCase().trim() === val.toLowerCase().trim()) {
                                return true;
                            }
                        }
                        return false;
                    });
                });
                if (exactComboMatch) {
                    if (exactComboMatch.price !== null && exactComboMatch.price !== undefined && exactComboMatch.price !== '') {
                        let p = parseFloat(exactComboMatch.price);
                        let dPrice = null;
                        if (exactComboMatch.discount_type && exactComboMatch.discount > 0) {
                            const dVal = parseFloat(exactComboMatch.discount);
                            if (exactComboMatch.discount_type === 'percent') {
                                dPrice = p - (p * (dVal / 100));
                            } else if (exactComboMatch.discount_type === 'fixed') {
                                dPrice = p - dVal;
                            }
                        }
                        
                        if (dPrice !== null && dPrice >= 0) {
                            activePrice = dPrice;
                            originalPriceForVariant = p;
                        } else {
                            activePrice = p;
                        }
                        
                        priceOverridden = true;
                    }
                    if (exactComboMatch.image) {
                        activeImage = "{{ asset('storage') }}/" + exactComboMatch.image;
                        imageOverridden = true;
                    }
                }
            }
        } else {
            for (const [label, val] of Object.entries(selections)) {
                const match = productVariants.find(v => 
                    v.label && v.label.toLowerCase().trim() === label.toLowerCase().trim() && 
                    v.value && v.value.toLowerCase().trim() === val.toLowerCase().trim()
                );

                if (match) {
                    if (match.price !== null && match.price !== undefined && match.price !== '') {
                        let p = parseFloat(match.price);
                        let dPrice = null;
                        if (match.discount_type && match.discount > 0) {
                            const dVal = parseFloat(match.discount);
                            if (match.discount_type === 'percent') {
                                dPrice = p - (p * (dVal / 100));
                            } else if (match.discount_type === 'fixed') {
                                dPrice = p - dVal;
                            }
                        }
                        
                        if (dPrice !== null && dPrice >= 0) {
                            activePrice = dPrice;
                            originalPriceForVariant = p;
                        } else {
                            activePrice = p;
                        }
                        priceOverridden = true;
                    }
                    if (match.image) {
                        activeImage = "{{ asset('storage') }}/" + match.image;
                        imageOverridden = true;
                    }
                }
            }
        }

        const priceContainer = document.querySelector('.text-danger.fw-bold');
        if (priceContainer) {
            if (priceOverridden) {
                if (originalPriceForVariant !== null) {
                    priceContainer.innerHTML = `৳${activePrice.toFixed(2)} <del class="text-muted fs-5 ms-2">৳${originalPriceForVariant.toFixed(2)}</del>`;
                } else {
                    priceContainer.innerHTML = `৳${activePrice.toFixed(2)}`;
                }
            } else {
                if (hasDiscount) {
                    priceContainer.innerHTML = `৳${baseProductPrice.toFixed(2)} <del class="text-muted fs-5 ms-2">৳${baseProductOriginalPrice.toFixed(2)}</del>`;
                } else {
                    priceContainer.innerHTML = `৳${baseProductPrice.toFixed(2)}`;
                }
            }
        }

        if (imageOverridden && mainImage) {
            mainImage.src = activeImage;
            thumbs.forEach(t => t.classList.remove('active'));
        } else if (!keepCurrentMainImage && mainImage) {
            mainImage.src = baseProductImage;
            if (thumbs[0]) {
                thumbs.forEach(t => t.classList.remove('active'));
                thumbs[0].classList.add('active');
            }
        }

        const addToCartBtn = document.querySelector('.add-to-cart-detail');
        const buyNowBtn = document.querySelector('.buy-now-detail');
        const originalPriceAttr = priceOverridden ? (originalPriceForVariant !== null ? originalPriceForVariant : activePrice) : baseProductOriginalPrice;
        
        if (addToCartBtn) {
            addToCartBtn.dataset.price = activePrice;
            addToCartBtn.dataset.originalPrice = originalPriceAttr;
            addToCartBtn.dataset.image = imageOverridden ? activeImage : mainImage.src;
        }
        if (buyNowBtn) {
            buyNowBtn.dataset.price = activePrice;
            buyNowBtn.dataset.originalPrice = originalPriceAttr;
            buyNowBtn.dataset.image = imageOverridden ? activeImage : mainImage.src;
        }
    }

    const hasVariantImages = productVariants.some(v => v.image);

    // Variant buttons selection toggling
    document.querySelectorAll('.variant-group').forEach(group => {
        const buttons = group.querySelectorAll('.variant-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                // If product has variant images, attributes act as read-only indicators
                if (hasVariantImages) {
                    return;
                }
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateVariantDisplay();
            });
        });
    });

    // Run once on load
    if (thumbs.length > 0) {
        // Trigger click on first thumb to select its variants
        thumbs[0].click();
    } else {
        // Fallback: Select first button in each group
        document.querySelectorAll('.variant-group').forEach(group => {
            const buttons = group.querySelectorAll('.variant-btn');
            if (buttons.length > 0) {
                buttons[0].classList.add('active');
            }
        });
        updateVariantDisplay();
    }


    tabBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            if (tabContents[index]) {
                tabContents[index].classList.add('active');
            }
        });
    });

    const starRating = document.getElementById('starRating');
    const ratingValue = document.getElementById('ratingValue');
    const stars = starRating ? starRating.querySelectorAll('i') : [];
    const reviewForm = document.getElementById('reviewForm');
    const reviewsList = document.getElementById('reviewsList');

    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingValue.value = value;
                updateStars(value);
            });

            star.addEventListener('mouseenter', () => {
                const value = parseInt(star.getAttribute('data-value'));
                highlightStars(value);
            });

            star.addEventListener('mouseleave', () => {
                updateStars(parseInt(ratingValue.value) || 0);
            });
        });
    }

    function updateStars(value) {
        stars.forEach(s => {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill');
            } else {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
            }
        });
    }

    function highlightStars(value) {
        stars.forEach(s => {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) {
                s.classList.add('hovered');
            } else {
                s.classList.remove('hovered');
            }
        });
    }

    // Add To Cart Integration
    const detailAddToCartBtn = document.querySelector('.add-to-cart-detail');
    if (detailAddToCartBtn) {
        detailAddToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const originalPrice = this.dataset.originalPrice || price;
            const image = this.dataset.image;
            const qty = parseInt(qtyInput.value) || 1;
            const variants = getSelectedVariants();

            if (window.addToCartGlobal) {
                window.addToCartGlobal(id, name, price, image, qty, variants, originalPrice);
            }
        });
    }

    // Buy Now Integration
    const detailBuyNowBtn = document.querySelector('.buy-now-detail');
    if (detailBuyNowBtn) {
        detailBuyNowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const originalPrice = this.dataset.originalPrice || price;
            const image = this.dataset.image;
            const qty = parseInt(qtyInput.value) || 1;
            const variants = getSelectedVariants();

            if (window.checkoutSingleItemGlobal) {
                window.checkoutSingleItemGlobal(id, name, price, image, qty, variants, originalPrice);
            }
        });
    }
})();
</script>

<script>
// ── Daraz-style Magnifier Zoom ────────────────────────────────────────────
(function () {
    const wrap   = document.getElementById('mainImageWrap');
    const img    = document.getElementById('mainImage');
    const lens   = document.getElementById('zoomLens');
    const result = document.getElementById('zoomResult');
    if (!wrap || !img || !lens || !result) return;

    const ZOOM = 2.8; // magnification factor

    function updateZoom(e) {
        if (window.innerWidth < 992) return;

        const imgRect  = img.getBoundingClientRect();
        const wrapRect = wrap.getBoundingClientRect();

        const rw = result.offsetWidth  || 420;
        const rh = result.offsetHeight || 420;
        const lw = rw / ZOOM;
        const lh = rh / ZOOM;

        // Cursor position relative to the image element
        let x = e.clientX - imgRect.left;
        let y = e.clientY - imgRect.top;

        // Clamp so lens stays fully within the image
        let lensLeft = x - lw / 2;
        let lensTop  = y - lh / 2;
        lensLeft = Math.max(0, Math.min(imgRect.width  - lw, lensLeft));
        lensTop  = Math.max(0, Math.min(imgRect.height - lh, lensTop));

        // Place lens div relative to wrap (not image) since wrap has position:relative via main-image
        const offsetX = imgRect.left - wrapRect.left;
        const offsetY = imgRect.top  - wrapRect.top;
        lens.style.left   = (lensLeft + offsetX) + 'px';
        lens.style.top    = (lensTop  + offsetY) + 'px';
        lens.style.width  = lw + 'px';
        lens.style.height = lh + 'px';

        // Background in result panel: image zoomed ZOOM times, shifted so lens area fills it
        const bgW = imgRect.width  * ZOOM;
        const bgH = imgRect.height * ZOOM;
        const bgX = -lensLeft * ZOOM;
        const bgY = -lensTop  * ZOOM;

        result.style.backgroundImage    = `url('${img.src}')`;
        result.style.backgroundSize     = `${bgW}px ${bgH}px`;
        result.style.backgroundPosition = `${bgX}px ${bgY}px`;
    }

    wrap.addEventListener('mouseenter', () => {
        if (window.innerWidth < 992) return;
        lens.style.display   = 'block';
        result.style.display = 'block';
    });

    wrap.addEventListener('mousemove', updateZoom);

    wrap.addEventListener('mouseleave', () => {
        lens.style.display   = 'none';
        result.style.display = 'none';
    });
})();
</script>
@endpush

