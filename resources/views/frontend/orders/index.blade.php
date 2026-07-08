@extends('layouts.frontend.app')

@section('title', 'My Orders')

@section('content')
<div class="clearfix mb-4">
  <h4>My Orders</h4>
</div>

<div class="stat-card">
  @if($orders->count() > 0)
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th style="width:60px;">#</th>
          <th>Invoice</th>
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
              <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View">
                <i class="bi bi-eye"></i>
              </a>
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
      <p>No orders yet.</p>
      <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Continue Shopping</a>
    </div>
  @endif
</div>
@endsection
