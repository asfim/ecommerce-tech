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
<div class="col-6 col-sm-6 col-md-4 col-lg-3">
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
