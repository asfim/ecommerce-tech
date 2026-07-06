@extends('layouts.backend.app')

@section('title', 'Register Customer')

@section('content')
<div class="clearfix mb-4">
  <h4>Register New Customer</h4>
</div>

<div class="stat-card">
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <input type="hidden" name="account_type" value="user">

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" value="{{ old('name') }}" required>
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" placeholder="e.g. customer@example.com" value="{{ old('email') }}" required>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
      </div>
    </div>

    @if($userRoles->isNotEmpty())
    <div class="mb-4">
      <label class="form-label d-block fw-semibold mb-2">Assign Role <span class="text-muted small fw-normal">(optional)</span></label>
      <div class="row g-2 border rounded p-3 bg-light">
        @foreach($userRoles as $role)
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
              <label class="form-check-label small" for="role_{{ $role->id }}">{{ $role->name }}</label>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    <button type="submit" class="btn btn-primary">Register Customer</button>
    <a href="{{ route('admin.users.staff') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
