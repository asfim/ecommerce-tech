@extends('layouts.backend.app')

@section('title', 'Order #' . $order->invoice_no)

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
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm me-2"><i
                class="bi bi-arrow-left"></i> Back</a>
        <h4 class="d-inline-block mb-0">Order Details</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $isFraud = $order->isFlaggedAsFraud();
        $isIpBlocked = $order->isIpBlocked();
    @endphp

    {{-- Prominent Red Warning Banner for Fraud Orders --}}
    @if ($isFraud)
        <div class="alert alert-danger p-3 mb-4 rounded-3 d-flex flex-wrap justify-content-between align-items-center"
            style="background-color: #fee2e2 !important; border: 2px solid #ef4444 !important; color: #991b1b !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-shield-x-fill text-danger fs-1"></i>
                <div>
                    <h5 class="mb-1 fw-bold text-danger text-uppercase">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i> FRAUD ORDER FLAGGED
                    </h5>
                    <p class="mb-0 text-danger fw-semibold">{{ $order->fraud_reason ?: 'This order has been marked as fraudulent.' }}</p>
                    @if ($isIpBlocked)
                        <span class="badge bg-dark mt-1"><i class="bi bi-slash-circle me-1"></i> Customer IP is Blocked: {{ $order->ip_address }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <form method="POST" action="{{ route('admin.orders.toggle-fraud', $order) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-shield-check me-1"></i> Remove Fraud Flag
                    </button>
                </form>
                @if ($order->ip_address)
                    @if ($isIpBlocked)
                        <form method="POST" action="{{ route('admin.orders.unblock-ip', $order) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-unlock me-1"></i> Unblock IP
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.orders.block-ip', $order) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-slash-circle me-1"></i> Block Customer IP
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Customer & Security Details -->
        <div class="col-md-5">
            <div class="stat-card h-100 {{ $isFraud ? 'border-danger' : '' }}" style="{{ $isFraud ? 'background-color: #fef2f2 !important; border: 1.5px solid #ef4444;' : '' }}">
                <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-person-circle me-1"></i> Customer & Security</span>
                    @if ($isFraud)
                        <span class="badge bg-danger"><i class="bi bi-shield-x-fill me-1"></i> Fraud</span>
                    @endif
                </h6>
                <div class="mb-2">
                    <span class="text-muted small d-block">Customer Name:</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Phone:</span>
                    <strong>{{ $order->customer_phone }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Address:</span>
                    <span>{{ $order->customer_address }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">IP Address:</span>
                    @if ($order->ip_address)
                        <code>{{ $order->ip_address }}</code>
                        @if ($isIpBlocked)
                            <span class="badge bg-danger ms-1">BLOCKED</span>
                        @else
                            <span class="badge bg-success ms-1">ACTIVE</span>
                        @endif
                    @else
                        <span class="text-muted small">Not recorded</span>
                    @endif
                </div>

                <div class="pt-2 border-top d-flex gap-2 flex-wrap">
                    @if (! $isFraud)
                        <form method="POST" action="{{ route('admin.orders.toggle-fraud', $order) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-shield-x me-1"></i> Mark as Fraud
                            </button>
                        </form>
                    @endif

                    @if ($order->ip_address)
                        @if ($isIpBlocked)
                            <form method="POST" action="{{ route('admin.orders.unblock-ip', $order) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-unlock me-1"></i> Unblock IP
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.orders.block-ip', $order) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-slash-circle me-1"></i> Block IP
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="col-md-7">
            <div class="stat-card h-100 {{ $isFraud ? 'border-danger' : '' }}" style="{{ $isFraud ? 'background-color: #fef2f2 !important; border: 1.5px solid #ef4444;' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $order->invoice_no }}</h5>
                        <span class="text-muted small">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div>
                        @if ($order->order_status === 'pending')
                            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock"></i> Pending</span>
                        @elseif($order->order_status === 'confirmed')
                            <span class="badge bg-primary fs-6"><i class="bi bi-check-circle"></i> Confirmed</span>
                        @elseif($order->order_status === 'delivered')
                            <span class="badge bg-success fs-6"><i class="bi bi-check2-all"></i> Delivered</span>
                        @elseif($order->order_status === 'cancelled')
                            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Cancelled</span>
                        @elseif($order->order_status === 'returned')
                            <span class="badge bg-secondary fs-6"><i class="bi bi-arrow-counterclockwise"></i>
                                Returned</span>
                        @endif
                    </div>
                </div>
                <div class="row g-2 mb-2 small">
                    <div class="col-6">
                        <span class="text-muted">Payment Method:</span>
                        <strong>{{ strtoupper($order->payment_method) }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Payment Status:</span>
                        <strong class="{{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">{{ ucfirst($order->payment_status) }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Shipping Method:</span>
                        <span>{{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}</span>
                    </div>
                    <div class="col-6">
                        <span class="text-muted">Total Amount:</span>
                        <strong class="text-danger fs-6">৳{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Items Card -->
        <div class="col-md-12">
            <div class="stat-card mb-4 {{ $isFraud ? 'border-danger' : '' }}" style="{{ $isFraud ? 'background-color: #fff5f5 !important; border: 1.5px solid #fca5a5;' : '' }}">
                <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-1"></i> Order Items</h6>

                <!-- Order Items -->
                <table class="table table-bordered align-middle mb-0" style="border-color: #a1a1a1 !important;">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">Image</th>
                            <th>Product</th>
                            <th style="width:80px;">Qty</th>
                            <th style="width:120px;">Price</th>
                            <th style="width:120px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    @if ($item->product_image)
                                        @php
                                            $imgPath = $item->product_image;
                                            if (str_starts_with($imgPath, 'http')) {
                                                $imgSrc = $imgPath;
                                            } elseif (str_starts_with($imgPath, '/storage/')) {
                                                $imgSrc = asset(ltrim($imgPath, '/'));
                                            } else {
                                                $imgSrc = asset('storage/' . $imgPath);
                                            }
                                        @endphp
                                        <img src="{{ $imgSrc }}"
                                            alt="Product Image" style="width: 48px; height: 48px; object-fit: contain; border-radius: 6px; border: 1px solid #eee;">
                                    @else
                                        <div
                                            style="width:48px;height:48px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $item->product_name }}</div>
                                    @if (is_array($item->variants) && count($item->variants) > 0)
                                        <small class="text-muted">
                                            {{ collect($item->variants)->map(fn($v, $k) => ucfirst($k) . ': ' . $v)->join(' · ') }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td>
                                    @if($item->original_price && $item->original_price > $item->price)
                                        <del class="text-muted" style="font-size: 0.85em; color: #6c757d;">৳{{ number_format($item->original_price, 2) }}</del><br>
                                    @endif
                                    ৳{{ number_format($item->price, 2) }}
                                </td>
                                <td class="fw-bold">৳{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Subtotal</td>
                            <td class="fw-bold">৳{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if ($order->coupon_code)
                            <tr class="text-success">
                                <td colspan="4" class="text-end text-success">Discount ({{ $order->coupon_code }})</td>
                                <td class="fw-bold text-success">-৳{{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end">Shipping
                                ({{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }})</td>
                            <td>৳{{ number_format($order->shipping_cost, 2) }}</td>
                        </tr>
                        @if ($order->tax > 0)
                            <tr>
                                <td colspan="4" class="text-end">Tax (5%)</td>
                                <td>৳{{ number_format($order->tax, 2) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5">Grand Total</td>
                            <td class="fw-bold fs-5 text-danger">৳{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
