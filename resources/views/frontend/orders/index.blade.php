@extends('layouts.frontend.app')

@section('title', 'My Orders')

@section('content')
<div class="clearfix mb-4">
  <h4>My Orders</h4>
</div>

<div class="stat-card">
  <!-- Search and Per Page Filter -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('user.orders.index') }}" class="d-flex align-items-center gap-2">
      <label class="small text-muted mb-0">Show</label>
      <select name="per_page" class="form-select form-select-sm" style="width: 85px;" onchange="this.form.submit()">
        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
        <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
        <option value="30" {{ request('per_page') == '30' ? 'selected' : '' }}>30</option>
        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
      </select>
      <label class="small text-muted mb-0">entries</label>
      
      @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
      @endif
    </form>

    <form method="GET" action="{{ route('user.orders.index') }}" class="d-flex gap-2">
      @if(request('per_page'))
        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
      @endif
      <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by product name and invoice ..." style="width: 240px;">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
      @if(request('search') || request('per_page'))
        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
      @endif
    </form>
  </div>

  @if($orders->count() > 0)
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th style="width:60px;">#</th>
          <th>Invoice</th>
          <th>Products</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date</th>
          <th style="width:120px;">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td><span class="badge bg-dark font-monospace">{{ $order->invoice_no }}</span></td>
            <td>
              @foreach($order->items as $item)
                <div class="small fw-semibold text-wrap">{{ $item->product_name }} <span class="text-muted">(x{{ $item->quantity }})</span></div>
              @endforeach
            </td>
            <td class="fw-bold">৳{{ number_format($order->total, 2) }}</td>
            <td>
              @if($order->order_status === 'pending')
                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Pending</span>
              @elseif($order->order_status === 'confirmed')
                <span class="badge bg-primary"><i class="bi bi-check-circle"></i> Confirmed</span>
              @elseif($order->order_status === 'delivered')
                <span class="badge bg-success"><i class="bi bi-check2-all"></i> Delivered</span>
              @elseif($order->order_status === 'cancelled')
                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Cancelled</span>
              @endif
            </td>
            <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('order.invoice', $order->invoice_no) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View Invoice">
                  <i class="bi bi-file-earmark-text"></i>
                </a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @if($orders->hasPages())
      <div class="d-flex justify-content-center mt-3">
        {{ $orders->withQueryString()->links() }}
      </div>
    @endif
  @else
    <div class="text-center text-muted py-5">
      <i class="bi bi-inbox fs-1 d-block mb-3"></i>
      @if(request('search'))
        <p>No orders found matching "{{ request('search') }}".</p>
        <a href="{{ route('user.orders.index') }}" class="btn btn-primary btn-sm">Clear Search</a>
      @else
        <p>No orders yet.</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Continue Shopping</a>
      @endif
    </div>
  @endif
</div>
@endsection
