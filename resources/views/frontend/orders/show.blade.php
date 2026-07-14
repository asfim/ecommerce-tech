@extends('layouts.frontend.app')

@section('title', 'Order #' . $order->invoice_no)

@section('content')
<div class="clearfix mb-4">
  <div class="float-end">
    <a href="{{ route('order.invoice', $order->invoice_no) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-text me-1"></i> View Invoice</a>
  </div>
  <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left"></i> Back to Orders</a>
  <h4 class="d-inline-block mb-0">Order Details</h4>
</div>

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
                  <img src="{{ str_starts_with($item->product_image, 'http') ? $item->product_image : asset('storage/' . $item->product_image) }}" style="width:48px;height:48px;object-fit:contain;border-radius:6px;border:1px solid #eee;">
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
          @if($order->tax > 0)
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

  <!-- Sidebar Info -->
  <div class="col-md-4">
    <div class="stat-card mb-3">
      <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Shipping Address</h6>
      <p class="mb-1 fw-bold">{{ $order->customer_name }}</p>
      <p class="mb-1 text-muted small"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone }}</p>
      <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-1"></i> {{ $order->customer_address }}</p>
    </div>

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

    <div class="stat-card">
      <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Shipping</h6>
      <p class="mb-1">
        <strong>Method:</strong>
        {{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}
      </p>
      <p class="mb-0">
        <strong>Cost:</strong> ৳{{ number_format($order->shipping_cost, 2) }}
      </p>
    </div>
  </div>
</div>
@endsection
