<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'eCommerce - Fashion Store')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{
            --blue:#1a73e8;
            --dark:#1c1c1c;
            --muted:#8a8a8a;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #222;
            font-size: 14px;
            background: #fff;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        .wrap {
            max-width: 1260px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .topline{ height:6px; background:#111; }
        .header-row{ padding:14px 0; border-bottom:1px solid #eee; }
        .logo-box{ width:34px;height:34px;background:#111;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700; }
        .search-input{ max-width:560px; }
        .search-input input{ border-radius:20px 0 0 20px; }
        .search-input button{ border-radius:0 20px 20px 0; background:#111; color:#fff; border:1px solid #111; }
        .navbar2{ border-bottom:1px solid #eee; padding:8px 0; }
        .navbar2 a{ color:#333; font-size:13px; margin-right:22px; }
        .navbar2 a:hover{ color:var(--blue); }

        /* Hero grid */
        .hero-sec{ background:#f3f3f3; padding:16px 0; }
        .brand-banner{ position:relative; height:260px; border-radius:6px; overflow:hidden; background:#ccc; }
        .brand-banner img{ width:100%; height:100%; object-fit:cover; }
        .brand-banner .cap{ position:absolute; top:24px; left:24px; font-weight:800; font-size:1.6rem; line-height:1.1; text-transform:uppercase; }
        .season-banner{ height:260px; border-radius:6px; overflow:hidden; position:relative; background:linear-gradient(135deg,#1a3fbf,#2a5be8); color:#fff; }
        .season-banner img{ position:absolute; bottom:0; right:0; height:100%; object-fit:cover; opacity:.9; }
        .season-banner .cap{ position:relative; z-index:2; padding:20px; }
        .hotcat-panel{ background:#fff; border-radius:6px; padding:14px; height:260px; }
        .hotcat-panel h6{ font-weight:700; font-size:13px; }
        .hotcat-item img{ width:100%; height:60px; object-fit:cover; border-radius:6px; margin-bottom:4px; }
        .hotcat-item .name{ font-size:10.5px; text-align:center; color:#555; }

        .featured-strip{ background:#fff; border-radius:6px; padding:12px 16px; margin-top:14px; }
        .fs-item{ display:flex; align-items:center; gap:10px; }
        .fs-item img{ width:48px; height:48px; object-fit:cover; border-radius:4px; background:#f2f2f2; }
        .fs-item .t{ font-size:11.5px; font-weight:600; }
        .fs-item .p{ font-size:12px; font-weight:700; }
        .fs-item .p .old{ text-decoration:line-through; color:#bbb; font-weight:400; font-size:11px; }

        .trending-box{ border:2px solid var(--blue); border-radius:6px; padding:16px; margin:20px 0; display:flex; align-items:center; gap:20px; }
        .trending-box h6{ font-weight:700; margin-bottom:2px; }
        .trending-box p{ font-size:12px; color:var(--muted); margin-bottom:8px; }
        .tcat-item{ text-align:center; }
        .tcat-item img{ width:74px; height:74px; object-fit:cover; border-radius:50%; margin-bottom:4px; }
        .tcat-item .name{ font-size:11.5px; }

        .panel-title{ display:flex; justify-content:space-between; align-items:center; padding:12px 16px; font-weight:700; font-size:13px; }
        .panel-title .arrow{ width:26px;height:26px;border-radius:50%;background:#111;color:#fff;display:flex;align-items:center;justify-content:center; }

        .bestselling-panel{ background:#eceee6; border-radius:6px; }
        .todaydeal-panel{ background:#fff; border:1px solid #eee; border-radius:6px; }
        .mini-prod{ text-align:center; padding:0 6px; }
        .mini-prod img{ width:100%; height:90px; object-fit:contain; margin-bottom:6px; background:#fff; border-radius:4px;}
        .mini-prod .t{ font-size:11px; color:#444; min-height:28px; }
        .mini-prod .p{ font-size:12px; font-weight:700; }

        .promo3{ height:220px; border-radius:6px; overflow:hidden; position:relative; border: 1px solid #ddd; background:#f5f5f5; }
        .promo3 img{ width:100%; height:100%; object-fit:contain; }
        /* .hotcat-item{
            border-radius: 6px;
            border:  solid #cccccc;

        } */

        .newarrival-banner{ background:#d8cdb8; border-radius:6px; min-height:250px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 20px; text-align:center; position:relative; }
        .newarrival-banner img{ max-width:100%; max-height:100%; object-fit:contain; }
        .newarrival-banner .cap{ font-size:2rem; font-weight:800; letter-spacing:6px; color:#fff; text-shadow:0 2px 4px rgba(0,0,0,.3); writing-mode:vertical-rl; position:absolute; left:20px; top:20px; }
        .newarrival-list-panel{ background:#e6dfd0; border-radius:6px; }
        .newarrival-item{ display:flex; gap:10px; align-items:center; padding:8px; background:#fff; border-radius:6px; margin-bottom:8px; }
        .newarrival-item img{ width:50px; height:50px; object-fit:contain; }
        .newarrival-item .t{ font-size:11.5px; font-weight:600; }
        .newarrival-item .bid{ font-size:10.5px; color:var(--muted); }
        .btn-bid{ background:#c9a45c; color:#fff; font-size:10px; padding:3px 10px; border-radius:3px; border:none; }

        .classified-sec{ background:#f5f5f5; padding:26px 0; }
        .classified-card{ background:#fff; border:1px solid #eee; border-radius:6px; padding:10px; text-align:center; position:relative; }
        .classified-card img{ width:100%; height:90px; object-fit:contain; margin-bottom:6px; }
        .classified-card .p{ font-weight:700; font-size:12.5px; }
        .badge-used{ position:absolute; bottom:8px; left:50%; transform:translateX(-50%); background:#dfeaff; color:#1a73e8; font-size:10px; padding:2px 10px; border-radius:3px; }
        .badge-new{ position:absolute; bottom:8px; left:50%; transform:translateX(-50%); background:#111; color:#fff; font-size:10px; padding:2px 10px; border-radius:3px; }

        .preorder-panel{ border:1px solid #eee; border-radius:6px; padding:14px; }
        .preorder-hero{ background:#111; color:#fff; border-radius:6px; height:100%; padding:20px; position:relative; overflow:hidden; }
        .preorder-hero .badge-limit{ background:#ff5722; font-size:11px; padding:4px 10px; border-radius:3px; }
        .rating i{ font-size:11px; color:#f2b01e; }

        .shops-banner{ background:linear-gradient(120deg,#eef3fb,#dbe7fb); border-radius:6px; padding:30px; display:flex; align-items:center; justify-content:space-between; margin:26px 0; }
        .shops-banner h3{ font-weight:800; color:#1c2b4a; }
        .shops-banner img{ height:120px; border-radius:6px; object-fit:cover; }

        .prod-card{ border:1px solid #eee; border-radius:6px; padding:10px; text-align:center; height:100%; position:relative; }
        .prod-card img{ width:100%; height:150px; object-fit:contain; margin-bottom:8px; }
        .prod-card .t{ font-size:12px; color:#444; min-height:32px; }
        .prod-card .p{ font-weight:700; font-size:13px; }
        .prod-card .p .old{ text-decoration:line-through; color:#bbb; font-weight:400; font-size:11.5px; }
        .disc-badge{ position:absolute; top:8px; left:8px; font-size:10px; padding:3px 7px; border-radius:3px; color:#fff; }

        .about-sec{ background:#fafafa; padding:30px 0; color:#999; font-size:12px; line-height:1.7; }
        .about-sec h6{ color:#666; font-weight:700; margin-top:14px; }

        .footlinks{ background:#fff; padding:20px 0; border-top:1px solid #eee; }
        .footlinks .item{ text-align:center; font-size:13px; font-weight:600; }

        footer.main-footer{ background:#111; color:#aaa; padding:40px 0 15px; }
        footer.main-footer a{ color:#aaa; }
        footer.main-footer a:hover{ color:#fff; }
        footer.main-footer h6{ color:#fff; font-weight:700; margin-bottom:12px; font-size:13px; text-transform:uppercase; }
        .social-ic{ width:30px;height:30px;border-radius:50%;background:#2a2a2a;display:inline-flex;align-items:center;justify-content:center;color:#fff;margin-right:6px; }

        /* Custom Toast Notifications */
        .custom-cart-toast {
            position: fixed;
            bottom: 24px;
            right: -350px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            width: 320px;
            border-left: 4px solid #1a73e8;
            transition: right 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s;
        }

        .custom-cart-toast.show {
            right: 24px;
        }

        .custom-cart-toast img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #eee;
        }

        .custom-cart-toast .toast-content h6 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #1a73e8;
        }

        .custom-cart-toast .toast-content p {
            margin: 2px 0 0 0;
            font-size: 11.5px;
            color: #555;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 210px;
        }

        /* Cart Badge Bounce Animation */
        .badge-bounce {
            animation: badge-bounce 0.4s ease;
        }

        @keyframes badge-bounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.4);
            }
        }

        /* Checkout Modal */
        .checkout-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .checkout-modal-backdrop.show {
            opacity: 1;
            pointer-events: auto;
        }

        .checkout-modal {
            background: #fff;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }

        .checkout-modal-backdrop.show .checkout-modal {
            transform: translateY(0);
        }

        .checkout-header {
            background: #f8f9fa;
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .checkout-header h5 {
            margin: 0;
            font-weight: 700;
            color: #1c1c1c;
        }

        .checkout-body {
            padding: 20px;
        }

        .checkout-footer {
            padding: 14px 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8f9fa;
        }
    </style>
    @stack('styles')
</head>
<body>

    @include('layouts.header.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ─── Frontend Cart & Checkout System ───────────────────────────────
        (function() {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');

            function updateCartBadge() {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const badges = document.querySelectorAll('a.icon-btn .badge-num');
                badges.forEach(badge => {
                    badge.textContent = totalItems;
                    badge.classList.remove('badge-bounce');
                    void badge.offsetWidth; // Trigger reflow
                    badge.classList.add('badge-bounce');
                });
            }

            function updateCartDropdown() {
                const dropdownMenus = document.querySelectorAll('.cart-dropdown-menu');
                dropdownMenus.forEach(menu => {
                    if (cart.length === 0) {
                        menu.innerHTML = `
                            <div class="text-center py-4">
                                <i class="bi bi-cart-x text-muted" style="font-size: 32px;"></i>
                                <p class="text-muted small mb-0 mt-2">Your cart is empty</p>
                            </div>
                        `;
                        return;
                    }

                    let itemsHtml = '';
                    let total = 0;

                    cart.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        total += itemTotal;

                        let variantsHtml = '';
                        if (item.variants && Object.keys(item.variants).length > 0) {
                            const details = Object.entries(item.variants).map(([k, v]) => `${k}: ${v}`).join(', ');
                            variantsHtml = `<div class="text-muted" style="font-size: 10px; margin-top: 1px;">${details}</div>`;
                        }

                        itemsHtml += `
                            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom dropdown-cart-item">
                                <img src="${item.image}" style="width: 40px; height: 40px; object-fit: contain; border-radius: 4px; background: #fff;" class="border">
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="fw-semibold small text-truncate" title="${item.name}">${item.name}</div>
                                    ${variantsHtml}
                                    <div class="text-muted small">${item.quantity} x $${item.price.toFixed(2)}</div>
                                </div>
                                <button type="button" class="btn btn-sm text-danger p-0 remove-dropdown-item" data-id="${item.id}" data-variants='${JSON.stringify(item.variants || {})}' style="border: none; background: transparent;">
                                    <i class="bi bi-trash" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        `;
                    });

                    menu.innerHTML = `
                        <div class="px-1 py-1">
                            <h6 class="fw-bold mb-3 small d-flex justify-content-between">
                                <span>Shopping Cart</span>
                                <span class="text-primary">(${cart.reduce((sum, item) => sum + item.quantity, 0)} Items)</span>
                            </h6>
                            <div style="max-height: 200px; overflow-y: auto;" class="pe-1">
                                ${itemsHtml}
                            </div>
                            <div class="d-flex justify-content-between align-items-center my-3 pt-2 border-top">
                                <span class="fw-bold text-dark small">Total:</span>
                                <span class="fw-bold text-primary">$${total.toFixed(2)}</span>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-semibold checkout-dropdown-btn" style="border-radius: 6px;">Buy Now</button>
                        </div>
                    `;
                });
            }

            function showNotification(productName, productImage) {
                const toast = document.createElement('div');
                toast.className = 'custom-cart-toast';
                toast.innerHTML = `
                    <img src="${productImage}" alt="${productName}">
                    <div class="toast-content">
                      <h6>Added to Cart!</h6>
                      <p>${productName}</p>
                    </div>
                `;
                document.body.appendChild(toast);

                setTimeout(() => toast.classList.add('show'), 10);

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 400);
                }, 3000);
            }

            function addToCart(productId, productName, productPrice, productImage, quantity = 1, variants = null) {
                const existing = cart.find(item => {
                    if (item.id != productId) return false;
                    return JSON.stringify(item.variants || {}) === JSON.stringify(variants || {});
                });

                if (existing) {
                    existing.quantity += quantity;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: parseFloat(productPrice),
                        image: productImage,
                        quantity: quantity,
                        variants: variants || {}
                    });
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartBadge();
                updateCartDropdown();
                showNotification(productName, productImage);
            }

            // Expose globally so other pages can add to cart programmatically
            window.addToCartGlobal = addToCart;
            window.updateCartDropdown = updateCartDropdown;
            window.updateCartBadge = updateCartBadge;

            // Bind event listeners for "Add to Cart"
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.add-to-cart-btn');
                if (btn) {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    const name = btn.dataset.name;
                    const price = btn.dataset.price;
                    const image = btn.dataset.image;
                    addToCart(id, name, price, image, 1, null);
                }
            });

            // Bind event listener to remove item inside dropdown
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.remove-dropdown-item');
                if (btn) {
                    e.preventDefault();
                    const id = btn.dataset.id;
                    const itemVariants = JSON.parse(btn.dataset.variants || '{}');
                    cart = cart.filter(item => {
                        if (item.id != id) return true;
                        return JSON.stringify(item.variants || {}) !== JSON.stringify(itemVariants);
                    });
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartBadge();
                    updateCartDropdown();
                }
            });

            // Create Modal Elements in Body
            const backdrop = document.createElement('div');
            backdrop.className = 'checkout-modal-backdrop';
            backdrop.innerHTML = `
              <div class="checkout-modal">
                <div class="checkout-header">
                  <h5><i class="bi bi-shield-check text-primary me-2"></i>Secure Checkout</h5>
                  <button type="button" class="btn-close close-checkout" style="font-size:12px;"></button>
                </div>
                <div class="checkout-body">
                  <p class="text-muted small mb-3">Please fill out your details to complete your order.</p>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Product Name</label>
                    <input type="text" id="checkoutProdName" class="form-control form-control-sm" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Total Price</label>
                    <input type="text" id="checkoutProdPrice" class="form-control form-control-sm text-primary fw-bold" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Shipping Address</label>
                    <textarea id="checkoutAddress" class="form-control form-control-sm" rows="2" placeholder="123 Main St, Dhaka" required></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold">Phone Number</label>
                    <input type="tel" id="checkoutPhone" class="form-control form-control-sm" placeholder="+8801xxxxxxxxx" required>
                  </div>
                </div>
                <div class="checkout-footer">
                  <button type="button" class="btn btn-sm btn-outline-secondary close-checkout">Cancel</button>
                  <button type="button" id="submitOrderBtn" class="btn btn-sm btn-primary px-3">Place Order</button>
                </div>
              </div>
            `;
            document.body.appendChild(backdrop);

            const checkoutModal = backdrop.querySelector('.checkout-modal');
            const checkoutProdName = backdrop.querySelector('#checkoutProdName');
            const checkoutProdPrice = backdrop.querySelector('#checkoutProdPrice');
            const checkoutAddress = backdrop.querySelector('#checkoutAddress');
            const checkoutPhone = backdrop.querySelector('#checkoutPhone');

            function openCheckout(productName, productPrice) {
                checkoutProdName.value = productName;
                checkoutProdPrice.value = `$${parseFloat(productPrice).toFixed(2)}`;
                checkoutAddress.value = '';
                checkoutPhone.value = '';
                backdrop.classList.add('show');
            }

            window.openCheckoutGlobal = openCheckout;

            function closeCheckout() {
                backdrop.classList.remove('show');
            }

            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop || e.target.closest('.close-checkout')) {
                    closeCheckout();
                }
            });

            // Bind event listeners for checkout from dropdown
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.checkout-dropdown-btn');
                if (btn) {
                    e.preventDefault();
                    if (cart.length === 0) return;
                    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
                    openCheckout(`Cart Order (${totalQty} items)`, total);
                }
            });

            // Bind event listeners for page-level Buy Now buttons
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-bid');
                if (btn) {
                    e.preventDefault();
                    const card = btn.closest('.newarrival-item');
                    if (card) {
                        const name = card.querySelector('.t').textContent.trim();
                        const priceText = card.querySelector('.bid b').textContent.replace('$', '').replace(/,/g, '').trim();
                        openCheckout(name, parseFloat(priceText));
                    }
                }
            });

            // Complete Purchase
            backdrop.querySelector('#submitOrderBtn').addEventListener('click', function() {
                if (!checkoutAddress.value || !checkoutPhone.value) {
                    alert('Please fill out all shipping details.');
                    return;
                }

                const prodName = checkoutProdName.value;
                closeCheckout();

                // Clear cart if checking out the whole cart
                if (prodName.startsWith('Cart Order')) {
                    cart = [];
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartBadge();
                    updateCartDropdown();
                }

                // Show Order Success Toast
                const successToast = document.createElement('div');
                successToast.className = 'custom-cart-toast';
                successToast.style.borderColor = '#2e7d32'; // green
                successToast.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                      <i class="bi bi-check2-circle text-success fs-4"></i>
                      <div class="toast-content">
                        <h6 style="color: #2e7d32;">Order Placed!</h6>
                        <p>Thank you for buying ${prodName}!</p>
                      </div>
                    </div>
                `;
                document.body.appendChild(successToast);
                setTimeout(() => successToast.classList.add('show'), 10);
                setTimeout(() => {
                    successToast.classList.remove('show');
                    setTimeout(() => successToast.remove(), 400);
                }, 4000);
            });

            // Run badge and dropdown update on load
            updateCartBadge();
            updateCartDropdown();
        })();
    </script>
    @stack('scripts')
</body>
</html>
