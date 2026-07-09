@extends('layouts.backend.app')

@section('title', 'Order #' . $order->invoice_no)

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
        <span class="role">eCommerce</span>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
        </form>
      </li>
    </ul>
  </div>
  <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
  <h4 class="d-inline-block mb-0">Order Details</h4>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
  <!-- Order Info Card -->
  <div class="col-md-8">
    <div class="stat-card mb-4">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h5 class="mb-1 fw-bold">{{ $order->invoice_no }}</h5>
          <span class="text-muted small">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</span>
        </div>
        <div>
          @if($order->order_status === 'pending')
            <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock"></i> Pending</span>
          @elseif($order->order_status === 'confirmed')
            <span class="badge bg-primary fs-6"><i class="bi bi-check-circle"></i> Confirmed</span>
          @elseif($order->order_status === 'delivered')
            <span class="badge bg-success fs-6"><i class="bi bi-check2-all"></i> Delivered</span>
          @elseif($order->order_status === 'cancelled')
            <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Cancelled</span>
          @endif
        </div>
      </div>

      <!-- Order Items -->
      <table class="table table-bordered align-middle mb-0">
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
          @foreach($order->items as $item)
            <tr>
              <td>
                @if($item->product_image)
                  <img src="{{ asset('storage/' . $item->product_image) }}" style="width:48px;height:48px;object-fit:contain;border-radius:6px;border:1px solid #eee;">
                @else
                  <div style="width:48px;height:48px;background:#f0f0f0;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-image text-muted"></i>
                  </div>
                @endif
              </td>
              <td>
                <div class="fw-bold">{{ $item->product_name }}</div>
                @if(is_array($item->variants) && count($item->variants) > 0)
                  <small class="text-muted">
                    {{ collect($item->variants)->map(fn($v, $k) => ucfirst($k) . ': ' . $v)->join(' · ') }}
                  </small>
                @endif
              </td>
              <td class="text-center">{{ $item->quantity }}</td>
              <td>৳{{ number_format($item->price, 2) }}</td>
              <td class="fw-bold">৳{{ number_format($item->line_total, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="table-light">
          <tr>
            <td colspan="4" class="text-end fw-bold">Subtotal</td>
            <td class="fw-bold">৳{{ number_format($order->subtotal, 2) }}</td>
          </tr>
          @if($order->coupon_code)
            <tr class="text-success">
              <td colspan="4" class="text-end text-success">Discount ({{ $order->coupon_code }})</td>
              <td class="fw-bold text-success">-৳{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
          @endif
          <tr>
            <td colspan="4" class="text-end">Shipping ({{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }})</td>
            <td>৳{{ number_format($order->shipping_cost, 2) }}</td>
          </tr>
          <tr>
            <td colspan="4" class="text-end">Tax (5%)</td>
            <td>৳{{ number_format($order->tax, 2) }}</td>
          </tr>
          <tr>
            <td colspan="4" class="text-end fw-bold fs-5">Grand Total</td>
            <td class="fw-bold fs-5 text-danger">৳{{ number_format($order->total, 2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- Sidebar Info -->
  <div class="col-md-4">
    <!-- Customer Info -->
    <div class="stat-card mb-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Customer</h6>
      <p class="mb-1 fw-bold">{{ $order->customer_name }}</p>
      <p class="mb-1 text-muted small"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone }}</p>
      <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-1"></i> {{ $order->customer_address }}</p>
    </div>

    <!-- Payment Info -->
    <div class="stat-card mb-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2"></i>Payment</h6>
      <p class="mb-1">
        <strong>Method:</strong>
        @if($order->payment_method === 'cod')
          <span class="badge bg-info text-dark">Cash on Delivery</span>
        @else
          <span class="badge bg-primary">SSL Commerz</span>
        @endif
      </p>
      <p class="mb-0">
        <strong>Status:</strong>
        @if($order->payment_status === 'paid')
          <span class="badge bg-success">Paid</span>
        @else
          <span class="badge bg-warning text-dark">Pending</span>
        @endif
      </p>
    </div>

    <!-- Update Status -->
    <div class="stat-card">
      <h6 class="fw-bold mb-3"><i class="bi bi-arrow-repeat me-2"></i>Update Status</h6>
      <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
        @csrf
        @method('PATCH')
        <div class="mb-2">
          <label class="form-label small text-muted mb-1">Order Status</label>
          <select name="order_status" class="form-select form-select-sm">
            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label small text-muted mb-1">Payment Status</label>
          <select name="payment_status" class="form-select form-select-sm">
            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-check-lg me-1"></i> Update Status</button>
      </form>
    </div>
  </div>
</div>
@endsection
