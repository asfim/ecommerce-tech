@php
    $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
    $companyName = $companySettings['name'] ?? 'eCommerce';
    $companyLogo = $companySettings['logo'] ?? null;
@endphp

<div class="topline"></div>

<!-- ============ DESKTOP HEADER (992px and up) ============ -->
<div class="d-none d-lg-block">
    <!-- Header Row -->
    <div class="header-row">
        <div class="wrap d-flex align-items-center gap-4">
            <a class="d-flex align-items-center gap-2" href="{{ route('home') }}" style="text-decoration:none; color:inherit;">
                @if($companyLogo)
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 34px; border-radius: 4px;">
                @else
                    <span class="logo-box">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
                @endif
                
            </a>

            <form action="{{ route('home') }}" method="GET" class="search-input flex-grow-1 d-flex mb-0 position-relative">
                <input type="text" name="search" class="form-control search-input-field" placeholder="I am shopping for..." value="{{ request()->query('search') }}" autocomplete="off">
                <button type="submit" class="btn"><i class="bi bi-search"></i></button>
                <div class="search-results-dropdown d-none position-absolute w-100 bg-white border rounded shadow mt-1 p-2" style="z-index: 1050; top: 100%; left: 0; max-height: 350px; overflow-y: auto;"></div>
            </form>

            <div class="d-flex align-items-center gap-2">
                <a href="#" class="icon-btn"><i class="bi bi-heart"></i><span class="badge-num">3</span></a>
                <div class="dropdown">
                    <a href="#" class="icon-btn dropdown-toggle no-arrow" data-bs-toggle="dropdown" id="cartDropdownDesktop" style="text-decoration:none;">
                        <i class="bi bi-bag"></i><span class="badge-num">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3 cart-dropdown-menu" aria-labelledby="cartDropdownDesktop" style="width: 320px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.08);">
                        <!-- Dynamically rendered cart items -->
                    </ul>
                </div>
                @if(auth()->guard('admin')->check())
                    <div class="position-relative">
                        <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
                            <span>
                                <span class="name d-block">{{ auth()->guard('admin')->user()->email }}</span>
                                <span class="role">eCommerce</span>
                            </span>
                            <i class="bi bi-chevron-down small ms-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @elseif(auth()->guard('web')->check())
                    <div class="position-relative">
                        <a href="{{ route('user.dashboard') }}" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->user()->name, 0, 1)) }}" class="rounded-circle">
                            <span>
                                <span class="name d-block">{{ auth()->user()->name }}</span>
                                <span class="role">eCommerce</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('user.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="btn btn-sm btn-outline-primary">Login</a>
                    <a href="{{ route('user.register') }}" class="btn btn-sm btn-primary">Register</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Navigation Bar -->
    <div class="navbar2">
        <div class="wrap inner d-flex align-items-center">
            <!-- Category mega menu trigger -->
            <div class="cat-dd">
                <button class="cat-trigger">
                    <i class="bi bi-grid-3x3-gap-fill"></i> All Categories <i class="bi bi-chevron-down small"></i>
                </button>

                <div class="mega-panel">
                    <div class="row g-3">
                        @foreach($categories as $category)
                            <div class="col-4">
                                <div class="mega-col-title">{{ $category->name }}</div>
                                <div class="mega-list">
                                    @foreach($category->subCategories as $subCategory)
                                        <a href="#"><i class="bi bi-dot"></i> {{ $subCategory->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main nav links -->
            <ul class="nav-links list-unstyled d-flex mb-0">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">Blog</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- ============ MOBILE & TABLET HEADER (Below 992px) ============ -->
<div class="d-block d-lg-none">
    <div class="header-row">
        <div class="wrap">
            <!-- Top Row: Logo (left), Search (right) -->
            <div class="d-flex justify-content-between align-items-center mb-3 gap-3">
                <a class="d-flex align-items-center gap-2" href="{{ route('home') }}" style="text-decoration:none; color:inherit;">
                    @if($companyLogo)
                        <img src="{{ asset('storage/' . $companyLogo) }}" alt="" style="max-height: 34px; border-radius: 4px;">
                    @else
                        <span class="logo-box">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
                    @endif
                </a>

                <form action="{{ route('home') }}" method="GET" class="search-input d-flex m-0 position-relative" style="max-width: 450px; width: 100%;">
                    <input type="text" name="search" class="form-control search-input-field" placeholder="I am shopping for..." value="{{ request()->query('search') }}" autocomplete="off">
                    <button type="submit" class="btn"><i class="bi bi-search"></i></button>
                    <div class="search-results-dropdown d-none position-absolute w-100 bg-white border rounded shadow mt-1 p-2" style="z-index: 1050; top: 100%; left: 0; max-height: 350px; overflow-y: auto;"></div>
                </form>
            </div>

            <!-- Bottom Row: Nav Icon -> Category Icon -> Add to cart icon -> Auth Icon dropdown -->
            <div class="d-flex align-items-center gap-3 pt-2 border-top">
                <!-- Nav Icon (Menu Drawer Trigger) -->
                <button type="button" class="icon-btn btn btn-link text-dark p-0" id="menuToggleBtn" style="text-decoration: none;">
                    <i class="bi bi-list" style="font-size: 22px;"></i>
                </button>

                <!-- Category Icon (Category Drawer Trigger) -->
                <button type="button" class="icon-btn btn btn-link text-dark p-0" id="categoryToggleBtn" style="text-decoration: none;" title="Categories">
                    <i class="bi bi-grid-3x3-gap-fill" style="font-size: 18px;"></i>
                </button>

                <!-- Add to Cart Icon (Bag Icon) -->
                <div class="dropdown">
                    <a href="#" class="icon-btn dropdown-toggle no-arrow" data-bs-toggle="dropdown" id="cartDropdownMobile" style="text-decoration:none;">
                        <i class="bi bi-bag"></i><span class="badge-num">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start p-3 cart-dropdown-menu" aria-labelledby="cartDropdownMobile" style="width: 300px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.08);">
                        <!-- Dynamically rendered cart items -->
                    </ul>
                </div>

                <!-- Auth Icon with Dropdown -->
                <div class="dropdown">
                    <a href="#" class="icon-btn dropdown-toggle no-arrow" data-bs-toggle="dropdown" id="authDropdownBtn" style="text-decoration: none;">
                        @if (auth()->guard('admin')->check())
                            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->guard('admin')->user()->email, 0, 1)) }}"
                                class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                        @elseif(auth()->guard('web')->check())
                            <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
                                class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                        @else
                            <i class="bi bi-person" style="font-size: 20px;"></i>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start mt-2">
                        @if (auth()->guard('admin')->check())
                            <li><h6 class="dropdown-header text-dark fw-bold">{{ auth()->guard('admin')->user()->email }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        @elseif(auth()->guard('web')->check())
                            <li><h6 class="dropdown-header text-dark fw-bold">{{ auth()->user()->name }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('user.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('user.login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.register') }}"><i class="bi bi-person-plus me-2"></i>Register</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Drawer Backdrop -->
<div class="drawer-backdrop" id="drawerBackdrop"></div>

<!-- Mobile Menu Drawer (Left Slide) -->
<div id="mobileMenuDrawer" class="mobile-drawer drawer-left">
    <div class="drawer-header">
        <h5>Menu</h5>
        <button type="button" class="btn-close" id="closeMenuDrawer"></button>
    </div>
    <div class="drawer-body">
        <ul class="mobile-nav-links list-unstyled">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="bi bi-house-door me-2"></i>Home</a></li>
            <li><a href="#"><i class="bi bi-info-circle me-2"></i>About</a></li>
            <li><a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}"><i class="bi bi-journal-text me-2"></i>Blog</a></li>
            <li><a href="#"><i class="bi bi-envelope me-2"></i>Contact</a></li>
        </ul>
    </div>
</div>

<!-- Mobile Category Drawer (Right Slide) -->
<div id="mobileCategoryDrawer" class="mobile-drawer drawer-right">
    <div class="drawer-header">
        <h5>Categories</h5>
        <button type="button" class="btn-close" id="closeCategoryDrawer"></button>
    </div>
    <div class="drawer-body">
        <div class="mobile-categories-list">
            @foreach($categories as $category)
                <div class="mb-3">
                    <div class="fw-bold border-bottom pb-1 mb-2 text-dark" style="font-size:14px;">{{ $category->name }}</div>
                    <div class="ps-2">
                        @foreach($category->subCategories as $subCategory)
                            <a href="#" class="d-block py-1 text-muted small" style="text-decoration:none;"><i class="bi bi-dot"></i> {{ $subCategory->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Sidebar Drawer Javascript Trigger Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menuToggleBtn');
        const categoryToggle = document.getElementById('categoryToggleBtn');
        const closeMenu = document.getElementById('closeMenuDrawer');
        const closeCategory = document.getElementById('closeCategoryDrawer');
        const menuDrawer = document.getElementById('mobileMenuDrawer');
        const categoryDrawer = document.getElementById('mobileCategoryDrawer');
        const backdrop = document.getElementById('drawerBackdrop');

        function toggleDrawer(drawer, show) {
            if (show) {
                drawer.classList.add('show');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                drawer.classList.remove('show');
                if (!menuDrawer.classList.contains('show') && !categoryDrawer.classList.contains('show')) {
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDrawer(menuDrawer, true);
            });
        }
        if (categoryToggle) {
            categoryToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDrawer(categoryDrawer, true);
            });
        }
        if (closeMenu) {
            closeMenu.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDrawer(menuDrawer, false);
            });
        }
        if (closeCategory) {
            closeCategory.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDrawer(categoryDrawer, false);
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                toggleDrawer(menuDrawer, false);
                toggleDrawer(categoryDrawer, false);
            });
        }
    });
</script>

<style>
    .search-results-dropdown {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    .search-item-link:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInputs = document.querySelectorAll('.search-input-field');
        
        searchInputs.forEach(input => {
            const form = input.closest('form');
            const dropdown = form.querySelector('.search-results-dropdown');
            let debounceTimer;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = input.value.trim();

                if (query.length < 2) {
                    dropdown.innerHTML = '';
                    dropdown.classList.add('d-none');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/products/search-api?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(products => {
                            dropdown.innerHTML = '';
                            
                            if (products.length === 0) {
                                dropdown.innerHTML = '<div class="text-muted text-center py-3 small">No products found</div>';
                                dropdown.classList.remove('d-none');
                                return;
                            }

                            products.forEach(product => {
                                const itemHtml = `
                                    <a href="${product.url}" class="d-flex align-items-center gap-3 p-2 mb-1 text-decoration-none text-dark rounded search-item-link">
                                        <img src="${product.image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" alt="">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold text-truncate small">${product.name}</div>
                                            <div class="text-danger small">$${product.price}</div>
                                        </div>
                                    </a>
                                `;
                                dropdown.insertAdjacentHTML('beforeend', itemHtml);
                            });

                            dropdown.classList.remove('d-none');
                        })
                        .catch(err => console.error('Error fetching live search results:', err));
                }, 300);
            });

            // Hide dropdown on click outside
            document.addEventListener('click', function(e) {
                if (!form.contains(e.target)) {
                    dropdown.classList.add('d-none');
                }
            });
            
            // Show dropdown on focus if it has items
            input.addEventListener('focus', function() {
                if (dropdown.children.length > 0) {
                    dropdown.classList.remove('d-none');
                }
            });
        });
    });
</script>
