@extends('layouts.backend.app')

@section('title', 'Add Attribute Value')

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
  <h4>Add Attribute Value: {{ $attribute->name }}</h4>
</div>

<div class="stat-card">
  <form method="POST" action="{{ route('admin.attributes.values.store', $attribute) }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Value</label>
      <input type="text" name="value" class="form-control" value="{{ old('value') }}" required>
      @error('value') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection