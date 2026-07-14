<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
        $siteName = $companySettings['site_name'] ?? 'E-Commerce';
        $favicon = $companySettings['favicon'] ?? null;
    @endphp
    <title>@hasSection('title')@yield('title') - @endif{{ $siteName }}</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

            function requireLogin() {
                const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
                if (!isAuthenticated) {
                    window.location.href = "{{ route('user.login') }}";
                    return false;
                }

                return true;
            }

            function addToCart(productId, productName, productPrice, productImage, quantity = 1, variants = null) {
                if (!requireLogin()) {
                    return;
                }

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
                    if (!requireLogin()) {
                        return;
                    }
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

            function checkoutSingleItem(productId, productName, productPrice, productImage, quantity = 1, variants = null) {
                const item = {
                    id: productId,
                    name: productName,
                    price: parseFloat(productPrice),
                    image: productImage,
                    quantity: quantity,
                    variants: variants || {}
                };
                localStorage.setItem('checkout_items', JSON.stringify([item]));
                window.location.href = "/checkout";
            }

            window.checkoutSingleItemGlobal = checkoutSingleItem;

            // Bind event listeners for checkout from dropdown
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.checkout-dropdown-btn');
                if (btn) {
                    e.preventDefault();
                    if (!requireLogin()) {
                        return;
                    }
                    if (cart.length === 0) return;
                    localStorage.removeItem('checkout_items'); // Fallback to cart
                    window.location.href = "/checkout";
                }
            });

            // Bind event listeners for page-level Buy Now buttons
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-bid');
                if (btn) {
                    e.preventDefault();
                    if (!requireLogin()) {
                        return;
                    }
                    const id = btn.dataset.id;
                    const name = btn.dataset.name;
                    const price = btn.dataset.price;
                    const image = btn.dataset.image;
                    if (id && name && price) {
                        checkoutSingleItem(id, name, price, image, 1, null);
                    }
                }
            });

            // Run badge and dropdown update on load
            updateCartBadge();
            updateCartDropdown();
        })();
    </script>
    @stack('scripts')
</body>
</html>
