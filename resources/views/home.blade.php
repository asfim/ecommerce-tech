@extends('layouts.app')

@section('content')
    <!-- Hero section -->
    <div class="hero-sec">
        <div class="wrap">
            <div id="heroCarousel" class="carousel slide hero-carousel shadow-sm" data-bs-ride="carousel" data-bs-interval="5000">
                <!-- Indicators -->
                <div class="carousel-indicators">
                    @forelse ($heroBanners as $index => $banner)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @empty
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    @endforelse
                </div>

                <!-- Slides -->
                <div class="carousel-inner">
                    @forelse ($heroBanners as $index => $banner)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $banner) }}" class="d-block w-100 hero-slider-img" alt="Slide {{ $index + 1 }}">
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&q=80" class="d-block w-100 hero-slider-img" alt="Fashion Slide">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <div class="caption-content">
                                    <span class="badge bg-primary mb-2 px-3 py-2 text-uppercase fw-bold">New Season</span>
                                    <h1 class="display-6 fw-bold text-white mb-2">Modern Fashion Collection</h1>
                                    <p class="text-white-50 mb-3">Explore the latest style statements and trends for this season.</p>
                                    <a href="#products-grid" class="btn btn-primary px-4 py-2 fw-semibold">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1200&q=80" class="d-block w-100 hero-slider-img" alt="Season Sale Slide">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <div class="caption-content">
                                    <span class="badge bg-warning text-dark mb-2 px-3 py-2 text-uppercase fw-bold">Big Discount</span>
                                    <h1 class="display-6 fw-bold text-white mb-2">End of Season Sale</h1>
                                    <p class="text-white-50 mb-3">Get up to 50% discount on all premium brand apparel and accessories.</p>
                                    <a href="#products-grid" class="btn btn-warning px-4 py-2 fw-semibold text-dark">Discover Deals</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80" class="d-block w-100 hero-slider-img" alt="Smart Tech & Shopping">
                            <div class="carousel-caption d-none d-md-block text-start">
                                <div class="caption-content">
                                    <span class="badge bg-success mb-2 px-3 py-2 text-uppercase fw-bold">Smart Living</span>
                                    <h1 class="display-6 fw-bold text-white mb-2">Premium Electronics</h1>
                                    <p class="text-white-50 mb-3">Experience innovation with our top-tier devices.</p>
                                    <a href="#products-grid" class="btn btn-success px-4 py-2 fw-semibold text-white">Explore Tech</a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <i class="bi bi-chevron-left"></i>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <i class="bi bi-chevron-right"></i>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <div class="wrap">
        <!-- Trending categories -->
        <div class="trending-box">
            <div style="min-width:220px;">
                <h6>Trending Categories</h6>
                <p>Categories catching eyes &amp; winning hearts across our marketplace</p>
            </div>
            <div style="overflow: hidden; flex-grow: 1;">
                <div id="trendingSlider" style="display: flex; gap: 15px; transition: transform .4s ease;">
                    @forelse($trendingCategories as $tc)
                        <a href="{{ route('category.products', $tc->id) }}" class="tcat-item">
                            @if ($tc->image)
                                <img src="{{ asset('storage/' . $tc->image) }}" alt="{{ $tc->name }}"
                                    style="width:90px; height:90px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
                            @else
                                <img src="https://placehold.co/74x74/eee/aaa?text={{ urlencode(Str::limit($tc->name, 8, '')) }}"
                                    alt="{{ $tc->name }}"
                                    style="width:94px; height:94px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
                            @endif
                            <div class="name" style="font-size:11.5px;">{{ $tc->name }}</div>
                        </a>
                    @empty
                        <div class="text-muted small px-2">No trending categories yet.</div>
                    @endforelse
                </div>
            </div>
            <div class="d-flex gap-1 align-items-center">
                <span class="arrow d-inline-flex" id="trendingPrev"
                    style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                        class="bi bi-chevron-left"></i></span>
                <span class="arrow d-inline-flex" id="trendingNext"
                    style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                        class="bi bi-chevron-right"></i></span>
            </div>
        </div>


        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="bestselling-panel h-100">
                    <div class="panel-title">Best Selling
                    </div>
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2 px-3 pb-3">
                        @forelse($bestSellingProducts as $bp)
                            <div class="col mini-prod">
                                <a href="{{ route('product.details', $bp->slug) }}" class="text-decoration-none">
                                    <div class="mini-img-wrap">
                                        @if ($bp->image)
                                            <img src="{{ asset('storage/' . $bp->image) }}">
                                        @else
                                            <img
                                                src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($bp->name, 8, '')) }}">
                                        @endif
                                    </div>
                                    <div class="t text-dark hover-blue">{{ Str::limit($bp->name, 35) }}</div>
                                </a>
                                <div class="code">Code: {{ $bp->id < 100 ? 'P' . $bp->id : $bp->id }}</div>
                                <div class="p">Tk {{ number_format($bp->price, 0) }}</div>
                                <div class="mt-2 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-add-to-cart add-to-cart-btn w-100 py-2 d-inline-flex align-items-center justify-content-center gap-1"
                                        style="font-size: 12px; font-weight: 600; border-radius: 6px;"
                                        data-id="{{ $bp->id }}" data-name="{{ $bp->name }}"
                                        data-price="{{ $bp->price }}"
                                        data-image="{{ $bp->image ? asset('storage/' . $bp->image) : 'https://placehold.co/150x150/eee/aaa?text=' . urlencode(Str::limit($bp->name, 8, '')) }}"
                                        title="Add to Cart">
                                        <i class="bi bi-cart3"></i><span> Add to Cart</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small px-3">No best selling products yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>


        </div>


        <!-- Promo 3 banners -->
        <div class="row g-3 mb-4">

            <div class="col-12 col-md-4">
                <div class="promo3">
                    @if (!empty($bestSellingBanners[0]))
                        <img src="{{ asset('storage/' . $bestSellingBanners[0]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&q=80">
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="promo3">
                    @if (!empty($bestSellingBanners[1]))
                        <img src="{{ asset('storage/' . $bestSellingBanners[1]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&q=80">
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="promo3">
                    @if (!empty($bestSellingBanners[2]))
                        <img src="{{ asset('storage/' . $bestSellingBanners[2]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&q=80">
                    @endif
                </div>
            </div>
        </div>
        <!-- Featured products strip -->
            <div class="featured-strip">
                <div class="row align-items-center g-2" style="background-color: #d8d8d8;">
                    <div class="col-12 col-md-3"><b>Featured Products</b></div>
                    <div class="col-10 col-md-8" style="overflow:hidden;">
                        <div id="featuredSlider" style="display:flex;transition:transform .4s ease;gap:0;">
                            @forelse($featuredProducts as $fp)
                                <div class="fs-item" style="display:flex;align-items:center;gap:10px;">
                                    @if ($fp->image)
                                        <a href="{{ route('product.details', $fp->slug) }}">
                                            <img src="{{ asset('storage/' . $fp->image) }}"
                                                style="width:90px;height:100px;object-fit:cover;border-radius:6px;">
                                        </a>
                                    @else
                                        <a href="{{ route('product.details', $fp->slug) }}">
                                            <img src="https://placehold.co/50x50/eee/aaa?text=No+Img"
                                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                        </a>
                                    @endif
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div>
                                            <a href="{{ route('product.details', $fp->slug) }}" class="text-dark hover-blue">
                                                <div class="t">{{ Str::limit($fp->name, 35) }}</div>
                                            </a>
                                            <div class="p">${{ number_format($fp->price, 2) }}</div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center add-to-cart-btn"
                                            style="width:24px;height:24px;min-width:24px;" data-id="{{ $fp->id }}"
                                            data-name="{{ $fp->name }}" data-price="{{ $fp->price }}"
                                            data-image="{{ $fp->image ? asset('storage/' . $fp->image) : 'https://placehold.co/50x50/eee/aaa?text=No+Img' }}"
                                            title="Add to Cart">
                                            <i class="bi bi-plus-lg" style="font-size:10px;"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small px-2">No featured products yet.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-2 col-md-1 text-end d-flex justify-content-end gap-1">
                        <span class="arrow d-inline-flex" id="featuredPrev"
                            style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                                class="bi bi-chevron-left"></i></span>
                        <span class="arrow d-inline-flex" id="featuredNext"
                            style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                                class="bi bi-chevron-right"></i></span>
                    </div>
                </div>
            </div>

        <!-- New Arrival -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="newarrival-banner">
                    @if (!empty($newArrivalsBanner[0]))
                        <img src="{{ asset('storage/' . $newArrivalsBanner[0]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=300&q=80">
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="newarrival-list-panel p-3 h-100 d-flex flex-column justify-content-between" style="overflow: hidden;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <b>New Arrival</b>
                        <div class="d-flex gap-2 align-items-center">
                            <small class="text-muted">Products ({{ $newArrivalProducts->count() }})</small>
                            <span class="arrow d-inline-flex" id="newArrivalPrev"><i
                                    class="bi bi-chevron-left"></i></span>
                            <span class="arrow d-inline-flex" id="newArrivalNext"><i
                                    class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                    <div style="overflow: hidden; width: 100%;">
                        <div id="newArrivalSlider" style="display: flex; transition: transform .4s ease;">
                            @forelse($newArrivalProducts->chunk(4) as $chunk)
                                <div class="newarrival-slide" style="min-width: 100%; flex: 0 0 100%;">
                                    <div class="row g-2">
                                        @foreach($chunk as $np)
                                            <div class="col-12 col-sm-6">
                                                <div class="newarrival-item">
                                                    @if ($np->image)
                                                        <a href="{{ route('product.details', $np->slug) }}">
                                                            <img src="{{ asset('storage/' . $np->image) }}">
                                                        </a>
                                                    @else
                                                        <a href="{{ route('product.details', $np->slug) }}">
                                                            <img src="https://placehold.co/100x100/eee/aaa?text=No+Img">
                                                        </a>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <a href="{{ route('product.details', $np->slug) }}" class="text-dark hover-blue">
                                                            <div class="t">{{ Str::limit($np->name, 45) }}</div>
                                                        </a>
                                                        <div class="bid"><b>${{ number_format($np->price, 2) }}</b></div>
                                                        <div class="d-flex gap-1 mt-1 justify-content-center align-items-center new-arrival-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-custom-cart add-to-cart-btn px-2 py-0 d-inline-flex align-items-center justify-content-center"
                                                                style="height: 24px; font-size:11px; border-radius:10px;"
                                                                data-id="{{ $np->id }}" data-name="{{ $np->name }}"
                                                                data-price="{{ $np->price }}"
                                                                data-image="{{ $np->image ? asset('storage/' . $np->image) : 'https://placehold.co/100x100/eee/aaa?text=' . urlencode(Str::limit($np->name, 8, '')) }}"
                                                                title="Add to Cart">
                                                                <i class="bi bi-cart-plus"></i><span> Add</span>
                                                            </button>
                                                            <button class="btn btn-sm btn-primary btn-custom-buy py-0 px-2 d-inline-flex align-items-center justify-content-center btn-bid"
                                                                style="height: 24px; font-size:11px; border-radius:10px;"
                                                                data-id="{{ $np->id }}"
                                                                data-name="{{ $np->name }}"
                                                                data-price="{{ $np->price }}"
                                                                data-image="{{ $np->image ? asset('storage/' . $np->image) : 'https://placehold.co/100x100/eee/aaa?text=' . urlencode(Str::limit($np->name, 8, '')) }}"
                                                                title="Buy Now"><i class="bi bi-lightning-fill"></i><span> Buy Now</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small px-3 w-100">No new arrival products yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <!-- Discounted Products -->
        <div class="preorder-panel my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                <h5 class="fw-bold mb-0">Discounted Products</h5>
                <div class="d-flex gap-1 align-items-center discounted-controls">
                    <span class="arrow d-inline-flex" id="discountedPrev" title="Previous"><i class="bi bi-chevron-left"></i></span>
                    <span class="arrow d-inline-flex" id="discountedNext" title="Next"><i class="bi bi-chevron-right"></i></span>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-12 col-lg-3">
                    <div class="preorder-hero">
                        <span class="badge-limit">Don't Miss Out</span>
                        <h5 class="fw-bold mt-3">Limited Discounted<br>Products Available</h5>
                        @if (!empty($discountedProductsBanner[0]))
                            <img src="{{ asset('storage/' . $discountedProductsBanner[0]) }}"
                                class="img-fluid rounded mt-2">
                        @else
                            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&q=80"
                                class="img-fluid rounded mt-2">
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="discounted-slider-wrapper">
                        <div id="discountedSlider" class="discounted-slider">
                            @forelse($discountedProducts as $dp)
                                @php
                                    $hasDiscount = $dp->discount_type && $dp->discount_value > 0;
                                    $discountedPrice = $dp->price;
                                    if ($hasDiscount) {
                                        if ($dp->discount_type === 'percent') {
                                            $discountedPrice = $dp->price - ($dp->price * $dp->discount_value) / 100;
                                        } elseif ($dp->discount_type === 'fixed') {
                                            $discountedPrice = $dp->price - $dp->discount_value;
                                        }
                                    }
                                @endphp
                                <div class="mini-prod discounted-product-card">
                                    <a href="{{ route('product.details', $dp->slug) }}" class="text-decoration-none">
                                        <div class="mini-img-wrap">
                                            @if ($dp->image)
                                                <img src="{{ asset('storage/' . $dp->image) }}" alt="{{ $dp->name }}" class="mini-product-img">
                                            @else
                                                <img
                                                    src="https://placehold.co/180x180/eee/aaa?text={{ urlencode(Str::limit($dp->name, 8, '')) }}"
                                                    alt="{{ $dp->name }}"
                                                    class="mini-product-img">
                                            @endif
                                        </div>
                                        <div class="t text-dark hover-blue">{{ Str::limit($dp->name, 35) }}</div>
                                    </a>
                                    <div class="code">Code: {{ $dp->id < 100 ? 'P' . $dp->id : $dp->id }}</div>
                                    <div class="p">
                                        @if ($hasDiscount)
                                            Tk {{ number_format($discountedPrice, 0) }}
                                            <span class="old text-decoration-line-through text-muted"
                                                style="font-size:10px;">Tk {{ number_format($dp->price, 0) }}</span>
                                        @else
                                            Tk {{ number_format($dp->price, 0) }}
                                        @endif
                                    </div>
                                    <div class="mt-2 d-flex gap-2 justify-content-center align-items-center product-card-actions">
                                        <button type="button" class="btn btn-add-to-cart add-to-cart-btn w-50 py-2 d-inline-flex align-items-center justify-content-center gap-1"
                                            style="font-size: 11px; font-weight: 600; border-radius: 6px;"
                                            data-id="{{ $dp->id }}" data-name="{{ $dp->name }}"
                                            data-price="{{ $discountedPrice }}"
                                            data-image="{{ $dp->image ? asset('storage/' . $dp->image) : 'https://placehold.co/150x150/eee/aaa?text=' . urlencode(Str::limit($dp->name, 8, '')) }}"
                                            title="Add to Cart">
                                            <i class="bi bi-cart3"></i><span> Add</span>
                                        </button>
                                        <button type="button" class="btn btn-buy-now btn-bid w-50 py-2 d-inline-flex align-items-center justify-content-center gap-1"
                                            style="font-size: 11px; font-weight: 600; border-radius: 6px;"
                                            data-id="{{ $dp->id }}" data-name="{{ $dp->name }}"
                                            data-price="{{ $discountedPrice }}"
                                            data-image="{{ $dp->image ? asset('storage/' . $dp->image) : 'https://placehold.co/150x150/eee/aaa?text=' . urlencode(Str::limit($dp->name, 8, '')) }}"
                                            title="Buy Now">
                                            <i class="bi bi-lightning-fill"></i><span> Buy Now</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small px-3">No discounted products yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(request()->query('search'))
            <div class="mb-4 p-3 bg-white rounded border border-light shadow-sm d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">Search Results for "{{ request()->query('search') }}"</h5>
                    <span class="text-muted small">Found {{ $products->count() }} product(s)</span>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle me-1"></i> Clear Search</a>
            </div>
        @endif

        <!-- Product grid -->
        <div id="products-grid" class="row g-3 mb-3">
            <div><h5 class="fw-bold mb-0">All Products</h5></div>
            @forelse($products as $product)
                @include('frontend.partials.product_card', ['product' => $product])
            @empty
                <div class="text-muted small px-3">No products available.</div>
            @endforelse
        </div>

        @if($hasMore)
            <div class="text-center mb-4">
                <button id="load-more-btn" class="btn btn-outline-dark px-5" data-page="2">
                    <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                    Load more
                </button>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            /* Hero Slider Styling */
            .hero-carousel {
                position: relative;
                width: 100%;
                overflow: hidden;
                border-radius: 8px;
                background-color: #e9ecef;
            }
            .hero-slider-img {
                width: 100%;
                height: auto;
                display: block;
            }
            .hero-carousel .carousel-caption {
                left: 8%;
                bottom: 12%;
                z-index: 10;
                text-align: left;
                padding: 0;
            }
            .hero-carousel .caption-content {
                background: rgba(0, 0, 0, 0.45);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 24px;
                border-radius: 12px;
                max-width: 480px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            }
            .hero-carousel .carousel-indicators [data-bs-target] {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                margin: 0 5px;
                background-color: #fff;
                opacity: 0.5;
                transition: all 0.2s;
                border: none;
            }
            .hero-carousel .carousel-indicators .active {
                opacity: 1;
                width: 20px;
                border-radius: 4px;
                background-color: #1a73e8;
            }
            .hero-carousel .carousel-control-prev,
            .hero-carousel .carousel-control-next {
                position: absolute;
                top: 50%;
                bottom: auto;
                transform: translateY(-50%);
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: #fff;
                font-size: 20px;
                opacity: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                z-index: 15;
            }
            .hero-carousel .carousel-control-prev {
                left: 20px;
            }
            .hero-carousel .carousel-control-next {
                right: 20px;
            }
            .hero-carousel:hover .carousel-control-prev,
            .hero-carousel:hover .carousel-control-next {
                opacity: 1;
            }
            .hero-carousel .carousel-control-prev:hover,
            .hero-carousel .carousel-control-next:hover {
                background: rgba(255, 255, 255, 0.9);
                color: #111;
                transform: translateY(-50%) scale(1.05);
            }
            @media (max-width: 991px) {
                .hero-carousel .caption-content {
                    padding: 16px;
                    max-width: 380px;
                }
                .hero-carousel .caption-content h1 {
                    font-size: 1.5rem !important;
                }
                .hero-carousel .caption-content p {
                    font-size: 0.85rem !important;
                    margin-bottom: 10px !important;
                }
            }
            @media (max-width: 575px) {
                .hero-carousel .carousel-caption {
                    display: none !important;
                }
            }

            .hover-blue {
                transition: color 0.2s ease;
            }
            .hover-blue:hover {
                color: #1a73e8 !important;
            }






            /* Trending Categories hover */
            .tcat-item {
                transition: all 0.3s ease;
                cursor: pointer;
                padding: 8px;
                border-radius: 12px;
            }

            .tcat-item:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                background: #fff;
            }

            .tcat-item:hover .name {
                color: #0066b9;
                font-weight: 700;
            }

            /* Featured Products hover */
            /* Featured Products hover & responsiveness */
            .fs-item {
                padding: 10px;
                border-radius: 10px;
                transition: all 0.3s ease;
                cursor: pointer;
                min-width: 100%;
                flex: 0 0 100%;
            }

            @media (min-width: 576px) {
                .fs-item {
                    min-width: 50%;
                    flex: 0 0 50%;
                }
            }

            @media (min-width: 992px) {
                .fs-item {
                    min-width: 33.333%;
                    flex: 0 0 33.333%;
                }
            }

            .fs-item:hover {
                background: #fff;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
                transform: translateY(-3px);
            }

            .fs-item:hover .t {
                color: #0066b9;
            }

            .fs-item .t {
                transition: color 0.2s ease;
            }

            /* Responsive overrides for sliders and custom rows */
            @media (max-width: 768px) {
                .trending-box {
                    flex-direction: column;
                    text-align: center;
                    align-items: stretch;
                }

                .trending-box>div:first-child {
                    margin-bottom: 10px;
                }
            }

            /* Trending categories items responsiveness */
            .tcat-item {
                min-width: calc(33.333% - 10px);
                flex: 0 0 calc(33.333% - 10px);
                text-align: center;
            }

            @media (min-width: 576px) {
                .tcat-item {
                    min-width: calc(25% - 11.25px);
                    flex: 0 0 calc(25% - 11.25px);
                }
            }

            @media (min-width: 992px) {
                .tcat-item {
                    min-width: calc(16.666% - 12.5px);
                    flex: 0 0 calc(16.666% - 12.5px);
                }
            }

            /* Discounted products items responsiveness inside slider */
            #discountedSlider .mini-prod {
                min-width: calc(50% - 7.5px);
                flex: 0 0 calc(50% - 7.5px);
            }

            @media (min-width: 576px) {
                #discountedSlider .mini-prod {
                    min-width: calc(33.333% - 10px);
                    flex: 0 0 calc(33.333% - 10px);
                }
            }

            @media (min-width: 992px) {
                #discountedSlider .mini-prod {
                    min-width: calc(25% - 11.25px);
                    flex: 0 0 calc(25% - 11.25px);
                }
            }



            /* Custom Toast Notifications */
            .custom-cart-toast {
                position: fixed;
                bottom: 24px;
                right: -350px;
                background: #fff;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                border-radius: 10px;
                padding: 12px 18px;
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 9999;
                width: 320px;
                border-left: 4px solid #1a73e8;
                transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
            }

            .custom-cart-toast.show {
                right: 24px;
            }

            .custom-cart-toast img {
                width: 48px;
                height: 48px;
                object-fit: contain;
                border-radius: 6px;
                background: #f8f9fa;
                border: 1px solid #eee;
            }

            .custom-cart-toast .toast-content h6 {
                margin: 0;
                font-size: 13px;
                font-weight: 700;
                color: #1a73e8;
            }

            .custom-cart-toast .toast-content p {
                margin: 2px 0 0 0;
                font-size: 11.5px;
                color: #555;
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: 210px;
            }

            /* Cart Badge Bounce Animation */
            .badge-bounce {
                animation: badge-bounce 0.4s ease;
            }

            @keyframes badge-bounce {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.4);
                }
            }


        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('featuredSlider');
                const prevBtn = document.getElementById('featuredPrev');
                const nextBtn = document.getElementById('featuredNext');
                if (!slider || !prevBtn || !nextBtn) return;

                const items = slider.querySelectorAll('.fs-item');
                const totalItems = items.length;
                let visibleItems = 3;
                let currentIndex = 0;
                let maxIndex = Math.max(0, totalItems - visibleItems);

                function getVisibleItems() {
                    if (window.innerWidth < 576) return 1;
                    if (window.innerWidth < 992) return 2;
                    return 3;
                }

                function updateSlider() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex > maxIndex) currentIndex = maxIndex;
                    const offset = currentIndex * (100 / visibleItems);
                    slider.style.transform = `translateX(-${offset}%)`;
                }

                prevBtn.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                });

                nextBtn.addEventListener('click', function() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        updateSlider();
                    }
                });

                // Call initially and on resize
                updateSlider();
                window.addEventListener('resize', updateSlider);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('trendingSlider');
                const prevBtn = document.getElementById('trendingPrev');
                const nextBtn = document.getElementById('trendingNext');
                if (!slider || !prevBtn || !nextBtn) return;

                const items = slider.querySelectorAll('.tcat-item');
                const totalItems = items.length;
                let visibleItems = 6;
                let currentIndex = 0;
                let maxIndex = Math.max(0, totalItems - visibleItems);

                function getVisibleItems() {
                    if (window.innerWidth < 576) return 3;
                    if (window.innerWidth < 992) return 4;
                    return 6;
                }

                function scrollSlider() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex > maxIndex) currentIndex = maxIndex;
                    if (totalItems <= visibleItems) return;
                    const itemWidth = items[0].getBoundingClientRect().width;
                    const gap = 15;
                    slider.parentElement.scrollTo({
                        left: currentIndex * (itemWidth + gap),
                        behavior: 'smooth'
                    });
                }

                prevBtn.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        scrollSlider();
                    }
                });

                nextBtn.addEventListener('click', function() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        scrollSlider();
                    }
                });

                window.addEventListener('resize', function() {
                    scrollSlider();
                });

                // Make sure parent container has style: overflow: hidden; scroll-behavior: smooth;
                slider.parentElement.style.scrollBehavior = 'smooth';
            });

            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('discountedSlider');
                const prevBtn = document.getElementById('discountedPrev');
                const nextBtn = document.getElementById('discountedNext');
                if (!slider || !prevBtn || !nextBtn) return;

                const items = slider.querySelectorAll('.mini-prod');
                const totalItems = items.length;
                let visibleItems = 4;
                let currentIndex = 0;
                let maxIndex = Math.max(0, totalItems - visibleItems);

                function getVisibleItems() {
                    if (window.innerWidth < 576) return 2;
                    if (window.innerWidth < 992) return 3;
                    return 4;
                }

                function scrollSlider() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex > maxIndex) currentIndex = maxIndex;
                    if (totalItems <= visibleItems) return;
                    const itemWidth = items[0].getBoundingClientRect().width;
                    const gap = 15;
                    slider.parentElement.scrollTo({
                        left: currentIndex * (itemWidth + gap),
                        behavior: 'smooth'
                    });
                }

                prevBtn.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        scrollSlider();
                    }
                });

                nextBtn.addEventListener('click', function() {
                    visibleItems = getVisibleItems();
                    maxIndex = Math.max(0, totalItems - visibleItems);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        scrollSlider();
                    }
                });

                window.addEventListener('resize', function() {
                    scrollSlider();
                });

                slider.parentElement.style.scrollBehavior = 'smooth';
            });

            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('newArrivalSlider');
                const prevBtn = document.getElementById('newArrivalPrev');
                const nextBtn = document.getElementById('newArrivalNext');
                if (!slider || !prevBtn || !nextBtn) return;

                const slides = slider.querySelectorAll('.newarrival-slide');
                const totalSlides = slides.length;
                let currentSlideIndex = 0;

                function scrollSlide() {
                    slider.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
                }

                prevBtn.addEventListener('click', function() {
                    if (currentSlideIndex > 0) {
                        currentSlideIndex--;
                        scrollSlide();
                    }
                });

                nextBtn.addEventListener('click', function() {
                    if (currentSlideIndex < totalSlides - 1) {
                        currentSlideIndex++;
                        scrollSlide();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('load-more-btn');
                const productsGrid = document.getElementById('products-grid');

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const page = loadMoreBtn.getAttribute('data-page');
                        const spinner = loadMoreBtn.querySelector('.spinner-border');

                        spinner.classList.remove('d-none');
                        loadMoreBtn.disabled = true;

                        const urlParams = new URLSearchParams(window.location.search);
                        const search = urlParams.get('search') || '';

                        fetch(`/?page=${page}&search=${encodeURIComponent(search)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            productsGrid.insertAdjacentHTML('beforeend', data.html);
                            loadMoreBtn.setAttribute('data-page', parseInt(page) + 1);

                            spinner.classList.add('d-none');
                            loadMoreBtn.disabled = false;

                            if (!data.has_more) {
                                loadMoreBtn.parentElement.remove();
                            }
                        })
                        .catch(err => {
                            console.error('Error loading products:', err);
                            spinner.classList.add('d-none');
                            loadMoreBtn.disabled = false;
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
