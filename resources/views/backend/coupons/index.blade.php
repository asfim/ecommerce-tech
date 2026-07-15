@extends('layouts.backend.app')

@section('title', 'Coupons')

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
  <h4>Coupons</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Coupon</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered" style="border-color: #a1a1a1 !important;">
    <thead>
      <tr>
        <th>#</th>
        <th>Code</th>
        <th>Type</th>
        <th>Value</th>
        <th>Min Order Amount</th>
        <th>Expires At</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($coupons as $coupon)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><span class="badge bg-dark font-monospace fs-6">{{ $coupon->code }}</span></td>
          <td>{{ ucfirst($coupon->type) }}</td>
          <td>
            @if($coupon->type === 'percent')
              {{ $coupon->value }}%
            @else
              ৳{{ number_format($coupon->value, 2) }}
            @endif
          </td>
          <td>৳{{ number_format($coupon->min_order_amount, 2) }}</td>
          <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y, h:i A') : 'N/A' }}</td>
          <td>
            <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
              {{ $coupon->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">No coupons found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $coupons->links() }}
</div>
@endsection
