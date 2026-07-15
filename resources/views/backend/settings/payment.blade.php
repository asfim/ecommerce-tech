@extends('layouts.backend.app')

@section('title', 'Payment Settings')

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
  <h4>Payment Settings</h4>
</div>

<div class="stat-card">
  @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
  @endif

  <div class="mb-4">
    <h5 class="fw-bold text-dark"><i class="bi bi-credit-card me-2 text-primary"></i>SSLCommerz Payment Gateway Integration</h5>
    <p class="text-muted small">Configure your SSLCommerz merchant API credentials below. Setting these values dynamically enables secure checkout processing on the frontend.</p>
  </div>

  <form method="POST" action="{{ route('admin.settings.payment.update') }}">
    @csrf
    
    <div class="mb-3">
      <label class="form-label fw-bold">Environment (Sandbox Mode)</label>
      <select name="sandbox" class="form-select" required>
        <option value="1" {{ old('sandbox', $settings['sandbox'] ?? '1') == '1' ? 'selected' : '' }}>Sandbox (Test Mode)</option>
        <option value="0" {{ old('sandbox', $settings['sandbox'] ?? '1') == '0' ? 'selected' : '' }}>Live (Production Mode)</option>
      </select>
      @error('sandbox') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">SSLCommerz Store ID</label>
      <input type="text" name="store_id" class="form-control" value="{{ old('store_id', $settings['store_id'] ?? '') }}" placeholder="Enter your SSLCommerz Store ID" required style="border-color: #a1a1a1 !important;">
      @error('store_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">SSLCommerz Store Password</label>
      <input type="text" name="store_password" class="form-control" value="{{ old('store_password', $settings['store_password'] ?? '') }}" placeholder="Enter your SSLCommerz Store Password" required style="border-color: #a1a1a1 !important;">
      @error('store_password') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Default Currency</label>
      <input type="text" name="currency" class="form-control" value="{{ old('currency', $settings['currency'] ?? 'BDT') }}" placeholder="BDT" required style="border-color: #a1a1a1 !important;">
      <div class="form-text text-muted">Usually <code>BDT</code>.</div>
      @error('currency') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Configuration</button>
  </form>
</div>
@endsection
