@extends('layouts.backend.app')

@section('title', 'Company Settings')

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
  <h4>Company Settings</h4>
</div>

<div class="stat-card">
  @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('admin.settings.company.update') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
      <label class="form-label fw-bold">Company Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $settings['name'] ?? '') }}" required>
      @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Site Name</label>
      <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
      @error('site_name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Company Logo</label>
      @if(!empty($settings['logo']))
        <div class="mb-2">
          <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Company Logo" class="img-thumbnail" style="max-height: 80px;">
        </div>
      @endif
      <input type="file" name="logo" class="form-control">
      <div class="form-text text-muted">Recommended: Landscape orientation with a transparent background. Max size: 2MB.</div>
      @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Company Favicon</label>
      @if(!empty($settings['favicon']))
        <div class="mb-2">
          <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Company Favicon" class="img-thumbnail" style="max-height: 48px; width: 48px; object-fit: contain;">
        </div>
      @endif
      <input type="file" name="favicon" class="form-control">
      <div class="form-text text-muted">Recommended: Square format (e.g. 32x32 or 48x48 pixels). Max size: 1MB.</div>
      @error('favicon') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Company Address</label>
      <textarea name="address" class="form-control" rows="3" placeholder="e.g. House 12, Road 5, Dhanmondi, Dhaka">{{ old('address', $settings['address'] ?? '') }}</textarea>
      @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Company Phone Number</label>
      <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="e.g. +8801700000000">
      @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Company Email Address</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $settings['email'] ?? '') }}" placeholder="e.g. info@ecommerce.com">
      @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">WhatsApp Number</label>
      <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="e.g. +8801700000000">
      @error('whatsapp') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Google Maps Embed Link (Iframe Src or URL)</label>
      <textarea name="google_map" class="form-control" rows="3" placeholder="Paste Google Maps iframe src attribute or embed URL">{{ old('google_map', $settings['google_map'] ?? '') }}</textarea>
      <div class="form-text text-muted">Paste the Google Maps embed URL (e.g. <code>https://www.google.com/maps/embed?pb=...</code>) or the full <code>&lt;iframe&gt;</code> HTML code.</div>
      @error('google_map') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <hr class="my-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-share me-2 text-primary"></i>Social Media Links</h5>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Facebook URL</label>
        <input type="url" name="facebook" class="form-control form-control-sm" value="{{ old('facebook', $settings['facebook'] ?? '') }}" placeholder="https://facebook.com/yourpage">
        @error('facebook') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Twitter / X URL</label>
        <input type="url" name="twitter" class="form-control form-control-sm" value="{{ old('twitter', $settings['twitter'] ?? '') }}" placeholder="https://twitter.com/yourhandle">
        @error('twitter') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">YouTube URL</label>
        <input type="url" name="youtube" class="form-control form-control-sm" value="{{ old('youtube', $settings['youtube'] ?? '') }}" placeholder="https://youtube.com/c/yourchannel">
        @error('youtube') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Instagram URL</label>
        <input type="url" name="instagram" class="form-control form-control-sm" value="{{ old('instagram', $settings['instagram'] ?? '') }}" placeholder="https://instagram.com/yourprofile">
        @error('instagram') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Pinterest URL</label>
        <input type="url" name="pinterest" class="form-control form-control-sm" value="{{ old('pinterest', $settings['pinterest'] ?? '') }}" placeholder="https://pinterest.com/yourprofile">
        @error('pinterest') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">LinkedIn URL</label>
        <input type="url" name="linkedin" class="form-control form-control-sm" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}" placeholder="https://linkedin.com/company/yourcompany">
        @error('linkedin') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Settings</button>
  </form>
</div>
@endsection
