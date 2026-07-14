<!-- About text -->
<div class="about-sec">
  <div class="wrap">
    <p> eCommerce CMS is an online shopping platform bringing great deals, with platforms existing across Asia including Singapore, Thailand, Indonesia, Vietnam, Philippines, and Taiwan. Our online shopping experience is powered by dependable service and a wide selection of products at affordable prices, so you can enjoy a hassle-free shopping journey whenever and wherever you shop.</p>
    <h6>Quality Products, Low Prices</h6>
    <p> eCommerce strives to provide affordable prices while maintaining great products and services, so shoppers can enjoy their daily shopping without worrying about their wallet.</p>
    <h6>Shop the Variety with  eCommerce</h6>
    <p>Everyday deals while shopping for convenience, plus year-round promos including Super Brand Day flash sales, big discount events, and exclusive festive campaigns to keep every shopper satisfied.</p>
  </div>
</div>

<!-- Foot links row -->
<div class="footlinks">
  <div class="wrap row g-3 justify-content-center">
    <div class="col-6 col-md-3 item"><a href="{{ route('page.show', 'terms-conditions') }}" class="text-decoration-none text-reset"><i class="bi bi-file-text"></i> Terms &amp; Conditions</a></div>
    <div class="col-6 col-md-3 item"><a href="{{ route('page.show', 'return-policy') }}" class="text-decoration-none text-reset"><i class="bi bi-arrow-counterclockwise"></i> Return Policy</a></div>
    <div class="col-6 col-md-3 item"><a href="{{ route('page.show', 'support-policy') }}" class="text-decoration-none text-reset"><i class="bi bi-headset"></i> Support Policy</a></div>
    <div class="col-6 col-md-3 item"><a href="{{ route('page.show', 'privacy-policy') }}" class="text-decoration-none text-reset"><i class="bi bi-shield-check"></i> Privacy Policy</a></div>
  </div>
</div>

<!-- Main footer -->
<footer class="main-footer">
  <div class="wrap">
    <div class="row g-4">
      <div class="col-12 col-lg-3 footer-col">
        @php
          $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
          $companyName = $companySettings['name'] ?? 'eCommerce';
          $companyLogo = $companySettings['logo'] ?? null;
        @endphp
        <div class="d-flex align-items-center gap-2 mb-2">
          @if($companyLogo)
            <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 34px; border-radius: 4px;">
          @else
            <span class="logo-box">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
          @endif
          <b class="text-white"> <span style="color:#1a73e8; text-transform: uppercase;">{{ $companyName }}</span></b>
        </div>
        <p class="small">Complete system for your eCommerce business</p>
        <p class="small">Subscribe to our newsletter for regular updates about Offers, Coupons &amp; more</p>
        <div class="d-flex flex-column flex-sm-row gap-2">
          <input type="email" class="form-control form-control-sm" placeholder="Your email address">
          <button class="btn btn-danger btn-sm">Subscribe</button>
        </div>
      </div>
      <div class="col-12 col-lg-3 footer-col">
        <h6>Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="{{ route('page.show', 'support-policy') }}">Support Policy Page</a></li>
          <li><a href="{{ route('page.show', 'return-policy') }}">Return Policy Page</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy Page</a></li>
          <li><a href="#">Seller Policy</a></li>
          <li><a href="{{ route('page.show', 'terms-conditions') }}">Term Conditions Page</a></li>
        </ul>
      </div>
      <div class="col-12 col-lg-3 footer-col">
        <h6>Contacts</h6>
        <ul class="list-unstyled small">
          <li>Address: {{ $companySettings['address'] ?? 'Demo Address' }}</li>
          <li>Phone: {{ $companySettings['phone'] ?? '+01 123 456 789' }}</li>
          <li>Email: info@ecommerce.com</li>
        </ul>
      </div>
      <div class="col-12 col-lg-3 footer-col">
        <h6>My Account</h6>
        <ul class="list-unstyled small">
          <li><a href="{{route('user.login')}}">Login</a></li>
          <li><a href="{{route('user.register')}}">Register</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-secondary mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center small flex-wrap gap-3">
      <span> eCommerce </span>
      <span class="d-flex align-items-center gap-2 flex-wrap">
        @if(!empty($companySettings['facebook']))
          <a href="{{ $companySettings['facebook'] }}" class="social-ic" target="_blank"><i class="bi bi-facebook"></i></a>
        @endif
        @if(!empty($companySettings['twitter']))
          <a href="{{ $companySettings['twitter'] }}" class="social-ic" target="_blank"><i class="bi bi-twitter-x"></i></a>
        @endif
        @if(!empty($companySettings['youtube']))
          <a href="{{ $companySettings['youtube'] }}" class="social-ic" target="_blank"><i class="bi bi-youtube"></i></a>
        @endif
        @if(!empty($companySettings['instagram']))
          <a href="{{ $companySettings['instagram'] }}" class="social-ic" target="_blank"><i class="bi bi-instagram"></i></a>
        @endif
        @if(!empty($companySettings['pinterest']))
          <a href="{{ $companySettings['pinterest'] }}" class="social-ic" target="_blank"><i class="bi bi-pinterest"></i></a>
        @endif
        @if(!empty($companySettings['linkedin']))
          <a href="{{ $companySettings['linkedin'] }}" class="social-ic" target="_blank"><i class="bi bi-linkedin"></i></a>
        @endif
      </span>
    </div>
  </div>
</footer>
