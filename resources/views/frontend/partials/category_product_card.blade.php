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
    $minPrice = $product->price;
    $maxPrice = $product->price;
    $hasMultiplePrices = false;

    if (!empty($product->variants) && is_array($product->variants)) {
        $prices = [];
        $firstVariantImage = null;
        foreach ($product->variants as $v) {
            if (isset($v['combo'])) {
                if (isset($v['price']) && $v['price'] > 0) $prices[] = $v['price'];
                if (!$firstVariantImage && !empty($v['image'])) $firstVariantImage = $v['image'];
            }
        }
        if ($firstVariantImage) $displayImage = $firstVariantImage;
        if (count($prices) > 0) {
            $minPrice = min($prices);
            $maxPrice = max($prices);
            if ($minPrice != $maxPrice) $hasMultiplePrices = true;
            else { $minPrice = $prices[0]; $discountedPrice = $minPrice; }
        }
    }
@endphp

<div class="rcat-card">

    {{-- Discount ribbon --}}
    @if ($hasDiscount)
        <div class="rcat-ribbon">
            @if ($product->discount_type === 'percent')
                {{ round($product->discount_value) }}% OFF
            @else
                ৳{{ round($product->discount_value) }} OFF
            @endif
        </div>
    @endif

    {{-- Image area --}}
    <a href="{{ route('product.details', $product->slug) }}" class="rcat-img-link">
        <div class="rcat-img-wrap">
            @if ($displayImage)
                <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}" class="rcat-img">
            @else
                <img src="https://placehold.co/260x220/f8f9fa/bbb?text={{ urlencode(Str::limit($product->name, 8, '')) }}" alt="{{ $product->name }}" class="rcat-img">
            @endif
        </div>
    </a>

    {{-- Card body --}}
    <div class="rcat-body">

        {{-- Name --}}
        <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">
            <div class="rcat-name">{{ Str::limit($product->name, 60) }}</div>
        </a>

        {{-- Stock badge --}}
        <div class="rcat-stock-row">
            @if ($product->stock > 0)
                <span class="rcat-in-stock"><i class="bi bi-check-circle-fill"></i> In Stock</span>
            @else
                <span class="rcat-out-stock"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
            @endif
        </div>

        {{-- Price --}}
        <div class="rcat-price-row">
            @if ($hasMultiplePrices)
                <span class="rcat-price"><span class="rcat-tk">৳</span> {{ number_format($minPrice, 0) }} – {{ number_format($maxPrice, 0) }}</span>
            @elseif($hasDiscount)
                <span class="rcat-price"><span class="rcat-tk">৳</span> {{ number_format($discountedPrice, 0) }}</span>
                <span class="rcat-price-old"><span class="rcat-tk-old">৳</span> {{ number_format($minPrice, 0) }}</span>
            @else
                <span class="rcat-price"><span class="rcat-tk">৳</span> {{ number_format($minPrice, 0) }}</span>
            @endif
        </div>

    </div>

    {{-- Footer button --}}
    <div class="rcat-footer">
        <a href="{{ route('product.details', $product->slug) }}" class="rcat-btn-cart">
            <i class="bi bi-cart-plus-fill me-1"></i> Add to Cart
        </a>
    </div>
</div>
