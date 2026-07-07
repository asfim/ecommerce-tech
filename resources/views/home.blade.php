@extends('layouts.app')

@section('title', 'eCommerce - Fashion Store')

@section('content')
<!-- Hero section -->
<div class="hero-sec">
  <div class="wrap">
    <div class="row g-3">
      <div class="col-4">
        <div class="brand-banner">
          @if(!empty($heroBanners[0]))
            <img src="{{ asset('storage/' . $heroBanners[0]) }}" alt="fashion model">
          @else
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80" alt="fashion model">
          @endif
          <div class="cap">Unleash<br>Your<br>Brand</div>
        </div>
      </div>
      <div class="col-4">
        <div class="season-banner">
          @if(!empty($heroBanners[1]))
            <img src="{{ asset('storage/' . $heroBanners[1]) }}" alt="end of season model" style="opacity:.5;">
          @else
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&q=80" alt="end of season model" style="opacity:.5;">
          @endif
          <div class="cap">
            <div class="small">🔥 End of Season</div>
            <h4 class="fw-bold">End of Season<br>Sale</h4>
            <p class="small">For limited time and stock is limited</p>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="hotcat-panel">
          <h6><i class="bi bi-fire text-danger"></i> Hot Categories</h6>
          <div class="row g-2 mt-1">
            @foreach($hotCategories as $cat)
              <div class="col-3 hotcat-item">
                @if($cat->image)
                  <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                @else
                  <img src="https://placehold.co/150x150/eee/aaa?text={{ urlencode(Str::limit($cat->name, 8, '')) }}" alt="{{ $cat->name }}">
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
              <div class="fs-item" style="min-width:33.333%;flex:0 0 33.333%;display:flex;align-items:center;gap:10px;padding:0 8px;">
                @if($fp->image)
                  <img src="{{ asset('storage/' . $fp->image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                @else
                  <img src="https://placehold.co/50x50/eee/aaa?text=No+Img" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
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
          <span class="arrow d-inline-flex" id="featuredPrev" style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i class="bi bi-chevron-left"></i></span>
          <span class="arrow d-inline-flex" id="featuredNext" style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i class="bi bi-chevron-right"></i></span>
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
          <div class="tcat-item" style="min-width: calc(16.666% - 12.5px); flex: 0 0 calc(16.666% - 12.5px); text-align: center;">
            @if($tc->image)
              <img src="{{ asset('storage/' . $tc->image) }}" alt="{{ $tc->name }}" style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
            @else
              <img src="https://placehold.co/74x74/eee/aaa?text={{ urlencode(Str::limit($tc->name, 8, '')) }}" alt="{{ $tc->name }}" style="width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px;">
            @endif
            <div class="name" style="font-size:11.5px;">{{ $tc->name }}</div>
          </div>
        @empty
          <div class="text-muted small px-2">No trending categories yet.</div>
        @endforelse
      </div>
    </div>
    <div class="d-flex gap-1 align-items-center">
      <span class="arrow d-inline-flex" id="trendingPrev" style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i class="bi bi-chevron-left"></i></span>
      <span class="arrow d-inline-flex" id="trendingNext" style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;align-items:center;justify-content:center;cursor:pointer;"><i class="bi bi-chevron-right"></i></span>
    </div>
  </div>

  <!-- Best selling / Today's deal -->
  <div class="row g-3 mb-4">
    <div class="col-9">
      <div class="bestselling-panel h-100">
        <div class="panel-title">Best Selling <span class="arrow"><i class="bi bi-chevron-right"></i></span></div>
        <div class="row g-2 px-3 pb-3">
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1592286130958-40dd3d2d5f0e?w=200&q=80"><div class="t">Shining Star Ladies Purse</div><div class="p">$449.00</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80"><div class="t">Apple MacBook Air 2022 with M2, 24GB Ram &amp; 512...</div><div class="p">$449.00</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80"><div class="t">Shining Star Ladies Purse</div><div class="p">$400.00</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=200&q=80"><div class="t">Apple MacBook Air 2022 with M2, 24GB Ram...</div><div class="p">$449.00</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=200&q=80"><div class="t">Shining Star Ladies Purse</div><div class="p">$449.00</div></div>
        </div>
      </div>
    </div>
    <div class="col-3">
      <div class="todaydeal-panel h-100">
        <div class="panel-title">Todays Deal <span class="arrow"><i class="bi bi-chevron-right"></i></span></div>
        <div class="text-center px-3 pb-3">
          <img src="https://images.unsplash.com/photo-1588058365548-9ae5966c1e77?w=200&q=80" style="width:100px;height:100px;object-fit:contain;">
          <div class="t small mt-2">Apple TV 4K Ethernet + WiFi</div>
          <div class="p">$111.00 <span class="old text-decoration-line-through text-muted">$500.00</span></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Promo 3 banners -->
  <div class="row g-3 mb-4">
    <div class="col-4">
      <div class="promo3">
        @if(!empty($bestSellingBanners[0]))
          <img src="{{ asset('storage/' . $bestSellingBanners[0]) }}">
        @else
          <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&q=80">
        @endif
      </div>
    </div>
    <div class="col-4">
      <div class="promo3">
        @if(!empty($bestSellingBanners[1]))
          <img src="{{ asset('storage/' . $bestSellingBanners[1]) }}">
        @else
          <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&q=80">
        @endif
      </div>
    </div>
    <div class="col-4">
      <div class="promo3">
        @if(!empty($bestSellingBanners[2]))
          <img src="{{ asset('storage/' . $bestSellingBanners[2]) }}">
        @else
          <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&q=80">
        @endif
      </div>
    </div>
  </div>

  <!-- Auction -->
  <div class="row g-3 mb-4">
    <div class="col-4">
      <div class="auction-panel">
        <span class="cap">AUCTION</span>
        <div class="badge bg-dark mt-auto mb-2">Just Bid It !</div>
        <img src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=300&q=80">
      </div>
    </div>
    <div class="col-8">
      <div class="auction-list-panel p-3">
        <div class="d-flex justify-content-between mb-2"><b>New Arrival</b><small class="text-muted">Products (109)</small></div>
        <div class="row g-2">
          <div class="col-6">
            <div class="auction-item">
              <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&q=80">
              <div class="flex-grow-1"><div class="t">Apple 2024 MacBook Air 15-inch Laptop with M3 chip 16.3-inch</div><div class="bid"><b>$1,499.00</b></div><button class="btn-bid mt-1">Buy Now</button></div>
            </div>
          </div>
          <div class="col-6">
            <div class="auction-item">
              <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=100&q=80">
              <div class="flex-grow-1"><div class="t">Flash Furniture Whitney Mid-Back Desk Chair - Black Leatherso...</div><div class="bid"><b>$132.00</b></div><button class="btn-bid mt-1">Buy Now</button></div>
            </div>
          </div>
          <div class="col-6">
            <div class="auction-item">
              <img src="https://images.unsplash.com/photo-1591370874773-6702e8f12fd8?w=100&q=80">
              <div class="flex-grow-1"><div class="t">Graco Modes Nest Travel System, Includes Baby Stroller with Height</div><div class="bid"><b>$399.00</b></div><button class="btn-bid mt-1">Buy Now</button></div>
            </div>
          </div>
          <div class="col-6">
            <div class="auction-item">
              <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?w=100&q=80">
              <div class="flex-grow-1"><div class="t">Rolmium 32LB/52LB Adjustable Dumbbells, 5 Weight Options</div><div class="bid"><b>$128.00</b></div><button class="btn-bid mt-1">Buy Now</button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Classified Ads -->
<div class="classified-sec">
  <div class="wrap">
    <div class="d-flex justify-content-between mb-3">
      <div><b>Classified Ads</b><div class="text-muted small">Products (108)</div></div>
      <span class="arrow" style="width:26px;height:26px;border-radius:50%;background:#111;color:#fff;display:flex;align-items:center;justify-content:center;"><i class="bi bi-chevron-right"></i></span>
    </div>
    <div class="row g-3">
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=200&q=80">
        <div class="t small">Cinca Urban-Adult Retro Graphic Clogs, 5lp</div>
        <div class="p">$39.28</div>
        <span class="badge-used">Used</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1517341860632-4ffdbb63d5b0?w=200&q=80">
        <div class="t small">Timex Women's TW2R98700 Stretch</div>
        <div class="p">$39.28</div>
        <span class="badge-used">Used</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=200&q=80">
        <div class="t small">Adikanra Papell Women's Halter Art Deco Beaded</div>
        <div class="p">$39.28</div>
        <span class="badge-new">New</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1620064916298-2f8c8ba9f1a8?w=200&q=80">
        <div class="t small">Acer Aspire 3 A315-74P-R7VH Slim Laptop, 15.6"</div>
        <div class="p">$39.28</div>
        <span class="badge-used">Used</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1547082299-de196ea013d6?w=200&q=80">
        <div class="t small">Minolta Pro Shot 16 Mega Pixel HD Digital Camera</div>
        <div class="p">$39.28</div>
        <span class="badge-new">New</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=200&q=80">
        <div class="t small">Acer Aspire 3 A315-74P-R7VH Slim Laptop, 15.6"</div>
        <div class="p">$39.28</div>
        <span class="badge-used">Used</span>
      </div>
      <div class="col classified-card">
        <img src="https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=200&q=80">
        <div class="t small">Minolta Pro Shot 16 Mega Pixel HD Digital Camera</div>
        <div class="p">$39.28</div>
        <span class="badge-new">New</span>
      </div>
    </div>
  </div>
</div>

<div class="wrap">
  <!-- Preorder -->
  <div class="preorder-panel my-4">
    <div class="panel-title px-0">Newest Preorder Products <span class="arrow"><i class="bi bi-chevron-right"></i></span></div>
    <div class="row g-3">
      <div class="col-3">
        <div class="preorder-hero">
          <span class="badge-limit">Don't Miss Out</span>
          <h5 class="fw-bold mt-3">Limited Pre-Orders<br>Available</h5>
          <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&q=80" class="img-fluid rounded mt-2">
        </div>
      </div>
      <div class="col-9">
        <div class="row g-3">
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=150&q=80"><div class="t">Shining Star Ladies Purse</div><div class="rating">★★★★★</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=150&q=80"><div class="t">Apple MacBook Air 2022 with M2, 24GB Ram &amp;...</div><div class="rating">★★★★★</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1610557892470-55d587cef9c4?w=150&q=80"><div class="t">Shining Star Ladies Purse</div><div class="rating">★★★★★</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=150&q=80"><div class="t">Apple MacBook Air 2022 with M2, 24GB Ram...</div><div class="rating">★★★★★</div></div>
          <div class="col mini-prod"><img src="https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=150&q=80"><div class="t">Shining Star Ladies Purse</div><div class="rating">★★★★★</div></div>
        </div>
      </div>
    </div>
  </div>

  {{-- <!-- 1000s of shops banner -->
  <div class="shops-banner">
    <div>
      <h3>1000s of Shops with their best for You</h3>
      <p class="text-muted mb-3" style="max-width:420px;">All of our sellers are passionate to bring new trends and quality products</p>
      <a href="#" class="btn btn-primary rounded-pill px-4">Click to visit the best</a>
    </div>
    <div class="d-flex gap-2">
      <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=200&q=80">
      <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=200&q=80">
      <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=200&q=80">
    </div>
  </div> --}}

  <!-- Product grid -->
  <div class="row g-3 mb-3">
    <div class="col-2">
      <div class="prod-card border-dark">
        <span class="disc-badge bg-danger">-10%</span>
        <span class="badge bg-primary position-absolute" style="top:8px;right:8px;font-size:9px;">Limited Stock</span>
        <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=200&q=80">
        <div class="t">Dressystar Women Floral Lace Short Bridesmaid...</div>
        <div class="p">$449.00 <span class="old">$549.00</span></div>
      </div>
    </div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80"><div class="t">Lace Women Dress Elegant Ladies Swallow...</div><div class="p">$1,399.00</div></div></div>
    <div class="col-2"><div class="prod-card"><span class="disc-badge bg-danger">-10%</span><img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80"><div class="t">Jackie 1961 Small Shoulder Bag by Gucci</div><div class="p">$2,600.00</div></div></div>
    <div class="col-2"><div class="prod-card"><span class="disc-badge" style="background:#e91e8c;">Special Price</span><img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200&q=80"><div class="t">New Design Luxury Acetate Sun Glasses</div><div class="p">$11.00</div></div></div>
    <div class="col-2"><div class="prod-card"><span class="disc-badge bg-danger">-10%</span><img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80"><div class="t">Dressystar Women Floral Lace Short Bridesmaid...</div><div class="p">$449.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=200&q=80"><div class="t">Time and Tru Women's Ankle Boots</div><div class="p">$39.00</div></div></div>

    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=200&q=80&flip=1"><div class="t">Dressystar Women Floral Lace Short Bridesmaid...</div><div class="p">$449.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1587467512961-120760940315?w=200&q=80"><div class="t">Lace Women Dress Elegant Ladies Swallow...</div><div class="p">$1,399.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=200&q=80"><div class="t">Jackie 1961 Small Shoulder Bag by Gucci</div><div class="p">$2,600.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=200&q=80"><div class="t">New Design Luxury Acetate Sun Glasses</div><div class="p">$11.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1449505078219-8e56d3a99b91?w=200&q=80"><div class="t">Dressystar Women Floral Lace Short Bridesmaid...</div><div class="p">$449.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&q=80"><div class="t">Time and Tru Women's Ankle Boots</div><div class="p">$39.00</div></div></div>

    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=200&q=80"><div class="t">Minolta Pro Shot 16 Mega Pixel HD Digital Camera</div><div class="p">$449.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=200&q=80"><div class="t">Lace Women Dress Elegant Ladies Swallow...</div><div class="p">$1,399.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80"><div class="t">Jackie 1961 Small Shoulder Bag by Gucci</div><div class="p">$2,600.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200&q=80"><div class="t">New Design Luxury Acetate Sun Glasses</div><div class="p">$11.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&q=80"><div class="t">Dressystar Women Floral Lace Short Bridesmaid...</div><div class="p">$449.00</div></div></div>
    <div class="col-2"><div class="prod-card"><img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=200&q=80"><div class="t">Time and Tru Women's Ankle Boots</div><div class="p">$39.00</div></div></div>
  </div>
  <div class="text-center mb-4">
    <a href="#" class="btn btn-outline-dark px-5">Load more</a>
  </div>
</div>

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
</script>
@endpush
@endsection
