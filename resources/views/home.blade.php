@extends('layouts.app')

@section('content')
    <!-- Hero section -->
    <div class="hero-sec">
        <div class="wrap">

            {{-- Desktop: Category Sidebar + Slider --}}
            <div class="row g-4 d-none d-lg-flex">

                <!-- LEFT: Category Sidebar -->
                <div class="col-lg-3" style="z-index: 10;">
                    <div class="hero-cat-sidebar" style="overflow: visible !important; position: relative;">
                    <div class="hero-cat-sidebar-inner" style="height: 100%; overflow-y: auto;">
                    <ul class="hero-cat-list">
                        @foreach($categories as $cat)
                        <li class="hero-cat-item" style="position: static !important;">
                            <a href="{{ route('category.products', $cat->id) }}">
                                @if($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="hero-cat-icon">
                                @else
                                    <i class="bi bi-grid hero-cat-icon-bi"></i>
                                @endif
                                <span>{{ $cat->name }}</span>
                                @if($cat->subCategories->isNotEmpty())
                                    <i class="bi bi-chevron-right ms-auto cat-arrow"></i>
                                @endif
                            </a>
                            @if($cat->subCategories->isNotEmpty())
                                <div class="hero-cat-submenu hero-cat-sidebar-inner position-absolute bg-white" style="left: 100%; top: 0; min-width: 250px; height: 100%; overflow-y: auto; z-index: 9999; border-radius: 0 12px 12px 0; box-shadow: 10px 10px 30px rgba(0,0,0,0.08); padding: 16px 12px; border-left: 3px solid #E0471B; opacity: 0; visibility: hidden; transform: translateX(10px); transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);">
                                    <div class="text-uppercase fw-bold text-muted mb-2 px-3" style="font-size: 11px; letter-spacing: 1px;">{{ $cat->name }}</div>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($cat->subCategories as $subCat)
                                            <li class="mb-1">
                                                <a href="{{ route('category.products', [$cat->id, 'subcategory' => $subCat->id]) }}" class="d-flex align-items-center px-3 py-2 text-decoration-none text-dark rounded smart-submenu-link" style="font-size: 14px; font-weight: 500;">
                                                    {{ $subCat->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                        @endforeach
                        <li class="view-all-cats" style="position: static !important;">
                            <a href="{{ route('home') }}#products-grid">
                                <i class="bi bi-grid-3x3-gap hero-cat-icon-bi"></i>
                                <span>View All Categories</span>
                                <i class="bi bi-chevron-right ms-auto cat-arrow"></i>
                            </a>
                        </li>
                    </ul>
                    </div>
                </div>
                </div>

                <!-- RIGHT: Carousel -->
                <div class="col-lg-9">
                <div id="heroCarousel" class="carousel slide hero-carousel shadow-sm h-100" data-bs-ride="carousel" data-bs-interval="3000">
                <!-- Indicators -->
                <div class="carousel-indicators hero-indicators">
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
                        {{-- Fallback slide 1 --}}
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&q=80" alt="Fashion" class="d-block w-100 hero-slider-img">
                            <div class="hero-overlay-caption">
                                <div class="hero-slide-text">
                                    <p class="hero-slide-label" style="color: #ffc107; font-weight: 700; letter-spacing: .12em;">NEW ARRIVAL</p>
                                    <h2 class="hero-slide-heading" style="color: #fff; font-weight: 800; font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Modern Fashion<br>Collection 2026</h2>
                                    <p class="hero-slide-desc" style="color: #f1f1f1; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Latest trends with premium quality.<br>Express your unique style today!</p>
                                    <a href="#products-grid" class="btn hero-slide-btn">Shop Now &nbsp;<i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        {{-- Fallback slide 2 --}}
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1200&q=80" alt="Sale" class="d-block w-100 hero-slider-img">
                            <div class="hero-overlay-caption">
                                <div class="hero-slide-text">
                                    <p class="hero-slide-label" style="color: #ffc107; font-weight: 700; letter-spacing: .12em;">BIG DISCOUNT</p>
                                    <h2 class="hero-slide-heading" style="color: #fff; font-weight: 800; font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">End of Season<br>Sale — Up to 50%</h2>
                                    <p class="hero-slide-desc" style="color: #f1f1f1; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Unbeatable prices on premium brands.<br>Don't miss these exclusive deals!</p>
                                    <a href="#products-grid" class="btn hero-slide-btn">Discover Deals &nbsp;<i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        {{-- Fallback slide 3 --}}
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&q=80" alt="Electronics" class="d-block w-100 hero-slider-img">
                            <div class="hero-overlay-caption">
                                <div class="hero-slide-text">
                                    <p class="hero-slide-label" style="color: #ffc107; font-weight: 700; letter-spacing: .12em;">SMART LIVING</p>
                                    <h2 class="hero-slide-heading" style="color: #fff; font-weight: 800; font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Premium<br>Electronics 2026</h2>
                                    <p class="hero-slide-desc" style="color: #f1f1f1; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">Experience innovation with our<br>top-tier devices and gadgets.</p>
                                    <a href="#products-grid" class="btn hero-slide-btn">Explore Tech &nbsp;<i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev hero-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <i class="bi bi-chevron-left"></i>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next hero-ctrl" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <i class="bi bi-chevron-right"></i>
                    <span class="visually-hidden">Next</span>
                </button>
                </div>
                </div>
            </div>

            {{-- Mobile: Slider only (no sidebar) --}}
            <div class="d-block d-lg-none">
                <div id="heroCarouselMobile" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @forelse ($heroBanners as $index => $banner)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $banner) }}" class="d-block w-100 hero-slider-img" alt="Slide {{ $index + 1 }}">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80" alt="Fashion" class="d-block w-100 hero-slider-img">
                                <div class="hero-overlay-caption">
                                    <div class="hero-slide-text p-4">
                                        <p class="hero-slide-label" style="color: #ffc107; font-weight: 700; letter-spacing: .12em;">NEW ARRIVAL</p>
                                        <h2 class="hero-slide-heading" style="color: #fff; font-weight: 800; font-size: 2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Modern Fashion<br>Collection 2026</h2>
                                        <a href="#products-grid" class="btn hero-slide-btn">Shop Now &nbsp;<i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button class="carousel-control-prev hero-ctrl" type="button" data-bs-target="#heroCarouselMobile" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="carousel-control-next hero-ctrl" type="button" data-bs-target="#heroCarouselMobile" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="wrap">


        @push('styles')
        <style>
            .latest-products-slider {
                display: flex;
                overflow-x: auto;
                gap: 1.25rem;
                padding: 1rem 1rem 1.5rem 1rem;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
            }
            .latest-products-slider::-webkit-scrollbar {
                display: none;
            }
            .latest-products-slider .slider-card {
                flex: 0 0 auto;
                width: 220px;
                scroll-snap-align: start;
                background: #ffeee9;
                border: 1px solid #ffd6ca;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.06);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                display: flex;
                flex-direction: column;
                position: relative;
                overflow: hidden;
            }
            .latest-products-slider .slider-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            }
            .latest-products-slider .slider-img-wrap {
                width: 100%;
                height: 210px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8f9fa;
                border-radius: 10px 10px 0 0;
            }
            .latest-products-slider .slider-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: top center;
                transition: transform 0.3s ease;
            }
            .latest-products-slider .slider-card:hover .slider-img-wrap img {
                transform: scale(1.05);
            }
            .latest-products-slider .slider-card-body {
                padding: 1rem 1rem 0 1rem;
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }
            .latest-products-slider .product-title {
                font-size: 0.95rem;
                font-weight: 600;
                color: #2b2b2b;
                margin-bottom: 0.3rem;
                line-height: 1.3;
                height: 2.8rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .latest-products-slider .product-code {
                font-size: 0.75rem;
                color: #777;
                margin-bottom: 0.5rem;
            }
            .latest-products-slider .product-price {
                font-size: 1.1rem;
                font-weight: 700;
                color: #E0471B;
                margin-bottom: 1rem;
                margin-top: auto;
            }
            .latest-products-slider .btn-add-to-cart {
                background: #2b2b2b;
                color: #fff;
                border: none;
                border-radius: 8px;
                padding: 0.6rem;
                font-size: 0.85rem;
                font-weight: 600;
                transition: background 0.2s ease;
            }
            .latest-products-slider .btn-add-to-cart:hover {
                background: #E0471B;
                color: #fff;
            }
            .new-badge {
                position: absolute;
                top: 10px;
                left: 10px;
                background: #E0471B;
                color: #fff;
                font-size: 0.7rem;
                font-weight: 700;
                padding: 3px 8px;
                border-radius: 4px;
                z-index: 2;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .latest-slider-wrapper {
                position: relative;
            }
            .latest-slider-btn {
                position: absolute;
                top: 50%;

                z-index: 10;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: #fff;
                border: 1px solid #e0e0e0;
                box-shadow: 0 4px 12px rgba(0,0,0,0.12);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: #2b2b2b;
                font-size: 1rem;
            }
            .latest-slider-btn.prev-btn {
                left: -18px;
            }
            .latest-slider-btn.next-btn {
                right: -18px;
            }
            @media (max-width: 576px) {
                .latest-slider-btn.prev-btn { left: 4px; }
                .latest-slider-btn.next-btn { right: 4px; }
            }
        </style>
        @endpush

        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="bestselling-panel h-100" style="background: transparent; border: none; box-shadow: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3 px-2 mt-4">
                        <h4 class="mb-0 fw-bold" style="color: #2b2b2b; position: relative; padding-left: 15px;">
                            <span style="position: absolute; left: 0; top: 10%; height: 80%; width: 4px; background: #E0471B; border-radius: 2px;"></span>
                            Latest Products
                        </h4>
                    </div>
                    <div class="latest-slider-wrapper">
                        <button class="latest-slider-btn prev-btn" id="latestPrev" type="button" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="latest-slider-btn next-btn" id="latestNext" type="button" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <div class="latest-products-slider" id="latestSlider">
                            @forelse($bestSellingProducts as $bp)
                                @php
                                    $bpHasDiscount = $bp->has_active_discount;
                                    $bpDiscountedPrice = $bp->price;
                                    $bpDisplayDiscountType = $bp->discount_type;
                                    $bpDisplayDiscountValue = $bp->discount_value;
                                    
                                    if ($bpHasDiscount) {
                                        if ($bp->discount_type === 'percent') {
                                            $bpDiscountedPrice = $bp->price - ($bp->price * $bp->discount_value) / 100;
                                        } elseif ($bp->discount_type === 'fixed') {
                                            $bpDiscountedPrice = $bp->price - $bp->discount_value;
                                        }
                                    }

                                    $displayImage = $bp->image;
                                    $minPrice = $bp->price;
                                    $maxPrice = $bp->price;
                                    $originalMinPrice = $bp->price;
                                    $originalMaxPrice = $bp->price;
                                    $hasMultiplePrices = false;
                                    $hasMultipleOriginalPrices = false;
                                    $hasVariantDiscount = false;
                                    
                                    if (!empty($bp->variants) && is_array($bp->variants)) {
                                        $prices = [];
                                        $originalPrices = [];
                                        $firstVariantImage = null;
                                        $now = now();
                                        
                                        foreach ($bp->variants as $v) {
                                            if (isset($v['combo'])) {
                                                if (isset($v['price']) && $v['price'] > 0) {
                                                    $originalP = (float) $v['price'];
                                                    $p = $originalP;
                                                    
                                                    if (!empty($v['discount_type']) && isset($v['discount']) && $v['discount'] > 0) {
                                                        $isActive = true;
                                                        $startDate = !empty($v['discount_start']) ? \Carbon\Carbon::parse($v['discount_start']) : null;
                                                        $endDate = !empty($v['discount_end']) ? \Carbon\Carbon::parse($v['discount_end']) : null;
                                                        if ($startDate && $startDate->gt($now)) $isActive = false;
                                                        if ($endDate && $endDate->lt($now)) $isActive = false;
                                                        
                                                        if ($isActive) {
                                                            $hasVariantDiscount = true;
                                                            if ($v['discount_type'] === 'percent') {
                                                                $p = $p - ($p * $v['discount'] / 100);
                                                            } else {
                                                                $p = $p - $v['discount'];
                                                            }
                                                            
                                                            if (!$bpHasDiscount) {
                                                                if ($v['discount_type'] === 'percent') {
                                                                    if ($bpDisplayDiscountType !== 'percent' || $v['discount'] > $bpDisplayDiscountValue) {
                                                                        $bpDisplayDiscountType = 'percent';
                                                                        $bpDisplayDiscountValue = $v['discount'];
                                                                    }
                                                                } else if ($bpDisplayDiscountType !== 'percent') { 
                                                                    if ($v['discount'] > $bpDisplayDiscountValue) {
                                                                        $bpDisplayDiscountType = 'fixed';
                                                                        $bpDisplayDiscountValue = $v['discount'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $prices[] = $p;
                                                    $originalPrices[] = $originalP;
                                                }
                                                if (!$firstVariantImage && isset($v['image']) && !empty($v['image'])) {
                                                    $firstVariantImage = $v['image'];
                                                }
                                            }
                                        }

                                        if ($hasVariantDiscount && !$bpHasDiscount) {
                                            $bpHasDiscount = true;
                                        }

                                        if ($firstVariantImage) {
                                            $displayImage = $firstVariantImage;
                                        }

                                        if (count($prices) > 0) {
                                            $minPrice = min($prices);
                                            $maxPrice = max($prices);
                                            $originalMinPrice = min($originalPrices);
                                            $originalMaxPrice = max($originalPrices);

                                            if ($minPrice != $maxPrice) {
                                                $hasMultiplePrices = true;
                                            } else {
                                                $minPrice = $prices[0];
                                                $bpDiscountedPrice = $minPrice;
                                                $originalMinPrice = $originalPrices[0];
                                            }

                                            if ($originalMinPrice != $originalMaxPrice) {
                                                $hasMultipleOriginalPrices = true;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="slider-card position-relative">
                                    <span class="new-badge">New</span>
                                    @if ($bpHasDiscount && $bpDisplayDiscountValue > 0)
                                        @if ($bpDisplayDiscountType === 'percent')
                                            <span class="badge bg-danger position-absolute" style="top:10px; right:10px; font-size:10px; font-weight:bold; z-index:2; padding:4px 8px; border-radius:4px;">{{ round($bpDisplayDiscountValue) }}% OFF</span>
                                        @else
                                            <span class="badge bg-danger position-absolute" style="top:10px; right:10px; font-size:10px; font-weight:bold; z-index:2; padding:4px 8px; border-radius:4px;"><span style="font-size: 1.2em;">৳ </span> {{ round($bpDisplayDiscountValue) }} OFF</span>
                                        @endif
                                    @endif
                                    <a href="{{ route('product.details', $bp->slug) }}" class="text-decoration-none d-flex flex-column" style="flex-grow: 1;">
                                        <div class="slider-img-wrap">
                                            @if ($displayImage)
                                                <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $bp->name }}">
                                            @else
                                                <img src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($bp->name, 8, '')) }}" alt="{{ $bp->name }}">
                                            @endif
                                        </div>
                                        <div class="slider-card-body">
                                            <div class="product-title hover-blue">{{ $bp->name }}</div>
                                            <div class="product-code">Code: {{ $bp->id < 100 ? 'P' . $bp->id : $bp->id }}</div>
                                            <div class="product-price">
                                                @if ($hasMultiplePrices)
                                                    @if ($bpHasDiscount)
                                                        <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                                                        <span class="old text-decoration-line-through text-muted small ms-1" style="font-size: 11px;"><span style="font-size: 1.2em;">৳</span> {{ number_format($originalMinPrice, 0) }} - {{ number_format($originalMaxPrice, 0) }}</span>
                                                    @else
                                                        <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                                                    @endif
                                                @else
                                                    @if ($bpHasDiscount)
                                                        <span style="font-size: 1.2em;">৳</span> {{ number_format($bpDiscountedPrice, 0) }}
                                                        <span class="old text-decoration-line-through text-muted small ms-1" style="font-size: 11px;"><span style="font-size: 1.2em;">৳</span> {{ number_format(isset($originalMinPrice) ? $originalMinPrice : $bp->price, 0) }}</span>
                                                    @else
                                                        <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                    <div class="px-3 pb-3 mt-auto">
                                        <a href="{{ route('product.details', $bp->slug) }}"
                                            class="btn btn-buy-now w-100 d-inline-flex align-items-center justify-content-center gap-2"
                                            title="Buy Now">
                                            <i class="bi bi-lightning-fill"></i><span> Buy Now</span>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small px-3">No latest products yet.</div>
                            @endforelse
                        </div>
                    </div>
                    @push('scripts')
                    <script>
                        (function() {
                            const slider = document.getElementById('latestSlider');
                            const prevBtn = document.getElementById('latestPrev');
                            const nextBtn = document.getElementById('latestNext');
                            if (!slider) return;

                            // Scroll by one card width
                            function getCardWidth() {
                                const card = slider.querySelector('.slider-card');
                                if (!card) return 240;
                                const style = getComputedStyle(slider);
                                const gap = parseFloat(style.gap) || 20;
                                return card.offsetWidth + gap;
                            }

                            function slideNext() {
                                const cardWidth = getCardWidth();
                                const maxScroll = slider.scrollWidth - slider.clientWidth;
                                if (slider.scrollLeft + cardWidth >= maxScroll - 1) {
                                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                                } else {
                                    slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
                                }
                            }

                            function slidePrev() {
                                const cardWidth = getCardWidth();
                                slider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
                            }

                            prevBtn.addEventListener('click', slidePrev);
                            nextBtn.addEventListener('click', slideNext);

                            // Auto-slide every 2.5 seconds
                            let autoSlide = setInterval(slideNext, 2500);

                            // Pause on hover
                            slider.addEventListener('mouseenter', () => clearInterval(autoSlide));
                            slider.addEventListener('mouseleave', () => {
                                autoSlide = setInterval(slideNext, 2500);
                            });

                            // Pause on manual button click, resume after 4s
                            [prevBtn, nextBtn].forEach(btn => {
                                btn.addEventListener('click', () => {
                                    clearInterval(autoSlide);
                                    autoSlide = setInterval(slideNext, 2500);
                                });
                            });
                        })();
                    </script>
                    @endpush
                </div>
            </div>
        </div>


        <!-- Promo 3 banners -->
        {{-- <div class="row g-3 mb-4">

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
        </div> --}}
    </div>

    <div class="wrap">
        <!-- Flash Sale -->
        <div class="preorder-panel my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                <h4 class="mb-0 fw-bold" style="color: #2b2b2b; position: relative; padding-left: 15px;">
                    <span style="position: absolute; left: 0; top: 10%; height: 80%; width: 4px; background: #E0471B; border-radius: 2px;"></span>
                    <i class="bi bi-lightning-fill me-1" style="color:#E0471B; font-size:1rem;"></i> Flash Sale
                </h4>
                <div class="d-flex gap-1 align-items-center discounted-controls">
                    <span class="arrow d-inline-flex" id="discountedPrev" title="Previous"><i class="bi bi-chevron-left"></i></span>
                    <span class="arrow d-inline-flex" id="discountedNext" title="Next"><i class="bi bi-chevron-right"></i></span>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-12 col-lg-3">
                    <div class="preorder-hero d-flex flex-column justify-content-between text-center" style="background: linear-gradient(135deg, #1c1c1c 0%, #301007 100%); border: 2px solid #E0471B; min-height: 320px;">
                        <!-- Glow Effects -->
                        <div style="position: absolute; width: 120px; height: 120px; background: #E0471B; filter: blur(60px); top: -20px; right: -20px; border-radius: 50%; opacity: 0.6; pointer-events: none;"></div>
                        <div style="position: absolute; width: 120px; height: 120px; background: #ff5521; filter: blur(60px); bottom: -20px; left: -20px; border-radius: 50%; opacity: 0.4; pointer-events: none;"></div>

                        <div class="position-relative w-100" style="z-index: 1;">
                            <span class="badge-limit fw-bold text-white text-uppercase" style="background: #E0471B; border-radius: 50px; padding: 4px 12px; font-size: 10px; letter-spacing: 1px;">Flash Sale</span>
                            <h4 class="fw-bold text-white mt-3 mb-1" style="font-size: 19px; letter-spacing: 0.5px;">MEGA SAVINGS</h4>
                            <p class="text-muted small" style="font-size: 11.5px; opacity: 0.85;">Limited time discount event</p>
                        </div>

                        <div class="position-relative my-2 w-100" style="z-index: 1;">
                            <div class="small fw-bold text-uppercase" style="color: #ff5521; letter-spacing: 3px; font-size: 11px;">UP TO</div>
                            <div class="text-white" style="font-size: 58px; font-weight: 900; line-height: 1; font-family: 'Outfit', sans-serif; text-shadow: 0 4px 15px rgba(224, 71, 27, 0.5);">
                                {{ $maxDiscountPercent }}%
                            </div>
                            <div class="fw-bold text-white" style="font-size: 20px; letter-spacing: 2px; margin-top: 2px;">OFF</div>
                        </div>

                        <div class="position-relative mt-2 w-100" style="z-index: 1;">
                            <div class="d-flex align-items-center justify-content-center gap-2 text-white small fw-semibold" style="font-size: 12px;">
                                <span class="d-inline-block rounded-circle" style="width: 7px; height: 7px; background: #E0471B; animation: pulseGlow 1.5s infinite;"></span>
                                LIVE NOW
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="discounted-slider-wrapper">
                        <div id="discountedSlider" class="discounted-slider">
                            @forelse($discountedProducts as $dp)
                                @php
                                    $hasDiscount = $dp->has_active_discount;
                                    $discountedPrice = $dp->price;
                                    $displayDiscountType = $dp->discount_type;
                                    $displayDiscountValue = $dp->discount_value;
                                    
                                    if ($hasDiscount) {
                                        if ($dp->discount_type === 'percent') {
                                            $discountedPrice = $dp->price - ($dp->price * $dp->discount_value) / 100;
                                        } elseif ($dp->discount_type === 'fixed') {
                                            $discountedPrice = $dp->price - $dp->discount_value;
                                        }
                                    }
                                
                                    $displayImage = $dp->image;
                                    $isVariant = false;
                                    $minPrice = $dp->price;
                                    $maxPrice = $dp->price;
                                    $originalMinPrice = $dp->price;
                                    $originalMaxPrice = $dp->price;
                                    $hasMultiplePrices = false;
                                    $hasMultipleOriginalPrices = false;
                                    $hasVariantDiscount = false;
                                    
                                    if (!empty($dp->variants) && is_array($dp->variants)) {
                                        $prices = [];
                                        $originalPrices = [];
                                        $firstVariantImage = null;
                                        $now = now();
                                        foreach ($dp->variants as $v) {
                                            if (isset($v['combo'])) {
                                                $isVariant = true;
                                                if (isset($v['price']) && $v['price'] > 0) {
                                                    $originalP = (float) $v['price'];
                                                    $p = $originalP;
                                                    
                                                    // Check for variant discount
                                                    if (!empty($v['discount_type']) && isset($v['discount']) && $v['discount'] > 0) {
                                                        $isActive = true;
                                                        $startDate = !empty($v['discount_start']) ? \Carbon\Carbon::parse($v['discount_start']) : null;
                                                        $endDate = !empty($v['discount_end']) ? \Carbon\Carbon::parse($v['discount_end']) : null;
                                                        if ($startDate && $startDate->gt($now)) $isActive = false;
                                                        if ($endDate && $endDate->lt($now)) $isActive = false;
                                                        
                                                        if ($isActive) {
                                                            $hasVariantDiscount = true;
                                                            if ($v['discount_type'] === 'percent') {
                                                                $p = $p - ($p * $v['discount'] / 100);
                                                            } else {
                                                                $p = $p - $v['discount'];
                                                            }
                                                            
                                                            // For badge display, if the main product doesn't have a discount, we show the highest variant discount
                                                            if (!$hasDiscount) {
                                                                if ($v['discount_type'] === 'percent') {
                                                                    if ($displayDiscountType !== 'percent' || $v['discount'] > $displayDiscountValue) {
                                                                        $displayDiscountType = 'percent';
                                                                        $displayDiscountValue = $v['discount'];
                                                                    }
                                                                } else if ($displayDiscountType !== 'percent') { // Prefer percent over fixed for badge, or max fixed
                                                                    if ($v['discount'] > $displayDiscountValue) {
                                                                        $displayDiscountType = 'fixed';
                                                                        $displayDiscountValue = $v['discount'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $prices[] = $p;
                                                    $originalPrices[] = $originalP;
                                                }
                                                if (!$firstVariantImage && isset($v['image']) && !empty($v['image'])) {
                                                    $firstVariantImage = $v['image'];
                                                }
                                            }
                                        }
                                        
                                        if ($hasVariantDiscount && !$hasDiscount) {
                                            $hasDiscount = true;
                                        }
                                
                                        if ($firstVariantImage) {
                                            $displayImage = $firstVariantImage;
                                        }
                                        
                                        if (count($prices) > 0) {
                                            $minPrice = min($prices);
                                            $maxPrice = max($prices);
                                            $originalMinPrice = min($originalPrices);
                                            $originalMaxPrice = max($originalPrices);
                                
                                            if ($minPrice != $maxPrice) {
                                                $hasMultiplePrices = true;
                                            } else {
                                                $minPrice = $prices[0];
                                                $discountedPrice = $minPrice;
                                                $originalMinPrice = $originalPrices[0];
                                            }
                                
                                            if ($originalMinPrice != $originalMaxPrice) {
                                                $hasMultipleOriginalPrices = true;
                                            }
                                        }
                                    }
                                @endphp
                                 <div class="mini-prod discounted-product-card">
                                    <a href="{{ route('product.details', $dp->slug) }}" class="text-decoration-none">
                                        <div class="mini-img-wrap position-relative">
                                            @if ($displayImage)
                                                <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $dp->name }}" class="mini-product-img">
                                            @else
                                                <img
                                                    src="https://placehold.co/180x180/eee/aaa?text={{ urlencode(Str::limit($dp->name, 8, '')) }}"
                                                    alt="{{ $dp->name }}"
                                                    class="mini-product-img">
                                            @endif
                                            @if ($hasDiscount && $displayDiscountValue > 0)
                                                @if ($displayDiscountType === 'percent')
                                                    <span class="badge bg-danger position-absolute" style="top: 8px; left: 8px; font-size: 10px; font-weight: bold; z-index: 5;">{{ round($displayDiscountValue) }}% OFF</span>
                                                @else
                                                    <span class="badge bg-danger position-absolute" style="top: 8px; left: 8px; font-size: 10px; font-weight: bold; z-index: 5;">৳{{ round($displayDiscountValue) }} OFF</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="t text-dark hover-blue px-2 pt-2">{{ Str::limit($dp->name, 35) }}</div>
                                    </a>
                                    <div class="px-2">
                                        <div class="code">Code: {{ $dp->id < 100 ? 'P' . $dp->id : $dp->id }}</div>
                                        <div class="mt-2 text-center">
                                            @if ($hasMultiplePrices)
                                                @if ($hasDiscount)
                                                    <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                                                    <span class="text-decoration-line-through text-muted ms-1" style="font-size:10px;"><span style="font-size: 1.2em;">৳</span> {{ number_format($originalMinPrice, 0) }} - {{ number_format($originalMaxPrice, 0) }}</span>
                                                @else
                                                    <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                                                @endif
                                            @else
                                                @if ($hasDiscount)
                                                    <span style="font-size: 1.2em;">৳</span> {{ number_format($discountedPrice, 0) }}
                                                    <span class="text-decoration-line-through text-muted ms-1" style="font-size:10px;"><span style="font-size: 1.2em;">৳</span> {{ number_format($isVariant ? $originalMinPrice : $dp->price, 0) }}</span>
                                                @else
                                                    <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="px-2 pb-2 mt-1 d-flex gap-2 justify-content-center align-items-center product-card-actions">
                                        <a href="{{ route('product.details', $dp->slug) }}"
                                            class="btn btn-buy-now w-100 py-2 d-inline-flex align-items-center justify-content-center gap-1"
                                            style="font-size: 11px; font-weight: 600; border-radius: 6px;"
                                            title="Buy Now">
                                            <i class="bi bi-lightning-fill"></i><span> Buy Now</span>
                                        </a>
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
        <div id="products-grid" class="mb-3">
            @forelse($homeCategories as $category)
                @if($category->products->isNotEmpty())
                    <div class="col-12 mt-4 mb-3">
                        <h4 class="mb-0 fw-bold category-grid-title" style="color: #2b2b2b; position: relative; padding-left: 15px;">
                            <span style="position: absolute; left: 0; top: 10%; height: 80%; width: 4px; background: #E0471B; border-radius: 2px;"></span>
                            {{ $category->name }}
                        </h4>
                    </div>
                    <div id="category-products-{{ $category->id }}" class="row g-3">
                        @foreach($category->products as $product)
                            @include('frontend.partials.product_card', ['product' => $product])
                        @endforeach
                    </div>
                    @if($category->products_count > 8)
                        <div class="text-center mb-4 mt-4">
                            <button class="btn btn-outline-dark px-5 load-more-category-btn" data-category-id="{{ $category->id }}" data-page="2">
                                <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                                Load more
                            </button>
                        </div>
                    @endif
                @endif
            @empty
                <div class="text-muted small px-3 mt-4">No categories or products available.</div>
            @endforelse
        </div>
    </div>

    @push('styles')
        <style>
            /* Hero Category Hover Submenu (Premium Design) */
            .hero-cat-item:hover .hero-cat-submenu {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateX(0) !important;
            }
            .smart-submenu-link {
                transition: all 0.2s ease;
            }
            .smart-submenu-link:hover {
                background-color: #fff4f1;
                color: #E0471B !important;
                transform: translateX(4px);
            }
            /* Fix carousel arrow appearing over submenu */
            .carousel-control-prev,
            .carousel-control-next {
                z-index: 5 !important;
            }

            .category-grid-title {
                font-size: 30px;
                font-weight: 700;
            }
            @media (max-width: 768px) {
                .category-grid-title {
                    font-size: 20px;
                }
            }

            /* Hero Slider Styling */
            .hero-carousel {
                position: relative;
                width: 100%;
                height: 450px;
                overflow: hidden;
                border-radius: 8px;
                background-color: #e9ecef;
            }
            .hero-slider-img {
                width: 100%;
                height: 450px;
                object-fit: cover;
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
                .hero-carousel, .hero-slider-img {
                    height: 320px;
                }
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
                .hero-carousel, .hero-slider-img {
                    height: 200px;
                }
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
            });            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtns = document.querySelectorAll('.load-more-category-btn');

                loadMoreBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const page = btn.getAttribute('data-page');
                        const categoryId = btn.getAttribute('data-category-id');
                        const spinner = btn.querySelector('.spinner-border');
                        const categoryGrid = document.getElementById('category-products-' + categoryId);

                        spinner.classList.remove('d-none');
                        btn.disabled = true;

                        const urlParams = new URLSearchParams(window.location.search);
                        const search = urlParams.get('search') || '';

                        fetch(`/?page=${page}&category_id=${categoryId}&search=${encodeURIComponent(search)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            categoryGrid.insertAdjacentHTML('beforeend', data.html);
                            if(data.has_more) {
                                btn.setAttribute('data-page', parseInt(page) + 1);
                                spinner.classList.add('d-none');
                                btn.disabled = false;
                            } else {
                                btn.parentElement.remove();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            spinner.classList.add('d-none');
                            btn.disabled = false;
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
