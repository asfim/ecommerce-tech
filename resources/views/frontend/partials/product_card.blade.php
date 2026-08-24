@php
    $hasDiscount = $product->has_active_discount;
    $discountedPrice = $product->price;
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
    $hasMultiplePrices = false;
    
    if (!empty($product->variants) && is_array($product->variants)) {
        $prices = [];
        $firstVariantImage = null;
        foreach ($product->variants as $v) {
            if (isset($v['combo'])) {
                $isVariant = true;
                if (isset($v['price']) && $v['price'] > 0) {
                    $prices[] = $v['price'];
                }
                if (!$firstVariantImage && isset($v['image']) && !empty($v['image'])) {
                    $firstVariantImage = $v['image'];
                }
            }
        }
        
        if ($firstVariantImage) {
            $displayImage = $firstVariantImage;
        }
        
        if (count($prices) > 0) {
            $minPrice = min($prices);
            $maxPrice = max($prices);
            if ($minPrice != $maxPrice) {
                $hasMultiplePrices = true;
            } else {
                // If all variations have the same price, just show that price.
                $minPrice = $prices[0];
                $discountedPrice = $minPrice; // Ignore simple product discount calculation for variant if prices are overwritten, or we could apply discount to minPrice
            }
        }
    }
@endphp
<div class="col-6 col-sm-6 col-md-4 col-lg-3">
    <div class="prod-card">
        <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
            <div class="prod-img-wrap">
                @if ($hasDiscount)
                    @if ($product->discount_type === 'percent')
                        <span class="badge-new-arrival">{{ round($product->discount_value) }}% OFF</span>
                    @else
                        <span class="badge-new-arrival">৳{{ round($product->discount_value) }} OFF</span>
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
                        <span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }} - {{ number_format($maxPrice, 0) }}
                    @else
                        @if ($hasDiscount)
                            <span style="font-size: 1.2em;">৳</span> {{ number_format($discountedPrice, 0) }}
                            <span class="old"><span style="font-size: 1.2em;">৳</span> {{ number_format($minPrice, 0) }}</span>
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
                <a href="{{ route('product.details', $product->slug) }}"
                    class="btn btn-buy-now w-100 py-2 d-inline-flex align-items-center justify-content-center gap-1"
                    style="font-size: 11px; font-weight: 600; border-radius: 6px;"
                    title="Buy Now">
                    <i class="bi bi-lightning-fill"></i><span> Buy Now</span>
                </a>
            </div>
        </div>
    </div>
</div>
