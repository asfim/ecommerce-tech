@extends('layouts.backend.app')

@section('title', 'Orders')

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
  <h4>Orders</h4>
</div>

<!-- Status Filter Tabs -->
<div class="d-flex gap-2 mb-3 flex-wrap">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
    All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
  </a>
  <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
    Pending <span class="badge bg-warning text-dark ms-1">{{ $statusCounts['pending'] }}</span>
  </a>
  <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }}">
    Confirmed <span class="badge bg-primary ms-1">{{ $statusCounts['confirmed'] }}</span>
  </a>
  <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
    Delivered <span class="badge bg-success ms-1">{{ $statusCounts['delivered'] }}</span>
  </a>
</div>

<div class="stat-card">
  <!-- Search -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by invoice, name, phone..." style="width:280px;">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
    </form>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead>
      <tr>
        <th style="width:60px;">#</th>
        <th>Invoice</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Date</th>
        <th style="width:100px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td>{{ $order->id }}</td>
          <td><span class="badge bg-dark font-monospace">{{ $order->invoice_no }}</span></td>
          <td>{{ $order->customer_name }}</td>
          <td>{{ $order->customer_phone }}</td>
          <td class="fw-bold">৳{{ number_format($order->total, 2) }}</td>
          <td>
            @if($order->payment_method === 'cod')
              <span class="badge bg-info text-dark">COD</span>
            @else
              <span class="badge bg-primary">SSL Commerz</span>
            @endif
          </td>
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
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center text-muted py-4">No orders found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-3">
      {{ $orders->withQueryString()->links() }}
    </div>
  @endif
</div>
@endsection
