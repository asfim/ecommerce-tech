@php
    $hasDiscount = $product->has_active_discount;
    $discountedPrice = $product->price;
    $displayDiscountType = $product->discount_type;
    $displayDiscountValue = $product->discount_value;
    
    if ($hasDiscount) {
        if ($product->discount_type === 'percent') {
            $discountedPrice = $product->price - ($product->price * $product->discount_value) / 100;
        } elseif ($product->discount_type === 'fixed') {
            $discountedPrice = $product->price - $product->discount_value;
        }
    }

    $displayImage = $product->image;
    $isVariant = false;
    $minPrice = $product->price;
    $maxPrice = $product->price;
    $originalMinPrice = $product->price;
    $originalMaxPrice = $product->price;
    $hasMultiplePrices = false;
    $hasMultipleOriginalPrices = false;
    $hasVariantDiscount = false;
    
    if (!empty($product->variants) && is_array($product->variants)) {
        $prices = [];
        $originalPrices = [];
        $firstVariantImage = null;
        $now = now();
        foreach ($product->variants as $v) {
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
<div class="col-6 col-sm-6 col-md-4 col-lg-3">
    <div class="prod-card">
        <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
            <div class="prod-img-wrap">
                @if ($hasDiscount && $displayDiscountValue > 0)
                    @if ($displayDiscountType === 'percent')
                        <span class="badge-new-arrival">{{ round($displayDiscountValue) }}% OFF</span>
                    @else
                        <span class="badge-new-arrival">৳{{ round($displayDiscountValue) }} OFF</span>
                    @endif
                @endif



                @if ($product->stock <= 5 && $product->stock > 0)
                    <span class="badge bg-primary position-absolute"
                        style="top:10px;right:10px;font-size:9px;z-index:5;">Limited Stock</span>
                @elseif($product->stock == 0)
                    <span class="badge bg-danger position-absolute"
                        style="top:10px;right:10px;font-size:9px;z-index:5;">Out of Stock</span>
                @endif

                @if ($displayImage)
                    <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}" class="prod-product-img">
                @else
                    <img
                        src="https://placehold.co/240x240/eee/aaa?text={{ urlencode(Str::limit($product->name, 8, '')) }}"
                        alt="{{ $product->name }}"
                        class="prod-product-img">
                @endif
            </div>
        </a>

        <div class="prod-info">
            <div>
                <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
                    <div class="t text-dark hover-blue">{{ Str::limit($product->name, 35) }}</div>
                </a>
                <div class="p">
                    @if ($hasMultiplePrices)
                        @if ($hasDiscount)
                            <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                            <span class="old"><span style="font-size: 1.2em;">৳</span> {{ number_format($originalMinPrice, 0) }} - {{ number_format($originalMaxPrice, 0) }}</span>
                        @else
                            <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                        @endif
                    @else
                        @if ($hasDiscount)
                            <span style="font-size: 1.2em;">৳</span> {{ number_format($discountedPrice, 0) }}
                            <span class="old"><span style="font-size: 1.2em;">৳</span> {{ number_format($isVariant ? $originalMinPrice : $product->price, 0) }}</span>
                        @else
                            <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }}
                        @endif
                    @endif
                </div>
                <div class="prod-stock-badge">
                    @if ($product->stock > 0)
                        <span class="stock-in"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                    @else
                        <span class="stock-out"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
                    @endif
                </div>
            </div>

            <div class="mt-2 d-flex gap-2 justify-content-center align-items-center product-card-actions">
                <a href="#"
                    class="btn btn-buy-now w-100 py-2 d-inline-flex align-items-center justify-content-center gap-1 btn-bid"
                    data-id="{{ $product->id }}" 
                    data-name="{{ $product->name }}" 
                    data-price="{{ $hasDiscount ? $discountedPrice : $minPrice }}" 
                    data-original-price="{{ $hasDiscount ? $originalMinPrice : $product->price }}"
                    data-image="{{ $displayImage }}"
                    style="font-size: 11px; font-weight: 600; border-radius: 6px;"
                    title="Buy Now">
                    <i class="bi bi-lightning-fill"></i><span> Buy Now</span>
                </a>
            </div>
        </div>
    </div>
</div>
