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

                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="prod-product-img">
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
                <div class="prod-stars">
                    @php $avgRating = $product->average_rating; @endphp
                    @for ($s = 1; $s <= 5; $s++)
                        @if ($s <= floor($avgRating))
                            <i class="bi bi-star-fill star-filled"></i>
                        @elseif ($s - $avgRating < 1 && $s - $avgRating > 0)
                            <i class="bi bi-star-half star-filled"></i>
                        @else
                            <i class="bi bi-star star-empty"></i>
                        @endif
                    @endfor
                    @if ($product->reviews_count > 0)
                        <span class="prod-review-count">({{ $product->reviews_count }})</span>
                    @endif
                </div>
                <div class="p">
                    @if ($hasDiscount)
                        Tk {{ number_format($discountedPrice, 0) }}
                        <span class="old">Tk {{ number_format($product->price, 0) }}</span>
                    @else
                        Tk {{ number_format($product->price, 0) }}
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
