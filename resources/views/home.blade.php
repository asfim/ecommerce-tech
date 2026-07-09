@extends('layouts.app')

@section('content')
    <!-- Hero section -->
    <div class="hero-sec">
        <div class="wrap">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="brand-banner">
                        @if (!empty($heroBanners[0]))
                            <img src="{{ asset('storage/' . $heroBanners[0]) }}" alt="fashion model">
                        @else
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80"
                                alt="fashion model">
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="season-banner">
                        @if (!empty($heroBanners[1]))
                            <img src="{{ asset('storage/' . $heroBanners[1]) }}" alt="end of season model">
                        @else
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80"
                                alt="end of season model">
                        @endif

                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="hotcat-panel">
                        <h6><i class="bi bi-fire text-danger"></i> Hot Categories</h6>
                        <div class="row g-2 mt-1">
                            @foreach ($hotCategories as $cat)
                                <div class="col-3">
                                    <a href="{{ route('category.products', $cat->id) }}" class="hotcat-item">
                                        @if ($cat->image)
                                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                        @else
                                            <img src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($cat->name, 8, '')) }}"
                                                alt="{{ $cat->name }}">
                                        @endif
                                        <div class="name">{{ $cat->name }}</div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured products strip -->
            <div class="featured-strip">
                <div class="row align-items-center g-2">
                    <div class="col-12 col-md-3"><b>Featured Products</b></div>
                    <div class="col-10 col-md-8" style="overflow:hidden;">
                        <div id="featuredSlider" style="display:flex;transition:transform .4s ease;gap:0;">
                            @forelse($featuredProducts as $fp)
                                <div class="fs-item" style="display:flex;align-items:center;gap:10px;">
                                    @if ($fp->image)
                                        <a href="{{ route('product.details', $fp->slug) }}">
                                            <img src="{{ asset('storage/' . $fp->image) }}"
                                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
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
                                    style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
                            @else
                                <img src="https://placehold.co/74x74/eee/aaa?text={{ urlencode(Str::limit($tc->name, 8, '')) }}"
                                    alt="{{ $tc->name }}"
                                    style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
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
                                <div class="p">${{ number_format($bp->price, 2) }}</div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-custom-cart w-100 add-to-cart-btn py-1 px-2 mb-1"
                                        style="border-radius:15px; font-weight:600; font-size:11px;"
                                        data-id="{{ $bp->id }}" data-name="{{ $bp->name }}"
                                        data-price="{{ $bp->price }}"
                                        data-image="{{ $bp->image ? asset('storage/' . $bp->image) : 'https://placehold.co/150x150/eee/aaa?text=' . urlencode(Str::limit($bp->name, 8, '')) }}">
                                        <i class="bi bi-cart3"></i> Add to cart
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
                            <span class="arrow d-inline-flex" id="newArrivalPrev"
                                style="width:24px;height:24px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;font-size:10px;"><i
                                    class="bi bi-chevron-left"></i></span>
                            <span class="arrow d-inline-flex" id="newArrivalNext"
                                style="width:24px;height:24px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;font-size:10px;"><i
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
                                                        <div class="d-flex gap-1 mt-1">
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-custom-cart add-to-cart-btn px-2 py-0 d-inline-flex align-items-center justify-content-center"
                                                                style="height: 24px; font-size:11px; border-radius:10px;"
                                                                data-id="{{ $np->id }}" data-name="{{ $np->name }}"
                                                                data-price="{{ $np->price }}"
                                                                data-image="{{ $np->image ? asset('storage/' . $np->image) : 'https://placehold.co/100x100/eee/aaa?text=' . urlencode(Str::limit($np->name, 8, '')) }}">
                                                                <i class="bi bi-cart-plus"></i> Add
                                                            </button>
                                                            <button class="btn btn-sm btn-primary btn-custom-buy py-0 px-2 d-inline-flex align-items-center justify-content-center btn-bid"
                                                                style="height: 24px; font-size:11px; border-radius:10px;"
                                                                data-id="{{ $np->id }}"
                                                                data-name="{{ $np->name }}"
                                                                data-price="{{ $np->price }}"
                                                                data-image="{{ $np->image ? asset('storage/' . $np->image) : 'https://placehold.co/100x100/eee/aaa?text=' . urlencode(Str::limit($np->name, 8, '')) }}">Buy Now</button>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Discounted Products</h5>
                <div class="d-flex gap-1 align-items-center">
                    <span class="arrow d-inline-flex" id="discountedPrev"
                        style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                            class="bi bi-chevron-left"></i></span>
                    <span class="arrow d-inline-flex" id="discountedNext"
                        style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i
                            class="bi bi-chevron-right"></i></span>
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
                    <div style="overflow: hidden;">
                        <div id="discountedSlider" style="display: flex; gap: 15px; transition: transform .4s ease;">
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
                                <div class="mini-prod"
                                    style="min-width: calc(25% - 11.25px); flex: 0 0 calc(25% - 11.25px);">
                                    <a href="{{ route('product.details', $dp->slug) }}" class="text-decoration-none">
                                        <div class="mini-img-wrap">
                                            @if ($dp->image)
                                                <img src="{{ asset('storage/' . $dp->image) }}">
                                            @else
                                                <img
                                                    src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($dp->name, 8, '')) }}">
                                            @endif
                                        </div>
                                        <div class="t text-dark hover-blue">{{ Str::limit($dp->name, 35) }}</div>
                                    </a>
                                    <div class="p">
                                        @if ($hasDiscount)
                                            ${{ number_format($discountedPrice, 2) }}
                                            <span class="old text-decoration-line-through text-muted"
                                                style="font-size:10px;">${{ number_format($dp->price, 2) }}</span>
                                        @else
                                            ${{ number_format($dp->price, 2) }}
                                        @endif
                                    </div>
                                    <div class="rating">★★★★★</div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-custom-cart w-100 add-to-cart-btn py-1 px-2 mb-1"
                                            style="border-radius:15px; font-weight:600; font-size:11px;"
                                            data-id="{{ $dp->id }}" data-name="{{ $dp->name }}"
                                            data-price="{{ $discountedPrice }}"
                                            data-image="{{ $dp->image ? asset('storage/' . $dp->image) : 'https://placehold.co/150x150/eee/aaa?text=' . urlencode(Str::limit($dp->name, 8, '')) }}">
                                            <i class="bi bi-cart3"></i> Add to cart
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
        <div class="row g-3 mb-3">
            @forelse($products as $product)
                @php
                    $hasDiscount = $product->discount_type && $product->discount_value > 0;
                    $discountedPrice = $product->price;
                    if ($hasDiscount) {
                        if ($product->discount_type === 'percent') {
                            $discountedPrice = $product->price - ($product->price * $product->discount_value) / 100;
                        } elseif ($product->discount_type === 'fixed') {
                            $discountedPrice = $product->price - $product->discount_value;
                        }
                    }
                @endphp
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="prod-card">
                        <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
                            <div class="prod-img-wrap">
                                @if ($hasDiscount)
                                    @if ($product->discount_type === 'percent')
                                        <span class="badge-new-arrival">-{{ round($product->discount_value) }}%</span>
                                    @else
                                        <span
                                            class="badge-new-arrival">-${{ number_format($product->discount_value, 0) }}</span>
                                    @endif
                                @else
                                    <span class="badge-new-arrival">NEW</span>
                                @endif

                                @if ($product->stock <= 5 && $product->stock > 0)
                                    <span class="badge bg-primary position-absolute"
                                        style="top:10px;right:10px;font-size:9px;">Limited Stock</span>
                                @elseif($product->stock == 0)
                                    <span class="badge bg-danger position-absolute"
                                        style="top:10px;right:10px;font-size:9px;">Out of Stock</span>
                                @endif

                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}">
                                @else
                                    <img
                                        src="https://placehold.co/200x200/eee/aaa?text={{ urlencode(Str::limit($product->name, 8, '')) }}">
                                @endif
                            </div>
                        </a>

                        <div class="prod-info">
                            <div>
                                <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
                                    <div class="t text-dark hover-blue">{{ Str::limit($product->name, 35) }}</div>
                                </a>
                                <div class="p">
                                    @if ($hasDiscount)
                                        ${{ number_format($discountedPrice, 2) }}
                                        <span class="old">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        ${{ number_format($product->price, 2) }}
                                    @endif
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="button" class="btn btn-custom-cart w-100 add-to-cart-btn py-1 mb-1"
                                    style="border-radius:15px; font-weight:600; font-size:11px;"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $discountedPrice }}"
                                    data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/200x200/eee/aaa?text=' . urlencode(Str::limit($product->name, 8, '')) }}">
                                    <i class="bi bi-cart3"></i> Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted small px-3">No products available.</div>
            @endforelse
        </div>
        <div class="text-center mb-4">
            <a href="#" class="btn btn-outline-dark px-5">Load more</a>
        </div>
    </div>

    @push('styles')
        <style>
            .hover-blue {
                transition: color 0.2s ease;
            }
            .hover-blue:hover {
                color: #1a73e8 !important;
            }

            /* Premium product card styles and transitions (aligned with user request image) */
            .prod-card {
                position: relative;
                overflow: hidden;
                background: #FAF5E6;
                /* warm cream background like the image */
                border: 1px solid transparent;
                border-radius: 16px;
                /* rounded corners */
                text-align: left;
                /* left align text */
                height: 100%;
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .prod-card:hover {
                transform: translateY(-5px);
                border-color: #0066b9 !important;
                box-shadow: 0 12px 30px rgba(0, 102, 185, 0.15) !important;
            }

            .prod-img-wrap {
                position: relative;
                width: 100%;
                height: 180px;
                overflow: hidden;
                background: transparent;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            }

            .prod-img-wrap img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                transition: transform 0.3s ease;
            }

            .prod-card:hover .prod-img-wrap img {
                transform: scale(1.06);
            }

            .prod-info {
                padding: 12px;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .prod-card .t {
                font-size: 13px;
                font-weight: 700;
                color: #0c1e1a;
                margin-bottom: 4px;
                min-height: 34px;
                line-height: 1.3;
            }

            .prod-card .p {
                font-weight: 800;
                font-size: 15px;
                color: #0c1e1a;
                margin-bottom: 8px;
            }

            .prod-card .p .old {
                font-size: 12.5px;
                font-weight: 400;
                color: #888;
                text-decoration: line-through;
                margin-left: 6px;
            }

            .badge-new-arrival {
                position: absolute;
                top: 10px;
                left: 10px;
                font-size: 10px;
                font-weight: 800;
                background: #0D1F1C;
                /* dark pill like image */
                color: #FFC107;
                /* gold text like image */
                padding: 4px 12px;
                border-radius: 20px;
                letter-spacing: 0.5px;
                z-index: 2;
            }


            /* Mini product cards (Best Selling & Discounted sections) styled like the main product cards */
            .mini-prod {
                background: #FAF5E6;
                /* warm cream background */
                border: 1px solid transparent;
                border-radius: 14px;
                padding: 10px;
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100%;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
                text-align: left;
                /* left align text */
            }

            .mini-prod:hover {
                /* transform: translateY(-4px); */
                border-color: #0066b9 !important;
                box-shadow: 0 8px 20px rgba(0, 102, 185, 0.12) !important;
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

            .mini-img-wrap {
                position: relative;
                width: 100%;
                height: 110px;
                margin-bottom: 6px;
                overflow: hidden;
                border-radius: 10px;
                background: transparent;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            }

            .mini-img-wrap img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                transition: transform 0.3s ease;
            }

            .mini-prod:hover .mini-img-wrap img {
                transform: scale(1.06);
            }

            .mini-prod .t {
                font-size: 11.5px;
                font-weight: 700;
                color: #0c1e1a;
                margin-bottom: 3px;
                min-height: 30px;
                line-height: 1.2;
            }

            .mini-prod .p {
                font-weight: 800;
                font-size: 13px;
                color: #0c1e1a;
                margin-bottom: 4px;
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

            /* Checkout Modal */
            .checkout-modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            .checkout-modal-backdrop.show {
                opacity: 1;
                pointer-events: auto;
            }

            .checkout-modal {
                background: #fff;
                border-radius: 12px;
                width: 90%;
                max-width: 450px;
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
                transform: translateY(-20px);
                transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                overflow: hidden;
            }

            .checkout-modal-backdrop.show .checkout-modal {
                transform: translateY(0);
            }

            .checkout-header {
                background: #f8f9fa;
                padding: 16px 20px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .checkout-header h5 {
                margin: 0;
                font-weight: 700;
                color: #1c1c1c;
            }

            .checkout-body {
                padding: 20px;
            }

            .checkout-footer {
                padding: 14px 20px;
                border-top: 1px solid #eee;
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                background: #f8f9fa;
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
        </script>
    @endpush
@endsection
