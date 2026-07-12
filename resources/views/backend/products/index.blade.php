@extends('layouts.backend.app')

@section('title', 'Products')

@section('content')
    <div class="clearfix mb-4">
        <div class="dropdown float-end">
            <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}"
                    class="rounded-circle">
                <span>
                    <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
                    <span class="role">eCommerce</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        <h4>Products</h4>
    </div>

    <div class="stat-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <label class="form-label mb-0 fw-semibold small">Show</label>
                <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;">
                    @foreach (['all' => 'All', 10 => '10', 20 => '20', 50 => '50', 100 => '100'] as $value => $label)
                        <option value="{{ $value }}" {{ (string) $perPage === (string) $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
                <label class="form-label mb-0 fw-semibold small">entries</label>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add
                Product</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th style="width: 70px;">Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Buy Price</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Sales</th>
                    <th>New Arrival</th>
                    <th>Featured</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="rounded border"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="https://placehold.co/40x40/eee/aaa?text=No+Img" class="rounded border"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $product->buy_price ? '$' . number_format($product->buy_price, 2) : '-' }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->sales_count }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input active-toggle" type="checkbox" data-id="{{ $product->id }}"
                                    {{ $product->is_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input featured-toggle" type="checkbox"
                                    data-id="{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="btn btn-sm btn-info text-white" title="View Product"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline-block m-0"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($perPage !== 'all' && $products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{-- {{ $products->links() }} --}}
        @endif
    </div>

    <script>
        document.getElementById('perPageSelect').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });

        document.querySelectorAll('.featured-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const productId = this.dataset.id;
                fetch(`/admin/products/${productId}/toggle-featured`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        // toggle reflects server state
                    })
                    .catch(() => {
                        // revert on error
                        this.checked = !this.checked;
                    });
            });
        });

        document.querySelectorAll('.active-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const productId = this.dataset.id;
                fetch(`/admin/products/${productId}/toggle-active`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        // toggle reflects server state
                    })
                    .catch(() => {
                        // revert on error
                        this.checked = !this.checked;
                    });
            });
        });
    </script>
@endsection
