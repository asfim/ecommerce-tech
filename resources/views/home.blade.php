@extends('layouts.app')

@section('title', 'eCommerce - Fashion Store')

@section('content')
    <!-- Hero section -->
    <div class="hero-sec">
        <div class="wrap">
            <div class="row g-3">
                <div class="col-4">
                    <div class="brand-banner">
                        @if (!empty($heroBanners[0]))
                            <img src="{{ asset('storage/' . $heroBanners[0]) }}" alt="fashion model">
                        @else
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80"
                                alt="fashion model">
                        @endif
                    </div>
                </div>
                <div class="col-4">
                    <div class="season-banner">
                        @if (!empty($heroBanners[1]))
                            <img src="{{ asset('storage/' . $heroBanners[1]) }}" alt="end of season model">
                        @else
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80"
                                alt="end of season model">
                        @endif

                    </div>
                </div>
                <div class="col-4">
                    <div class="hotcat-panel">
                        <h6><i class="bi bi-fire text-danger"></i> Hot Categories</h6>
                        <div class="row g-2 mt-1">
                            @foreach ($hotCategories as $cat)
                                <div class="col-3 hotcat-item">
                                    @if ($cat->image)
                                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                    @else
                                        <img src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($cat->name, 8, '')) }}"
                                            alt="{{ $cat->name }}">
                                    @endif
                                    <div class="name">{{ $cat->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured products strip -->
            <div class="featured-strip">
                <div class="row align-items-center">
                    <div class="col-2"><b>Featured Products</b></div>
                    <div class="col-9" style="overflow:hidden;">
                        <div id="featuredSlider" style="display:flex;transition:transform .4s ease;gap:0;">
                            @forelse($featuredProducts as $fp)
                                <div class="fs-item"
                                    style="min-width:33.333%;flex:0 0 33.333%;display:flex;align-items:center;gap:10px;padding:0 8px;">
                                    @if ($fp->image)
                                        <img src="{{ asset('storage/' . $fp->image) }}"
                                            style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <img src="https://placehold.co/50x50/eee/aaa?text=No+Img"
                                            style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                    @endif
                                    <div>
                                        <div class="t">{{ Str::limit($fp->name, 35) }}</div>
                                        <div class="p">${{ number_format($fp->price, 2) }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small px-2">No featured products yet.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-1 text-end d-flex justify-content-end gap-1">
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
                        <div class="tcat-item"
                            style="min-width: calc(16.666% - 12.5px); flex: 0 0 calc(16.666% - 12.5px); text-align: center;">
                            @if ($tc->image)
                                <img src="{{ asset('storage/' . $tc->image) }}" alt="{{ $tc->name }}"
                                    style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
                            @else
                                <img src="https://placehold.co/74x74/eee/aaa?text={{ urlencode(Str::limit($tc->name, 8, '')) }}"
                                    alt="{{ $tc->name }}"
                                    style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
                            @endif
                            <div class="name" style="font-size:11.5px;">{{ $tc->name }}</div>
                        </div>
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

        <!-- Best selling / Today's deal -->
        <div class="row g-3 mb-4">
            <div class="col-9">
                <div class="bestselling-panel h-100">
                    <div class="panel-title">Best Selling <span class="arrow"><i class="bi bi-chevron-right"></i></span>
                    </div>
                    <div class="row g-2 px-3 pb-3">
                        @forelse($bestSellingProducts as $bp)
                            <div class="col mini-prod">
                                <div class="mini-img-wrap">
                                    @if ($bp->image)
                                        <img src="{{ asset('storage/' . $bp->image) }}">
                                    @else
                                        <img
                                            src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($bp->name, 8, '')) }}">
                                    @endif
                                </div>
                                <div class="t">{{ Str::limit($bp->name, 35) }}</div>
                                <div class="p">${{ number_format($bp->price, 2) }}</div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-dark w-100 add-to-cart-btn py-1 px-2 mb-1"
                                        style="background:#0066b9; border-color:#0D1F1C; border-radius:15px; font-weight:600; font-size:11px;"
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
            <div class="col-3">
                <div class="todaydeal-panel h-100">
                    <div class="panel-title">Todays Deal <span class="arrow"><i class="bi bi-chevron-right"></i></span>
                    </div>
                    <div class="text-center px-3 pb-3">
                        <img src="https://images.unsplash.com/photo-1588058365548-9ae5966c1e77?w=200&q=80"
                            style="width:100px;height:100px;object-fit:contain;">
                        <div class="t small mt-2">Apple TV 4K Ethernet + WiFi</div>
                        <div class="p">$111.00 <span
                                class="old text-decoration-line-through text-muted">$500.00</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promo 3 banners -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="promo3">
                    @if (!empty($bestSellingBanners[0]))
                        <img src="{{ asset('storage/' . $bestSellingBanners[0]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&q=80">
                    @endif
                </div>
            </div>
            <div class="col-4">
                <div class="promo3">
                    @if (!empty($bestSellingBanners[1]))
                        <img src="{{ asset('storage/' . $bestSellingBanners[1]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&q=80">
                    @endif
                </div>
            </div>
            <div class="col-4">
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
            <div class="col-4">
                <div class="newarrival-banner">
                    @if (!empty($newArrivalsBanner[0]))
                        <img src="{{ asset('storage/' . $newArrivalsBanner[0]) }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=300&q=80">
                    @endif
                </div>
            </div>
            <div class="col-8">
                <div class="newarrival-list-panel p-3">
                    <div class="d-flex justify-content-between mb-2"><b>New Arrival</b><small class="text-muted">Products
                            (109)</small></div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="newarrival-item">
                                <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&q=80">
                                <div class="flex-grow-1">
                                    <div class="t">Apple 2024 MacBook Air 15-inch Laptop with M3 chip 16.3-inch</div>
                                    <div class="bid"><b>$1,499.00</b></div><button class="btn-bid mt-1">Buy
                                        Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="newarrival-item">
                                <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=100&q=80">
                                <div class="flex-grow-1">
                                    <div class="t">Flash Furniture Whitney Mid-Back Desk Chair - Black Leatherso...
                                    </div>
                                    <div class="bid"><b>$132.00</b></div><button class="btn-bid mt-1">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="newarrival-item">
                                <img src="https://images.unsplash.com/photo-1591370874773-6702e8f12fd8?w=100&q=80">
                                <div class="flex-grow-1">
                                    <div class="t">Graco Modes Nest Travel System, Includes Baby Stroller with Height
                                    </div>
                                    <div class="bid"><b>$399.00</b></div><button class="btn-bid mt-1">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="newarrival-item">
                                <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?w=100&q=80">
                                <div class="flex-grow-1">
                                    <div class="t">Rolmium 32LB/52LB Adjustable Dumbbells, 5 Weight Options</div>
                                    <div class="bid"><b>$128.00</b></div><button class="btn-bid mt-1">Buy Now</button>
                                </div>
                            </div>
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
                <div class="col-3">
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
                <div class="col-9">
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
                                    <div class="mini-img-wrap">
                                        @if ($dp->image)
                                            <img src="{{ asset('storage/' . $dp->image) }}">
                                        @else
                                            <img
                                                src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($dp->name, 8, '')) }}">
                                        @endif
                                    </div>
                                    <div class="t">{{ Str::limit($dp->name, 35) }}</div>
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
                                        <button type="button" class="btn btn-dark w-100 add-to-cart-btn py-1 px-2 mb-1"
                                            style="background:#0066b9; border-color:#0D1F1C; border-radius:15px; font-weight:600; font-size:11px;"
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
                <div class="col-2">
                    <div class="prod-card">
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

                        <div class="prod-info">
                            <div>
                                <div class="t">{{ Str::limit($product->name, 35) }}</div>
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
                                <button type="button" class="btn btn-dark w-100 add-to-cart-btn py-1 mb-1"
                                    style="background:#0066b9; border-color:#0D1F1C; border-radius:15px; font-weight:600; font-size:11px;"
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
                width: 100%;
                height: 100%;
                object-fit: cover;
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
                transform: translateY(-4px);
                border-color: #0066b9 !important;
                box-shadow: 0 8px 20px rgba(0, 102, 185, 0.12) !important;
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
                width: 100%;
                height: 100%;
                object-fit: cover;
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
                const visibleItems = 3;
                let currentIndex = 0;
                const maxIndex = Math.max(0, totalItems - visibleItems);

                function updateSlider() {
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
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        updateSlider();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('trendingSlider');
                const prevBtn = document.getElementById('trendingPrev');
                const nextBtn = document.getElementById('trendingNext');
                if (!slider || !prevBtn || !nextBtn) return;

                const items = slider.querySelectorAll('.tcat-item');
                const totalItems = items.length;
                const visibleItems = 6;
                let currentIndex = 0;
                const maxIndex = Math.max(0, totalItems - visibleItems);

                function updateSlider() {
                    // Calculate offset based on item width and margins/gaps.
                    // Since they are inside display: flex with gap: 15px,
                    // translating by (100 / visibleItems)% per item plus accounting for gap
                    // can be simplified or just using percentage-based translation.
                    // Since min-width of items is calc(16.666% - 12.5px) and gap is 15px,
                    // moving by item index is cleanest with container scroll or computed style offsets.
                    // Alternatively, translating by a percentage of container:
                    // each item step = (100% of container + gap total) / visibleItems = (100 + gap_factor) / 6.
                    // With simple translateX, since parent has overflow hidden:
                    // Offset can be: (100 / 6) * currentIndex + (currentIndex * gapOffset)
                    // Or simply: scrollLeft / CSS scroll-behavior: smooth.
                    // Let's use standard container translate:
                    // The offset in px: itemWidth + gap = (containerWidth - 5*15)/6 + 15 = containerWidth/6 + 2.5px.
                    // Alternatively, we can translate by:
                    // index * (100 / visibleItems)%
                    // This is perfectly fine if layout uses gap and items scale accordingly.
                    // Let's translate by: currentIndex * (100 + 15) / 6 % or scroll.
                    // Scroll is actually extremely reliable and works with gaps automatically!
                    // Let's just use scrollTo/scrollBy or element.scrollLeft.
                    // Let's write standard CSS transform or scrollLeft:
                    // scrollLeft is super smooth and handles gaps natively!
                }

                function scrollSlider() {
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
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        scrollSlider();
                    }
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
                const visibleItems = 4;
                let currentIndex = 0;
                const maxIndex = Math.max(0, totalItems - visibleItems);

                function scrollSlider() {
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
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        scrollSlider();
                    }
                });

                slider.parentElement.style.scrollBehavior = 'smooth';
            });

            // ─── Frontend Cart & Checkout System ───────────────────────────────
            (function() {
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');

                function updateCartBadge() {
                    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                    const cartBadge = document.querySelector('a.icon-btn:has(i.bi-bag) .badge-num');
                    if (cartBadge) {
                        cartBadge.textContent = totalItems;
                        cartBadge.classList.remove('badge-bounce');
                        void cartBadge.offsetWidth; // Trigger reflow
                        cartBadge.classList.add('badge-bounce');
                    }
                }

                function showNotification(productName, productImage) {
                    const toast = document.createElement('div');
                    toast.className = 'custom-cart-toast';
                    toast.innerHTML = `
        <img src="${productImage}" alt="${productName}">
        <div class="toast-content">
          <h6>Added to Cart!</h6>
          <p>${productName}</p>
        </div>
      `;
                    document.body.appendChild(toast);

                    setTimeout(() => toast.classList.add('show'), 10);

                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 400);
                    }, 3000);
                }

                function addToCart(productId, productName, productPrice, productImage) {
                    const existing = cart.find(item => item.id == productId);
                    if (existing) {
                        existing.quantity += 1;
                    } else {
                        cart.push({
                            id: productId,
                            name: productName,
                            price: parseFloat(productPrice),
                            image: productImage,
                            quantity: 1
                        });
                    }
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartBadge();
                    showNotification(productName, productImage);
                }

                // Bind event listeners for "Add to Cart"
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.add-to-cart-btn');
                    if (btn) {
                        e.preventDefault();
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        const price = btn.dataset.price;
                        const image = btn.dataset.image;
                        addToCart(id, name, price, image);
                    }
                });

                // Create Modal Elements in Body
                const backdrop = document.createElement('div');
                backdrop.className = 'checkout-modal-backdrop';
                backdrop.innerHTML = `
      <div class="checkout-modal">
        <div class="checkout-header">
          <h5><i class="bi bi-shield-check text-primary me-2"></i>Secure Checkout</h5>
          <button type="button" class="btn-close close-checkout" style="font-size:12px;"></button>
        </div>
        <div class="checkout-body">
          <p class="text-muted small mb-3">Please fill out your details to complete your order.</p>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Product Name</label>
            <input type="text" id="checkoutProdName" class="form-control form-control-sm" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Total Price</label>
            <input type="text" id="checkoutProdPrice" class="form-control form-control-sm text-primary fw-bold" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Shipping Address</label>
            <textarea id="checkoutAddress" class="form-control form-control-sm" rows="2" placeholder="123 Main St, Dhaka" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Phone Number</label>
            <input type="tel" id="checkoutPhone" class="form-control form-control-sm" placeholder="+8801xxxxxxxxx" required>
          </div>
        </div>
        <div class="checkout-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary close-checkout">Cancel</button>
          <button type="button" id="submitOrderBtn" class="btn btn-sm btn-primary px-3">Place Order</button>
        </div>
      </div>
    `;
                document.body.appendChild(backdrop);

                const checkoutModal = backdrop.querySelector('.checkout-modal');
                const checkoutProdName = backdrop.querySelector('#checkoutProdName');
                const checkoutProdPrice = backdrop.querySelector('#checkoutProdPrice');
                const checkoutAddress = backdrop.querySelector('#checkoutAddress');
                const checkoutPhone = backdrop.querySelector('#checkoutPhone');

                function openCheckout(productName, productPrice) {
                    checkoutProdName.value = productName;
                    checkoutProdPrice.value = `$${parseFloat(productPrice).toFixed(2)}`;
                    checkoutAddress.value = '';
                    checkoutPhone.value = '';
                    backdrop.classList.add('show');
                }

                function closeCheckout() {
                    backdrop.classList.remove('show');
                }

                backdrop.addEventListener('click', function(e) {
                    if (e.target === backdrop || e.target.closest('.close-checkout')) {
                        closeCheckout();
                    }
                });

                // // Bind event listeners for "Buy Now"
                // document.addEventListener('click', function(e) {
                //   const btn = e.target.closest('.buy-now-btn');
                //   if (btn) {
                //     e.preventDefault();
                //     const name = btn.dataset.name;
                //     const price = btn.dataset.price;
                //     openCheckout(name, price);
                //   }
                // });

                // Complete Purchase
                backdrop.querySelector('#submitOrderBtn').addEventListener('click', function() {
                    if (!checkoutAddress.value || !checkoutPhone.value) {
                        alert('Please fill out all shipping details.');
                        return;
                    }

                    const prodName = checkoutProdName.value;
                    closeCheckout();

                    // Show Order Success Toast
                    const successToast = document.createElement('div');
                    successToast.className = 'custom-cart-toast';
                    successToast.style.borderColor = '#2e7d32'; // green
                    successToast.innerHTML = `
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-check2-circle text-success fs-4"></i>
          <div class="toast-content">
            <h6 style="color: #2e7d32;">Order Placed!</h6>
            <p>Thank you for buying ${prodName}!</p>
          </div>
        </div>
      `;
                    document.body.appendChild(successToast);
                    setTimeout(() => successToast.classList.add('show'), 10);
                    setTimeout(() => {
                        successToast.classList.remove('show');
                        setTimeout(() => successToast.remove(), 400);
                    }, 4000);
                });

                // Run badge update on load
                updateCartBadge();
            })();
        </script>
    @endpush
@endsection
