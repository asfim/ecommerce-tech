<!-- Main footer -->
<footer class="main-footer">
  <div class="wrap">
    <div class="row gx-5 gy-4">

      {{-- Brand Column --}}
      <div class="col-12 col-md-6 col-lg-4 footer-col">
        @php
          $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
          $companyName = $companySettings['name'] ?? 'eCommerce';
          $companyLogo = $companySettings['logo'] ?? null;
        @endphp
        <div class="d-flex align-items-center gap-2 mb-3">
          @if($companyLogo)
            <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 38px; border-radius: 6px;">
          @else
            <span class="logo-box">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
          @endif
          <span class="fw-bold fs-5" style="color:#E0471B; text-transform: uppercase; letter-spacing: 1px;">{{ $companyName }}</span>
        </div>
        <p class="footer-desc">Complete system for your eCommerce business. Subscribe to our newsletter for regular updates about Offers, Coupons &amp; more.</p>
        <div class="footer-newsletter d-flex gap-2 mt-3">
          <input type="email" class="form-control form-control-sm footer-input" placeholder="Your email address">
          <button class="btn btn-sm footer-subscribe-btn">Subscribe</button>
        </div>
      </div>

      {{-- Quick Links --}}
      <div class="col-6 col-md-3 col-lg-3 footer-col">
        <h6 class="footer-heading">Quick Links</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="{{ route('page.show', 'support-policy') }}">Support Policy</a></li>
          <li><a href="{{ route('page.show', 'return-policy') }}">Return Policy</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
          <li><a href="#">Seller Policy</a></li>
          <li><a href="{{ route('page.show', 'terms-conditions') }}">Terms &amp; Conditions</a></li>
        </ul>
      </div>

      {{-- Contacts --}}
      <div class="col-6 col-md-3 col-lg-3 footer-col">
        <h6 class="footer-heading">Contacts</h6>
        <ul class="list-unstyled footer-links">
          <li><i class="bi bi-geo-alt-fill me-2" style="color:#E0471B;"></i>{{ $companySettings['address'] ?? 'Demo Address' }}</li>
          <li><i class="bi bi-telephone-fill me-2" style="color:#E0471B;"></i>{{ $companySettings['phone'] ?? '+01 123 456 789' }}</li>
          <li><i class="bi bi-envelope-fill me-2" style="color:#E0471B;"></i>{{ $companySettings['email'] ?? 'info@ecommerce.com' }}</li>
        </ul>
      </div>

      {{-- My Account --}}
      <div class="col-6 col-md-3 col-lg-2 footer-col">
        <h6 class="footer-heading">My Account</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="{{ route('user.login') }}"><i class="bi bi-person-fill me-2" style="color:#E0471B;"></i>Login</a></li>
          <li><a href="{{ route('user.register') }}"><i class="bi bi-person-plus-fill me-2" style="color:#E0471B;"></i>Register</a></li>
        </ul>
      </div>

    </div>

    <hr class="footer-divider mt-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 footer-bottom">
      <span class="footer-copy">&copy; 2026 {{ $companyName }}. All Rights Reserved. H Tech Provision IT Solutions.</span>
      <span class="d-flex align-items-center gap-3">
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

