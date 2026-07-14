@extends('layouts.backend.app')

@section('title', 'Courier Settings')

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
  <h4>Courier Settings</h4>
</div>

<div class="stat-card">
  @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
  @endif

  <div class="mb-4">
    <h5 class="fw-bold text-dark"><i class="bi bi-truck me-2 text-primary"></i>Steadfast Courier API Integration</h5>
    <p class="text-muted small">Configure your Steadfast Courier API credentials below. When configured, you can automatically send orders to Steadfast Courier directly from the Orders Management panel.</p>
  </div>

  <form method="POST" action="{{ route('admin.settings.courier.update') }}">
    @csrf
    
    <div class="mb-3">
      <label class="form-label fw-bold">Steadfast API Key</label>
      <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $settings['api_key'] ?? '') }}" placeholder="Enter your Steadfast API Key" required>
      @error('api_key') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Steadfast Secret Key</label>
      <input type="password" name="secret_key" class="form-control" value="{{ old('secret_key', $settings['secret_key'] ?? '') }}" placeholder="Enter your Steadfast Secret Key" required>
      @error('secret_key') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Steadfast API Base URL</label>
      <input type="text" name="base_url" class="form-control" value="{{ old('base_url', $settings['base_url'] ?? 'https://portal.packnplay.com/api/v1') }}" placeholder="https://portal.packnplay.com/api/v1" required>
      <div class="form-text text-muted">Use <code>https://portal.packnplay.com/api/v1</code> for live production (Steadfast default).</div>
      @error('base_url') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Configuration</button>
  </form>
</div>
@endsection
