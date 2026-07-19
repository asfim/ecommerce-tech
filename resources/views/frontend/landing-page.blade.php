<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $landingPage->meta_title ?? $product->name }}</title>
  <!-- Bootstrap 5 + Icons + Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    html, body {
      font-family: 'Inter', sans-serif;
      background: #f9fafc;
      color: #0b1a2a;
      scroll-behavior: smooth;
      overflow-x: hidden;
      width: 100%;
      max-width: 100%;
    }
    section {
      overflow-x: hidden;
    }
    :root {
      --accent: #b33e0f;
      --accent-light: #e95f2b;
      --dark-bg: #0a1a2b;
      --card-border: 1px solid rgba(0,0,0,0.06);
      --shadow-soft: 0 12px 30px -10px rgba(0,0,0,0.08);
    }
    .btn-accent {
      background: linear-gradient(145deg, #b33e0f, #942f08);
      border: none;
      padding: 14px 36px;
      border-radius: 60px;
      font-weight: 700;
      color: #fff;
      box-shadow: 0 10px 22px -8px rgba(179,62,15,0.4);
      transition: all 0.25s ease;
    }
    .btn-accent:hover {
      transform: scale(1.03) translateY(-3px);
      box-shadow: 0 18px 30px -10px rgba(179,62,15,0.6);
      color: #fff;
    }
    .btn-outline-premium {
      background: transparent;
      border: 2px solid rgba(255,255,255,0.3);
      backdrop-filter: blur(4px);
      padding: 12px 28px;
      border-radius: 60px;
      font-weight: 600;
      color: #fff;
      transition: 0.25s;
    }
    .btn-outline-premium:hover {
      background: rgba(255,255,255,0.08);
      border-color: #fff;
      color: #fff;
      transform: translateY(-2px);
    }
    .premium-card {
      background: #ffffff;
      border-radius: 40px;
      border: var(--card-border);
      box-shadow: var(--shadow-soft);
      transition: all 0.3s cubic-bezier(0.2,0.9,0.4,1.1);
      overflow: hidden;
    }
    .premium-card:hover {
      transform: translateY(-8px);
      border-color: rgba(179,62,15,0.25);
      box-shadow: 0 24px 48px -16px rgba(0,0,0,0.15);
    }
    .hero-glow {
      background: radial-gradient(circle at 80% 20%, rgba(255,215,140,0.08) 0%, transparent 50%),
                  radial-gradient(circle at 20% 80%, rgba(179,62,15,0.15), transparent 60%),
                  linear-gradient(135deg, #082b1a 0%, #0f462b 45%, #1c5e3a 100%);
      border-bottom: 4px solid #b33e0f;
    }
    .floating-soft {
      animation: floatSoft 5s ease-in-out infinite;
    }
    @keyframes floatSoft {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    .timer-glow {
      background: rgba(10, 26, 43, 0.85);
      backdrop-filter: blur(14px);
      border-radius: 80px;
      padding: 0.8rem 1.8rem;
      border: 1px solid rgba(255,215,170,0.2);
      display: inline-flex;
      gap: 1.2rem;
      flex-wrap: wrap;
      justify-content: center;
      box-shadow: 0 0 0 0 rgba(179,62,15,0.25);
      animation: pulseBorder 2.2s infinite;
    }
    @keyframes pulseBorder {
      0% { box-shadow: 0 0 0 0 rgba(179,62,15,0.15); }
      70% { box-shadow: 0 0 0 16px rgba(179,62,15,0); }
      100% { box-shadow: 0 0 0 0 rgba(179,62,15,0); }
    }
    .time-block {
      background: #fff;
      border-radius: 28px;
      padding: 0.4rem 1rem;
      min-width: 72px;
      text-align: center;
      border: 1px solid #e4e9f0;
    }
    .time-number { font-weight: 800; font-size: 1.9rem; color: #0b1a2a; letter-spacing: 1px; }
    .price-show {
      background: #fff;
      border-radius: 48px;
      padding: 2rem 1.8rem;
      border: 1px solid #eef2f8;
      box-shadow: 0 20px 40px -14px rgba(0,0,0,0.06);
      position: relative;
      overflow: hidden;
    }
    .price-show::after {
      content: "";
      position: absolute;
      top: 0; left: 0; width: 6px; height: 100%;
      background: linear-gradient(180deg, #b33e0f, #f59e0b);
    }
    .shine-slide {
      position: absolute;
      top: -30%; left: -40%;
      width: 60%;
      height: 200%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
      transform: rotate(25deg);
      animation: shineSlide 7s infinite;
      pointer-events: none;
    }
    @keyframes shineSlide {
      0% { left: -60%; }
      25% { left: 120%; }
      100% { left: 120%; }
    }
    .form-premium {
      background: #fff;
      border-radius: 40px;
      border: 1px solid #eef2f8;
      padding: 2rem;
      box-shadow: 0 16px 32px -12px rgba(0,0,0,0.04);
    }
    .form-control-premium {
      border-radius: 60px;
      padding: 14px 22px;
      border: 1.5px solid #e2e8f0;
      background: #fff;
      transition: 0.2s;
    }
    .form-control-premium:focus {
      border-color: #b33e0f;
      box-shadow: 0 0 0 5px rgba(179,62,15,0.12);
      transform: translateX(6px);
    }
    .trust-pill {
      background: #fff;
      border: 1px solid #eaeef4;
      border-radius: 100px;
      padding: 6px 18px;
      font-weight: 500;
      font-size: 0.8rem;
      transition: 0.2s;
    }
    .trust-pill:hover {
      border-color: #b33e0f;
      background: #fef6f0;
      transform: translateY(-3px);
    }
    .wa-sticky {
      position: fixed;
      bottom: 24px; right: 24px;
      z-index: 1050;
      background: #25D366;
      border-radius: 60px;
      padding: 10px 22px;
      border: 1px solid rgba(255,255,255,0.3);
      box-shadow: 0 12px 28px rgba(0,0,0,0.18);
      animation: waBounce 2.4s infinite;
      transition: 0.2s;
    }
    .wa-sticky:hover { transform: scale(1.06); animation: none; }
    @keyframes waBounce {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    .img-soft-rounded {
      border-radius: 32px;
      border: 1px solid rgba(255,255,255,0.15);
      box-shadow: 0 20px 35px -14px rgba(0,0,0,0.3);
      transition: 0.4s ease;
    }
    .img-soft-rounded:hover { transform: scale(1.02); }
    .bulk-quantity-selector {
      background: #f2f5f9;
      border-radius: 60px;
      padding: 0.4rem 1.2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
      border: 1px solid #e2e8f0;
    }
    .bulk-quantity-selector button {
      background: transparent;
      border: none;
      font-size: 1.6rem;
      font-weight: 300;
      color: #0b1a2a;
      padding: 0 8px;
      transition: 0.15s;
    }
    .bulk-quantity-selector button:hover { color: #b33e0f; transform: scale(1.2); }
    .bulk-quantity-selector span { font-weight: 700; font-size: 1.5rem; min-width: 40px; text-align: center; }
    .delivery-charge-badge {
      background: #eef2f8;
      border-radius: 60px;
      padding: 4px 18px;
      font-weight: 500;
      font-size: 0.9rem;
    }
    @media (max-width: 768px) {
      .hero-glow { text-align: center; }
      .hero-glow h1 { font-size: 2.2rem !important; line-height: 1.25; }
      .hero-glow p.lead { font-size: 1.05rem !important; }
      .hero-glow .d-flex.flex-wrap { justify-content: center; }
      .hero-glow .mt-4.d-flex { justify-content: center; flex-wrap: wrap; gap: 10px; }
      .hero-glow img { max-width: 100% !important; height: auto !important; max-height: 320px !important; margin-top: 20px; }
      
      .time-number { font-size: 1.4rem; }
      .time-block { min-width: 62px; padding: 0.2rem 0.6rem; }
      .timer-glow { padding: 0.6rem 1rem; gap: 0.6rem; }

      .price-show { padding: 1.5rem 1rem !important; border-radius: 32px !important; }
      .new-price { font-size: 2.6rem !important; }
      .old-price { font-size: 1.4rem !important; }

      h2.text-center { font-size: 1.8rem !important; }
      .premium-card { border-radius: 28px !important; padding: 1.5rem 1rem !important; }
      
      .form-premium { padding: 1.2rem 1rem !important; border-radius: 28px !important; }
      .form-premium h3 { font-size: 1.5rem !important; }
      .form-control-premium { padding: 10px 18px !important; border-radius: 30px; }
      
      .wa-sticky { bottom: 16px !important; right: 16px !important; padding: 8px 16px !important; font-size: 0.9rem !important; }
    }
  </style>
</head>
<body>

@php
  $oldPrice = $landingPage->old_price ?? ($product->price * 1.5);
  $newPrice = $landingPage->new_price ?? $product->price;
  $sizes = [];
  if (!empty($product->variants)) {
      foreach ($product->variants as $variant) {
          if (strtolower($variant['label'] ?? '') === 'size') {
              $sizes[] = $variant['value'];
          }
      }
  }
  if (empty($sizes)) {
      $sizes = ['S', 'M', 'L', 'XL', '2XL'];
  }
@endphp

<!-- ======== HERO 1 ======== -->
<section class="hero-glow py-5 text-white">
  <div class="container py-3">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
        <div class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background:rgba(0,0,0,0.25); backdrop-filter:blur(4px); border:1px solid rgba(255,215,170,0.4);">
          <span class="small fw-semibold"><i class="fas fa-crown me-1 text-warning"></i> {{ $landingPage->tagline ?? 'প্রিমিয়াম কালেকশন ২০২৬' }}</span>
        </div>
        <h1 class="display-4 fw-bold" style="letter-spacing:-0.02em; text-shadow:0 2px 6px rgba(0,0,0,0.2);">
          {{ $landingPage->heading ?? '৫টি ভিন্ন ডিজাইন এক প্যাকেজে' }}
        </h1>
        <p class="lead mt-3" style="color:rgba(255,255,245,0.85);">{{ $landingPage->description ?? 'প্রিমিয়াম কম্বড কটন • প্রতিটি টি-শার্ট আলাদা স্টাইল • সীমিত সংস্করণ' }}</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="#order" class="btn btn-accent px-5 py-3"><i class="fas fa-shopping-bag me-2"></i> পুরো প্যাকেজ অর্ডার</a>
          <a href="#features" class="btn btn-outline-premium px-4 py-3"><i class="fas fa-info-circle me-2"></i> ডিটেইলস</a>
        </div>
        <div class="mt-4 d-flex gap-4 small" style="color:rgba(255,255,240,0.8);">
          <span><i class="fas fa-check-circle text-success"></i> {{ $landingPage->delivery_text ?? 'ফ্রি এক্সপ্রেস ডেলিভারি' }}</span>
          <span><i class="fas fa-undo-alt text-warning"></i> {{ $landingPage->return_text ?? '৩০ দিন রিটার্ন' }}</span>
        </div>
      </div>
      <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="150">
        @php
          $heroImage = $landingPage->image ? asset('storage/' . $landingPage->image) : ($product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/eee/aaa?text=No+Img');
        @endphp
        <img src="{{ $heroImage }}" alt="{{ $product->name }}" class="img-fluid img-soft-rounded floating-soft" style="max-height:420px; width:auto; object-fit: cover;">
      </div>
    </div>
  </div>
</section>

<!-- ======== TIMER + TRUST BADGES ======== -->
<section class="py-4">
  <div class="container text-center">
    <div class="timer-glow mx-auto" data-aos="zoom-in" data-aos-duration="700">
      <div class="time-block"><div class="time-number" id="hours">00</div><div class="small text-muted">ঘণ্টা</div></div>
      <div class="time-block"><div class="time-number" id="minutes">00</div><div class="small text-muted">মিনিট</div></div>
      <div class="time-block"><div class="time-number" id="seconds">00</div><div class="small text-muted">সেকেন্ড</div></div>
    </div>
    <div class="d-flex justify-content-center gap-3 flex-wrap mt-5" data-aos="fade-up" data-aos-delay="250">
      <span class="trust-pill"><i class="fas fa-shield-alt text-primary me-1"></i> ক্রেতা সুরক্ষা</span>
      <span class="trust-pill"><i class="fas fa-truck-fast text-success me-1"></i> ক্যাশ অন ডেলিভারি</span>
      <span class="trust-pill"><i class="fas fa-credit-card me-1"></i> নিরাপদ পেমেন্ট</span>
      <span class="trust-pill"><i class="fas fa-star text-warning me-1"></i> ৫★ রেটিং</span>
    </div>
  </div>
</section>

<!-- ======== PRICE SHOWCASE with BULK QUANTITY ======== -->
<section class="py-4">
  <div class="container">
    <div class="price-show text-center" data-aos="flip-up" data-aos-duration="1000">
      <div class="shine-slide"></div>
      <div class="d-inline-block mb-3 px-4 py-1 rounded-pill" style="background:#b33e0f; color:#fff; font-weight:600;">
        <i class="fas fa-bolt me-1"></i> {{ $landingPage->offer_text ?? 'কম্বো অফার – ৫টি টি-শার্ট' }}
      </div>
      
      <!-- QUANTITY SELECTOR (বাল্ক) -->
      <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap my-3">
        <span class="fw-semibold">প্যাকেজ সংখ্যা :</span>
        <div class="bulk-quantity-selector">
          <button id="qtyDown" aria-label="কমান">−</button>
          <span id="qtyDisplay">১</span>
          <button id="qtyUp" aria-label="বাড়ান">+</button>
        </div>
        <span class="text-muted small">(সর্বোচ্চ ৫ প্যাকেজ)</span>
      </div>

      <!-- PRICE & DISCOUNT DYNAMIC -->
      <div class="d-flex justify-content-center align-items-baseline gap-3 flex-wrap">
        <span class="old-price" id="oldPriceDisplay" style="font-size:1.8rem; font-weight:500; color:#94a3b8; text-decoration:line-through;">৳{{ number_format($oldPrice, 0, '.', '') }}</span>
        <span class="new-price" id="newPriceDisplay" style="font-size:4rem; font-weight:800; background:linear-gradient(135deg,#b33e0f,#ea580c); background-clip:text; -webkit-background-clip:text; color:transparent;">৳{{ number_format($newPrice, 0, '.', '') }}</span>
        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25" id="savedAmount">{{ $landingPage->discount_text ?? 'বাঁচাচ্ছেন ৳' . number_format($oldPrice - $newPrice, 0, '.', '') }}</span>
      </div>

      <p class="text-muted mt-3"><i class="fas fa-box-open me-1"></i> <span id="stockMsg">{{ $landingPage->stock_text ?? 'মাত্র ২৫টি প্যাকেজ বাকি' }}</span></p>
      <a href="#order" class="btn btn-accent btn-lg px-5 mt-2"><i class="fas fa-lock me-2"></i> <span id="ctaPrice">৳{{ number_format($newPrice, 0, '.', '') }}</span> এ অর্ডার করুন</a>
    </div>
  </div>
</section>

<!-- ======== 5 PRODUCT FEATURES (GRID) ======== -->
@if(!empty($landingPage->features))
<section id="features" class="py-5">
  <div class="container">
    <h2 class="fw-bold text-center mb-5" data-aos="fade-up"> <span style="color:#b33e0f;">৫টি</span> ভিন্ন ডিজাইন, একেকটি অনন্য</h2>
    <div class="row g-4">
      @foreach($landingPage->features as $feature)
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="premium-card p-4 text-center h-100 d-flex flex-column align-items-center">
          <div class="rounded-circle bg-light p-3 mb-3" style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;border:2px solid #b33e0f20;"><i class="{{ $feature['icon'] ?? 'fas fa-tshirt' }} fa-3x" style="color:#b33e0f;"></i></div>
          <h5 class="fw-bold">{{ $feature['title'] ?? '' }}</h5>
          <p class="small text-muted">{{ $feature['description'] ?? '' }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ======== ORDER FORM with DELIVERY LOCATION ======== -->
<section id="order" class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="form-premium" data-aos="fade-up" data-aos-duration="1000">
          <h3 class="fw-bold text-center mb-4"><i class="fas fa-pen-fancy me-2" style="color:#b33e0f;"></i> আপনার অর্ডার কনফর্ম করুন</h3>
          <form id="orderForm">
            <div class="mb-3">
              <label class="form-label fw-semibold">আপনার নাম *</label>
              <input type="text" id="fullName" class="form-control form-control-premium" placeholder="যেমন: আব্দুল্লাহ" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">ফোন নম্বর *</label>
              <input type="tel" id="phone" class="form-control form-control-premium" placeholder="০১৭xxxxxxxx" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">পূর্ণ ঠিকানা (বাড়ি, রোড, থানা) *</label>
              <input type="text" id="address" class="form-control form-control-premium" placeholder="বাড়ি নং, রোড, থানা" required>
            </div>
            
            <!-- ===== DELIVERY LOCATION (জেলা) ===== -->
            <div class="mb-3">
              <label class="form-label fw-semibold">ডেলিভারি লোকেশন (জেলা) *</label>
              <select id="deliveryDistrict" class="form-select form-control-premium" required>
                <option value="">-- জেলা নির্বাচন করুন --</option>
                <option value="ঢাকা">ঢাকা</option>
                <option value="চট্টগ্রাম">চট্টগ্রাম</option>
                <option value="রাজশাহী">রাজশাহী</option>
                <option value="খুলনা">খুলনা</option>
                <option value="বরিশাল">বরিশাল</option>
                <option value="সিলেট">সিলেট</option>
                <option value="রংপুর">রংপুর</option>
                <option value="ময়মনসিংহ">ময়মনসিংহ</option>
                <option value="কুমিল্লা">কুমিল্লা</option>
                <option value="নারায়ণগঞ্জ">নারায়ণগঞ্জ</option>
                <option value="গাজীপুর">গাজীপুর</option>
                <option value="নোয়াখালী">নোয়াখালী</option>
                <option value="বগুড়া">বগুড়া</option>
                <option value="পাবনা">পাবনা</option>
                <option value="যশোর">যশোর</option>
                <option value="কুষ্টিয়া">কুষ্টিয়া</option>
                <option value="ফریدপুর">ফریدপুর</option>
                <option value="সাতক্ষীরা">সাতক্ষীরা</option>
                <option value="ঝালকাঠি">ঝালকাঠি</option>
                <option value="পটুয়াখালী">পটুয়াখালী</option>
                <option value="বান্দরবান">বান্দরবান</option>
                <option value="রাঙ্গামাটি">রাঙ্গামাটি</option>
              </select>
              <div class="form-text">আমরা সারা বাংলাদেশে ডেলিভারি দেই।</div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">কত প্যাকেজ অর্ডার করছেন? (প্রতিটি প্যাকেজে ৫টি টি-শার্ট)</label>
              <input type="number" id="orderQty" class="form-control form-control-premium" value="1" min="1" max="5" step="1">
              <div class="form-text">সর্বোচ্চ ৫ প্যাকেজ (মোট ২৫ টি-শার্ট)</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">সাইজ (বিঃদ্রঃ ৫টি সাইজ আলাদা হতে পারে)</label>
              <select name="size" class="form-select form-control-premium">
                @foreach($sizes as $size)
                  <option value="{{ $size }}" {{ $loop->iteration == 2 ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
              </select>
            </div>

            <!-- ডেলিভারি চার্জ দেখানো হবে -->
            <div class="mb-3 text-center">
              <span class="delivery-charge-badge"><i class="fas fa-truck me-1"></i> ডেলিভারি চার্জ: <span id="deliveryChargeDisplay">ফ্রি</span></span>
              <span class="ms-2 delivery-charge-badge"><i class="fas fa-map-pin me-1"></i> লোকেশন: <span id="selectedDistrictDisplay">নির্বাচন করুন</span></span>
            </div>

            <button type="submit" class="btn btn-accent w-100 py-3"><i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন (Cash on Delivery)</button>
            <div id="orderSuccessMsg" class="alert alert-success mt-4 d-none text-center" role="alert">
              <i class="fas fa-check-circle fa-2x me-2"></i> <strong>অর্ডার সফল!</strong> আমাদের টিম ২৪ ঘণ্টার মধ্যে কনফর্ম করবে।
            </div>
          </form>
          <p class="text-muted text-center mt-3 small"><i class="fas fa-lock me-1"></i> আপনার তথ্য নিরাপদ, শুধু ডেলিভারির জন্য ব্যবহার করা হবে</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======== BONUS: 5TH SECTION – TESTIMONIAL / TRUST ======== -->
@if(!empty($landingPage->testimonials))
<section class="py-5 bg-light">
  <div class="container text-center">
    <h3 class="fw-bold mb-4" data-aos="fade-right">ক্রেতাদের মতামত</h3>
    <div class="row g-4">
      @foreach($landingPage->testimonials as $t)
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="100">
        <div class="premium-card p-4 h-100 d-flex flex-column">
          <div class="mb-2">
            @php
              $stars = (float)($t['rating'] ?? 5);
              $fullStars = floor($stars);
              $halfStar = $stars - $fullStars >= 0.5;
            @endphp
            @for($s = 0; $s < $fullStars; $s++)
              <i class="fas fa-star text-warning"></i>
            @endfor
            @if($halfStar)
              <i class="fas fa-star-half-alt text-warning"></i>
            @endif
          </div>
          <p class="mt-2">"{{ $t['text'] ?? '' }}"</p>
          <small class="text-muted">– {{ $t['author'] ?? '' }}</small>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ======== STICKY WHATSAPP ======== -->
@if($landingPage->whatsapp_number)
<div class="wa-sticky">
  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingPage->whatsapp_number) }}?text={{ urlencode($landingPage->whatsapp_text ?? '') }}" target="_blank" class="text-white text-decoration-none d-flex align-items-center gap-2">
    <i class="fab fa-whatsapp fa-xl"></i> <span>WhatsApp Order</span>
  </a>
</div>
@endif

<!-- ======== SCRIPTS ======== -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 900, easing: 'ease-out-quad' });

  // ----- DELIVERY LOCATION & CHARGE LOGIC -----
  (function() {
    const districtSelect = document.getElementById('deliveryDistrict');
    const chargeDisplay = document.getElementById('deliveryChargeDisplay');
    const districtDisplay = document.getElementById('selectedDistrictDisplay');

    // চার্জ নির্ধারণ: ঢাকা ও চট্টগ্রাম ফ্রি, বাকি ৫০ টাকা
    function updateDeliveryCharge() {
      const selected = districtSelect.value;
      districtDisplay.innerText = selected || 'নির্বাচন করুন';
      if (selected === 'ঢাকা' || selected === 'চট্টগ্রাম') {
        chargeDisplay.innerText = 'ফ্রি';
        chargeDisplay.style.color = '#0a7e3b';
      } else if (selected) {
        chargeDisplay.innerText = '৳৫০';
        chargeDisplay.style.color = '#b33e0f';
      } else {
        chargeDisplay.innerText = 'ফ্রি';
        chargeDisplay.style.color = '#0a7e3b';
      }
    }

    districtSelect.addEventListener('change', updateDeliveryCharge);
    // initial call
    updateDeliveryCharge();
  })();

  // ----- BULK QUANTITY LOGIC (price update) -----
  (function(){
    const basePrice = {{ $newPrice }};        // 1 প্যাকেজ
    const baseOldPrice = {{ $oldPrice }};
    const maxQty = 5;
    let currentQty = 1;

    const qtyDisplay = document.getElementById('qtyDisplay');
    const oldPriceSpan = document.getElementById('oldPriceDisplay');
    const newPriceSpan = document.getElementById('newPriceDisplay');
    const savedSpan = document.getElementById('savedAmount');
    const ctaPrice = document.getElementById('ctaPrice');
    const stockMsg = document.getElementById('stockMsg');
    const orderQtyInput = document.getElementById('orderQty');

    function updateUI() {
      const qty = currentQty;
      const totalPrice = basePrice * qty;
      const totalOld = baseOldPrice * qty;
      const saved = totalOld - totalPrice;

      qtyDisplay.innerText = qty;
      oldPriceSpan.innerText = '৳' + totalPriceFormat(totalOld);
      newPriceSpan.innerText = '৳' + totalPriceFormat(totalPrice);
      savedSpan.innerText = 'বাঁচাচ্ছেন ৳' + totalPriceFormat(saved);
      ctaPrice.innerText = '৳' + totalPriceFormat(totalPrice);
      orderQtyInput.value = qty;

      // stock message dynamic
      const remaining = 25 - (qty - 1) * 5;
      if (remaining > 0) {
        stockMsg.innerText = `মাত্র ${totalPriceFormat(remaining)}টি প্যাকেজ বাকি (${totalPriceFormat(remaining*5)}টি টি-শার্ট)`;
      } else {
        stockMsg.innerText = 'শেষ মুহূর্ত! দ্রুত অর্ডার করুন';
      }
    }

    function totalPriceFormat(num) {
      // Format number to Bengali digits
      const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
      return String(num).replace(/[0-9]/g, function(w){
        return banglaDigits[w];
      });
    }

    document.getElementById('qtyUp').addEventListener('click', function() {
      if (currentQty < maxQty) { currentQty++; updateUI(); }
    });
    document.getElementById('qtyDown').addEventListener('click', function() {
      if (currentQty > 1) { currentQty--; updateUI(); }
    });

    orderQtyInput.addEventListener('change', function() {
      let val = parseInt(this.value) || 1;
      if (val < 1) val = 1;
      if (val > maxQty) val = maxQty;
      currentQty = val;
      updateUI();
    });

    updateUI();
  })();

  // ----- TIMER (9 hours) -----
  (function(){
    let expiry = localStorage.getItem("premium_five_offer_end");
    if (!expiry) {
      expiry = Date.now() + (9 * 60 * 60 * 1000);
      localStorage.setItem("premium_five_offer_end", expiry);
    } else { expiry = parseInt(expiry); }
    function update() {
      let rem = Math.max(0, expiry - Date.now());
      document.getElementById("hours").innerText   = String(Math.floor(rem / 3600000)).padStart(2,'0');
      document.getElementById("minutes").innerText = String(Math.floor((rem % 3600000) / 60000)).padStart(2,'0');
      document.getElementById("seconds").innerText = String(Math.floor((rem % 60000) / 1000)).padStart(2,'0');
    }
    update();
    setInterval(update, 1000);
  })();

  // ----- ORDER FORM HANDLER -----
  document.getElementById("orderForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const name = document.getElementById("fullName").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const address = document.getElementById("address").value.trim();
    const district = document.getElementById("deliveryDistrict").value;
    const qty = parseInt(document.getElementById("orderQty").value) || 1;
    const size = document.querySelector('select[name="size"]').value;

    if (!name || !phone || !address || !district) {
      alert("দয়া করে নাম, ফোন, ঠিকানা ও ডেলিভারি জেলা নির্বাচন করুন।");
      return;
    }
    if (phone.length < 9) {
      alert("সঠিক ফোন নম্বর দিন (কমপক্ষে ৯ ডিজিট)।");
      return;
    }

    // Determine shipping method & cost based on delivery district selection
    let shippingMethod = 'outside_dhaka';
    let shippingCost = 50;
    if (district === 'ঢাকা' || district === 'চট্টগ্রাম') {
      shippingMethod = 'inside_dhaka';
      shippingCost = 0;
    }

    const price = {{ $newPrice }};
    const subtotal = price * qty;
    const total = subtotal + shippingCost;

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> অর্ডার সম্পন্ন হচ্ছে...';

    fetch("{{ route('order.store') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        customer_name: name,
        customer_phone: phone,
        customer_address: address + ', ' + district,
        shipping_method: shippingMethod,
        shipping_cost: shippingCost,
        payment_method: 'cod',
        subtotal: subtotal,
        tax: 0,
        total: total,
        items: [{
          product_id: {{ $product->id }},
          product_name: "{{ $product->name }}",
          product_image: "{{ $product->image }}",
          price: price,
          quantity: qty,
          variants: {
            size: size
          }
        }]
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const successDiv = document.getElementById("orderSuccessMsg");
        successDiv.classList.remove("d-none");
        successDiv.scrollIntoView({ behavior: "smooth", block: "center" });
        
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 1500);
      } else {
        alert(data.message || 'অর্ডার করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন (Cash on Delivery)';
      }
    })
    .catch(error => {
      console.error('Order error:', error);
      alert('অর্ডার করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> অর্ডার কনফর্ম করুন (Cash on Delivery)';
    });
  });

  // SMOOTH SCROLL
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  });
</script>
</body>
</html>
