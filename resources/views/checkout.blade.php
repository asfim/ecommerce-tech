@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
  :root{
    --ink:#111b16;
    --paper:#f6f4ee;
    --panel:#ffffff;
    --line:#e4e1d7;
    --signal:#2c56d6;
    --signal-dark:#1f3fa8;
    --moss:#3c6e47;
    --muted:#767066;
    --danger:#c0432e;
  }

  .mono{ font-family:'IBM Plex Mono',monospace; }
  .display{ font-family:'Fraunces',serif; }

  /* Step rail */
  .stepline{
    max-width:1120px; margin:36px auto 0; padding:0 24px;
    display:flex; align-items:center; gap:0;
  }
  .step{ display:flex; align-items:center; gap:10px; }
  .step .num{
    width:28px; height:28px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-family:'IBM Plex Mono',monospace; font-size:12px;
    border:1.5px solid var(--line); color:var(--muted); background:var(--panel);
    flex-shrink:0;
  }
  .step.done .num{ background:var(--moss); border-color:var(--moss); color:#fff; }
  .step.active .num{ background:var(--signal); border-color:var(--signal); color:#fff; }
  .step .label{ font-size:13.5px; font-weight:600; color:var(--muted); white-space:nowrap; }
  .step.active .label, .step.done .label{ color:var(--ink); }
  .step-connector{ flex:1; height:1.5px; background:var(--line); margin:0 14px; position:relative; top:-1px; }
  .step-connector.done{ background:var(--moss); }

  .checkout-layout{
    max-width:1120px; margin:0 auto; padding:32px 24px 100px;
    display:grid; grid-template-columns:1fr 400px; gap:40px;
    align-items:start;
  }

  .checkout-card{
    background:var(--panel);
    border:1px solid var(--line);
    border-radius:10px;
    padding:28px 30px;
    margin-bottom:22px;
  }
  .checkout-card h2{
    font-family:'Fraunces',serif; font-weight:600; font-size:1.3rem; margin:0 0 4px;
    display:flex; align-items:center; gap:10px;
  }
  .checkout-card h2 .idx{ font-family:'IBM Plex Mono',monospace; font-size:.75rem; color:var(--signal); font-weight:500; }
  .checkout-card .hint{ color:var(--muted); font-size:13px; margin-bottom:20px; }

  label.field-label{
    font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.4px;
    color:var(--muted); margin-bottom:6px; display:block;
  }
  .checkout-input, .checkout-select{
    border:1.5px solid var(--line); border-radius:6px; padding:10px 12px;
    font-size:14px; background:#fff; color:var(--ink);
  }
  .checkout-input:focus, .checkout-select:focus{
    border-color:var(--signal); box-shadow:0 0 0 3px rgba(44,86,214,.12); outline:none;
  }
  .g-row{ display:grid; gap:16px; margin-bottom:16px; }
  .g-2{ grid-template-columns:1fr 1fr; }
  .g-3{ grid-template-columns:2fr 1fr 1fr; }

  /* Shipping method */
  .ship-option{
    border:1.5px solid var(--line); border-radius:8px; padding:14px 16px;
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:10px; cursor:pointer; transition:border-color .15s;
  }
  .ship-option.selected{ border-color:var(--signal); background:#f4f7ff; }
  .ship-option .left{ display:flex; align-items:center; gap:12px; }
  .ship-option input[type=radio]{ accent-color:var(--signal); width:17px; height:17px; }
  .ship-option .t{ font-weight:600; font-size:14px; }
  .ship-option .s{ font-size:12.5px; color:var(--muted); }
  .ship-option .price{ font-family:'IBM Plex Mono',monospace; font-weight:500; }

  /* Payment tabs */
  .pay-tabs{ display:flex; gap:10px; margin-bottom:20px; }
  .pay-tab{
    flex:1; border:1.5px solid var(--line); border-radius:8px; padding:12px;
    text-align:center; font-size:13px; font-weight:600; color:var(--muted);
    cursor:pointer; display:flex; flex-direction:column; align-items:center; gap:6px;
  }
  .pay-tab i{ font-size:20px; }
  .pay-tab.active{ border-color:var(--signal); color:var(--signal); background:#f4f7ff; }

  /* Order summary */
  .summary{
    background:var(--panel); border:1px solid var(--line); border-radius:10px;
    position:sticky; top:24px; overflow:hidden;
  }
  .summary-head{ padding:22px 26px 14px; }
  .summary-head h3{ font-family:'Fraunces',serif; font-weight:600; font-size:1.15rem; margin:0; }
  .summary-head .count{ font-size:12.5px; color:var(--muted); margin-top:2px; }

  .summary-items{ padding:0 26px; max-height:260px; overflow-y:auto; }
  .item-row{ display:flex; gap:12px; padding:12px 0; border-bottom:1px dashed var(--line); align-items:center; }
  .item-row:last-child{ border-bottom:none; }
  .item-row img{ width:52px; height:52px; object-fit:contain; border-radius:6px; background:#fff; border: 1px solid #eee; flex-shrink:0; }
  .item-row .name{ font-size:13px; font-weight:600; line-height:1.3; }
  .item-row .meta{ font-size:11.5px; color:var(--muted); }
  .item-row .price{ font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500; white-space:nowrap; }

  .promo-row{ padding:16px 26px; border-top:1px solid var(--line); display:flex; gap:8px; }
  .promo-row input{ flex:1; }
  .promo-row button{ background:var(--ink); color:#fff; border:none; border-radius:6px; padding:0 16px; font-size:13px; font-weight:600; }

  /* Perforated tear edge */
  .tear{
    height:14px; position:relative;
    background:
      radial-gradient(circle at 10px 0, transparent 7px, var(--panel) 7.5px) 0 -7px / 20px 14px repeat-x;
  }
  .totals{ padding:18px 26px 24px; }
  .t-row{ display:flex; justify-content:space-between; font-size:13.5px; margin-bottom:9px; color:#4b463e; }
  .t-row .mono{ font-family:'IBM Plex Mono',monospace; }
  .t-row.grand{ font-size:1.15rem; font-weight:700; color:var(--ink); margin-top:14px; padding-top:14px; border-top:1px solid var(--line); }
  .t-row.grand .mono{ font-family:'IBM Plex Mono',monospace; }
  .t-row.discount{ color:var(--moss); }

  .btn-place{
    width:100%; background:var(--signal); color:#fff; border:none; border-radius:8px;
    padding:15px; font-size:15px; font-weight:700; margin-top:18px;
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition:background .15s;
    cursor:pointer;
  }
  .btn-place:hover{ background:var(--signal-dark); }

  .trust-row{
    display:flex; justify-content:center; gap:18px; margin-top:16px;
    font-size:11.5px; color:var(--muted);
  }
  .trust-row span{ display:flex; align-items:center; gap:5px; }

  @media (max-width:900px){
    .checkout-layout{ grid-template-columns:1fr; }
    .g-2, .g-3{ grid-template-columns:1fr; }
    .step .label{ display:none; }
    .summary{ position:static; }
  }
</style>
@endpush

@section('content')
<div class="stepline">
  <div class="step done"><span class="num"><i class="bi bi-check-lg"></i></span><span class="label">Cart</span></div>
  <div class="step-connector done"></div>
  <div class="step active"><span class="num">2</span><span class="label">Shipping</span></div>
  <div class="step-connector"></div>
  <div class="step"><span class="num">3</span><span class="label">Payment</span></div>
  <div class="step-connector"></div>
  <div class="step"><span class="num">4</span><span class="label">Review</span></div>
</div>

<div class="checkout-layout">
  <!-- LEFT: forms -->
  <div>
    <form id="checkoutForm">
      <div class="checkout-card">
        <h2><span class="idx">01</span> Shipping address</h2>
        <div class="hint">Please enter your shipping delivery details.</div>
        <div class="g-row g-2">
          <div>
            <label class="field-label">Full Name</label>
            <input type="text" id="checkoutName" class="checkout-input w-100" placeholder="Your full name" required>
          </div>
          <div>
            <label class="field-label">Phone number</label>
            <input type="tel" id="checkoutPhone" class="checkout-input w-100" placeholder="+8801xxxxxxxxx" required>
          </div>
        </div>
        <div class="g-row" style="grid-template-columns:1fr; margin-bottom:0;">
          <div>
            <label class="field-label">Address</label>
            <textarea id="checkoutAddress" class="checkout-input w-100" rows="2" placeholder="House, Road, Area, City" required></textarea>
          </div>
        </div>
      </div>

      <div class="checkout-card">
        <h2><span class="idx">02</span> Shipping method</h2>
        <div class="hint">Select your delivery zone.</div>
        <label class="ship-option selected">
          <div class="left">
            <input type="radio" name="ship" value="60" checked>
            <div><div class="t">Inside Dhaka</div><div class="s">Delivery within 1–2 business days</div></div>
          </div>
          <div class="price mono">৳60.00</div>
        </label>
        <label class="ship-option">
          <div class="left">
            <input type="radio" name="ship" value="120">
            <div><div class="t">Outside Dhaka</div><div class="s">Delivery within 3–5 business days</div></div>
          </div>
          <div class="price mono">৳120.00</div>
        </label>
      </div>

      <div class="checkout-card">
        <h2><span class="idx">03</span> Payment</h2>
        <div class="hint">All transactions are encrypted and secure.</div>
        <div class="pay-tabs">
          <div class="pay-tab active" data-method="sslcommerz"><i class="bi bi-wallet2"></i> Online Payment</div>
          <div class="pay-tab" data-method="cod"><i class="bi bi-cash-stack"></i> Cash on Delivery</div>
        </div>

        <input type="hidden" id="paymentMethod" value="sslcommerz">

        <div id="sslcommerzInfo" class="p-3 border rounded" style="background:#f8f9fa; font-size:13.5px;">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 20px;"></i>
            <span class="fw-bold">SSL Commerz Secure Payment Gateway</span>
          </div>
          <p class="text-muted mb-0">You will be redirected to the secure SSL Commerz gateway to pay using Cards, Mobile Banking (bKash, Nagad, Rocket) or Net Banking.</p>
        </div>

        <div id="codInfo" class="p-3 border rounded" style="display:none; background:#f0fdf4; border-color:var(--moss) !important; font-size:13.5px; color:var(--moss);">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-info-circle-fill text-success" style="font-size: 20px;"></i>
            <span class="fw-bold">Cash on Delivery Payment</span>
          </div>
          <p class="mb-0">Pay cash when your order arrives at your shipping destination. No digital card details needed.</p>
        </div>
      </div>
    </form>
  </div>

  <!-- RIGHT: summary -->
  <div class="summary">
    <div class="summary-head">
      <h3>Order summary</h3>
      <div class="count">0 items</div>
    </div>
    <div class="summary-items">
      <!-- Loaded dynamically via JS -->
    </div>
    <div>
      <div class="promo-row">
        <input type="text" id="couponCode" class="checkout-input" placeholder="Discount code">
        <button type="button" id="applyCouponBtn">Apply</button>
      </div>
      <div id="couponMessage" class="px-4 pb-2 small" style="display:none; font-weight:600;"></div>
    </div>
    <div class="tear"></div>
    <div class="totals">
      <div class="t-row"><span>Subtotal</span><span class="mono" id="summarySubtotal">৳0.00</span></div>
      <div class="t-row discount text-success" id="discountRow" style="display:none; color: #2e7d32 !important;"><span>Discount (<span id="discountCodeLabel"></span>)</span><span class="mono" id="summaryDiscount">-৳0.00</span></div>
      <div class="t-row"><span>Shipping</span><span class="mono" id="summaryShipping">৳60.00</span></div>
      <div class="t-row" style="display: none !important;"><span>Estimated tax (5%)</span><span class="mono" id="summaryTax">৳0.00</span></div>
      <div class="t-row grand"><span>Total</span><span class="mono" id="summaryTotal">৳0.00</span></div>
      <button type="button" id="placeOrderBtn" class="btn-place">Place order <i class="bi bi-arrow-right"></i></button>
      <div class="trust-row">
        <span><i class="bi bi-shield-check"></i> Secure</span>
        <span><i class="bi bi-arrow-counterclockwise"></i> Free returns</span>
        <span><i class="bi bi-truck"></i> Tracked shipping</span>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Determine checkout source items
    let checkoutItems = JSON.parse(localStorage.getItem('checkout_items') || '[]');
    if (checkoutItems.length === 0) {
        checkoutItems = JSON.parse(localStorage.getItem('cart') || '[]');
    }

    const itemsContainer = document.querySelector('.summary-items');
    const subtotalEl = document.getElementById('summarySubtotal');
    const shippingEl = document.getElementById('summaryShipping');
    const taxEl = document.getElementById('summaryTax');
    const totalEl = document.getElementById('summaryTotal');
    const itemsCountEl = document.querySelector('.summary-head .count');

    // Coupon variables
    const couponCodeInput = document.getElementById('couponCode');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponMessage = document.getElementById('couponMessage');
    const discountRow = document.getElementById('discountRow');
    const discountCodeLabel = document.getElementById('discountCodeLabel');
    const summaryDiscount = document.getElementById('summaryDiscount');

    let appliedCouponCode = null;
    let discountAmount = 0.00;

    function getShippingCost() {
        const checked = document.querySelector('input[name="ship"]:checked');
        return checked ? parseFloat(checked.value) : 60;
    }

    function calculateSubtotal() {
        let subtotal = 0;
        checkoutItems.forEach(item => {
            subtotal += parseFloat(item.price) * (parseInt(item.quantity) || 1);
        });
        return subtotal;
    }

    function renderSummary() {
        if (checkoutItems.length === 0) {
            itemsContainer.innerHTML = '<div class="text-center py-4 text-muted small">No items to checkout</div>';
            subtotalEl.textContent = '৳0.00';
            taxEl.textContent = '৳0.00';
            totalEl.textContent = '৳0.00';
            itemsCountEl.textContent = '0 items';
            return;
        }

        let itemsHtml = '';
        let subtotal = calculateSubtotal();
        let totalItems = 0;

        checkoutItems.forEach(item => {
            const price = parseFloat(item.price);
            const qty = parseInt(item.quantity) || 1;
            const itemTotal = price * qty;
            totalItems += qty;

            let variantsText = '';
            if (item.variants && Object.keys(item.variants).length > 0) {
                variantsText = Object.entries(item.variants).map(([k, v]) => `${k}: ${v}`).join(' · ');
            }

            itemsHtml += `
                <div class="item-row">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="name text-truncate" title="${item.name}">${item.name}</div>
                        <div class="meta">${variantsText ? variantsText + ' · ' : ''}Qty ${qty}</div>
                    </div>
                    <div class="price">৳${itemTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
            `;
        });

        itemsContainer.innerHTML = itemsHtml;
        itemsCountEl.textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;

        const shippingCost = getShippingCost();

        // Dynamic discount recalculation
        if (appliedCouponCode) {
            discountRow.style.display = 'flex';
            discountCodeLabel.textContent = appliedCouponCode;
            summaryDiscount.textContent = `-৳${discountAmount.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        } else {
            discountRow.style.display = 'none';
        }

        const tax = 0; // 0% tax
        const grandTotal = Math.max(0, subtotal - discountAmount + shippingCost);

        subtotalEl.textContent = `৳${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        shippingEl.textContent = `৳${shippingCost.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        taxEl.textContent = `৳${tax.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        totalEl.textContent = `৳${grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
    }

    renderSummary();

    // Apply coupon handler
    applyCouponBtn.addEventListener('click', function() {
        const code = couponCodeInput.value.trim();
        if (!code) {
            couponMessage.style.display = 'block';
            couponMessage.style.color = '#c0432e';
            couponMessage.textContent = 'Please enter a discount code.';
            return;
        }

        const subtotal = calculateSubtotal();

        applyCouponBtn.disabled = true;
        applyCouponBtn.textContent = 'Applying...';

        fetch("{{ route('coupon.apply') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: code,
                subtotal: subtotal
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                appliedCouponCode = data.code;
                discountAmount = parseFloat(data.discount);

                couponMessage.style.display = 'block';
                couponMessage.style.color = '#2e7d32';
                couponMessage.textContent = data.message;

                renderSummary();
            }
            applyCouponBtn.disabled = false;
            applyCouponBtn.textContent = 'Apply';
        })
        .catch(error => {
            appliedCouponCode = null;
            discountAmount = 0.00;

            couponMessage.style.display = 'block';
            couponMessage.style.color = '#c0432e';
            couponMessage.textContent = error.message || 'Invalid coupon code.';

            renderSummary();
            applyCouponBtn.disabled = false;
            applyCouponBtn.textContent = 'Apply';
        });
    });

    // Payment methods tabs switching
    const tabs = document.querySelectorAll('.pay-tab');
    const paymentMethodInput = document.getElementById('paymentMethod');
    const sslInfo = document.getElementById('sslcommerzInfo');
    const codInfo = document.getElementById('codInfo');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const selectedMethod = tab.dataset.method;
            paymentMethodInput.value = selectedMethod;

            if (selectedMethod === 'cod') {
                sslInfo.style.display = 'none';
                codInfo.style.display = 'block';
            } else {
                sslInfo.style.display = 'block';
                codInfo.style.display = 'none';
            }
        });
    });

    // Shipping method change recalculates totals
    document.querySelectorAll('input[name="ship"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.ship-option').forEach(opt => opt.classList.remove('selected'));
            this.closest('.ship-option').classList.add('selected');
            renderSummary();
        });
    });

    // Place Order validation and handling
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    placeOrderBtn.addEventListener('click', function() {
        const name = document.getElementById('checkoutName').value.trim();
        const phone = document.getElementById('checkoutPhone').value.trim();
        const address = document.getElementById('checkoutAddress').value.trim();

        if (checkoutItems.length === 0) {
            alert('Your checkout list is empty.');
            return;
        }

        if (!name || !phone || !address) {
            alert('Please fill out all required shipping details.');
            return;
        }

        const method = paymentMethodInput.value;
        const shippingCost = getShippingCost();
        const shippingMethod = shippingCost === 60 ? 'inside_dhaka' : 'outside_dhaka';

        // Calculate subtotal
        const subtotal = calculateSubtotal();
        const tax = 0;
        const total = Math.max(0, subtotal - discountAmount + shippingCost);

        // Build order items
        const orderItems = checkoutItems.map(item => ({
            product_id: item.id || null,
            product_name: item.name,
            product_image: item.image || null,
            price: parseFloat(item.price),
            quantity: parseInt(item.quantity) || 1,
            variants: item.variants || {}
        }));

        // Disable button during request
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Placing Order...';

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
                customer_address: address,
                shipping_method: shippingMethod,
                shipping_cost: shippingCost,
                payment_method: method,
                subtotal: subtotal,
                tax: tax,
                total: total,
                coupon_code: appliedCouponCode,
                items: orderItems
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear checkout and cart items
                localStorage.removeItem('checkout_items');
                const cartItems = JSON.parse(localStorage.getItem('cart') || '[]');
                const isCartCheckout = JSON.stringify(cartItems) === JSON.stringify(checkoutItems);
                if (isCartCheckout) {
                    localStorage.setItem('cart', '[]');
                } else {
                    const remainingCart = cartItems.filter(cartItem => {
                        return !checkoutItems.some(chkItem => {
                            return chkItem.id == cartItem.id && JSON.stringify(chkItem.variants || {}) === JSON.stringify(cartItem.variants || {});
                        });
                    });
                    localStorage.setItem('cart', JSON.stringify(remainingCart));
                }

                // Redirect to invoice page
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Something went wrong. Please try again.');
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerHTML = 'Place order <i class="bi bi-arrow-right"></i>';
            }
        })
        .catch(error => {
            console.error('Order error:', error);
            alert('Something went wrong. Please try again.');
            placeOrderBtn.disabled = false;
            placeOrderBtn.innerHTML = 'Place order <i class="bi bi-arrow-right"></i>';
        });
    });
});
</script>
@endpush
