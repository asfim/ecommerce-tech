@extends('layouts.backend.app')

@section('title', 'Add Coupon')

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
  <h4>Add Coupon</h4>
</div>

<div class="stat-card">
  <form method="POST" action="{{ route('admin.coupons.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Coupon Code</label>
      <input type="text" name="code" class="form-control font-monospace" value="{{ old('code') }}" placeholder="e.g. SAVE10" required>
      @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Discount Type</label>
      <select name="type" class="form-select" required>
        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
      </select>
      @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Discount Value</label>
      <input type="number" name="value" step="0.01" class="form-control" value="{{ old('value') }}" placeholder="e.g. 10" required>
      @error('value') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Minimum Order Amount (৳)</label>
      <input type="number" name="min_order_amount" step="0.01" class="form-control" value="{{ old('min_order_amount', '0.00') }}" placeholder="e.g. 200" required>
      @error('min_order_amount') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Expires At</label>
      <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
      @error('expires_at') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Active</label>
    </div>

    <button type="submit" class="btn btn-primary">Save Coupon</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
