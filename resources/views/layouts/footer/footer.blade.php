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
  <div class="wrap row">
    <div class="col item"><i class="bi bi-file-text"></i> Terms &amp; Conditions</div>
    <div class="col item"><i class="bi bi-arrow-counterclockwise"></i> Return Policy</div>
    <div class="col item"><i class="bi bi-headset"></i> Support Policy</div>
    <div class="col item"><i class="bi bi-shield-check"></i> Privacy Policy</div>
  </div>
</div>

<!-- Main footer -->
<footer class="main-footer">
  <div class="wrap">
    <div class="row g-4">
      <div class="col-3">
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
        <div class="d-flex gap-2">
          <input type="email" class="form-control form-control-sm" placeholder="Your email address">
          <button class="btn btn-danger btn-sm">Subscribe</button>
        </div>
      </div>
      <div class="col-3">
        <h6>Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="#">Support Policy Page</a></li>
          <li><a href="#">Return Policy Page</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Privacy Policy Page</a></li>
          <li><a href="#">Seller Policy</a></li>
          <li><a href="#">Term Conditions Page</a></li>
        </ul>
      </div>
      <div class="col-3">
        <h6>Contacts</h6>
        <ul class="list-unstyled small">
          <li>Address: Demo Address</li>
          <li>Phone: +01 123 456 789</li>
          <li>Email: info@ecommerce.com</li>
        </ul>
        <h6 class="mt-3">My Account</h6>
        <ul class="list-unstyled small">
          <li><a href="#">Login</a></li>
          <li><a href="#">Order History</a></li>
          <li><a href="#">My Wishlist</a></li>
          <li><a href="#">Track Order</a></li>
          <li><a href="#">Be an Affiliate Partner</a></li>
        </ul>
      </div>
      <div class="col-3">
        <h6>Seller Zone</h6>
        <ul class="list-unstyled small">
          <li><a href="#">Become A Seller <span class="badge bg-danger">Apply Now</span></a></li>
          <li><a href="#">Login to Seller Panel</a></li>
          <li><a href="#">Download Seller App</a></li>
        </ul>
        <h6 class="mt-3">Delivery Boy</h6>
        <ul class="list-unstyled small">
          <li><a href="#">Login to Delivery Boy Panel</a></li>
          <li><a href="#">Download Delivery Boy App</a></li>
        </ul>
        <div class="d-flex gap-2 mt-2">
          <span class="badge bg-secondary py-2"><i class="bi bi-google-play"></i> Google Play</span>
          <span class="badge bg-secondary py-2"><i class="bi bi-apple"></i> App Store</span>
        </div>
      </div>
    </div>
    <hr class="border-secondary mt-4">
    <div class="d-flex justify-content-between small flex-wrap">
      <span> eCommerce </span>
      <span class="d-flex align-items-center gap-2">
        <a href="#" class="social-ic"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-ic"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="social-ic"><i class="bi bi-youtube"></i></a>
        <a href="#" class="social-ic"><i class="bi bi-instagram"></i></a>
        <a href="#" class="social-ic"><i class="bi bi-pinterest"></i></a>
        <a href="#" class="social-ic"><i class="bi bi-linkedin"></i></a>
      </span>
    </div>
  </div>
</footer>
