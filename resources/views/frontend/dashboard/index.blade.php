@extends('layouts.frontend.app')

@section('title', 'My Dashboard')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::user()->name }}</span>
        <span class="role">My Account</span>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ route('user.logout') }}">
          @csrf
          <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
        </form>
      </li>
    </ul>
  </div>
  <h4>Dashboard</h4>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <p>Total Orders</p>
            <h2>{{ $totalOrders }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Wishlist</p>
            <h2>0</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Coupons</p>
            <h2>0</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Reviews</p>
            <h2>0</h2>
        </div>
    </div>
</div>

@if($totalOrders > 0)
<div class="mt-4">
    <h5 class="mb-3">Recent Orders</h5>
    @php
        $recentOrders = \App\Models\Order::where('user_id', auth()->id())->latest()->take(5)->get();
    @endphp
    <div class="stat-card">
        <table class="table table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td><span class="badge bg-dark font-monospace">{{ $order->invoice_no }}</span></td>
                        <td class="fw-bold">৳{{ number_format($order->total, 2) }}</td>
                        <td>
                            @if($order->order_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->order_status === 'confirmed')
                                <span class="badge bg-primary">Confirmed</span>
                            @elseif($order->order_status === 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->order_status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-3">
            <a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline-dark">View All Orders</a>
        </div>
    </div>
</div>
@endif
@endsection
