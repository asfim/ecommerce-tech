@extends('layouts.backend.app')

@section('title', 'Edit Staff Account')

@section('content')
<div class="clearfix mb-4">
  <h4>Edit Staff Account</h4>
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

  <form method="POST" action="{{ route('admin.users.admins.update', $account->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $account->email) }}" required>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">
          New Password <span class="text-muted small fw-normal">(leave blank to keep current)</span>
        </label>
        <input type="password" name="password" class="form-control" placeholder="New password">
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label d-block fw-semibold mb-2">
        Assign Role <span class="text-muted small fw-normal">(determines what this staff can access)</span>
      </label>

      @if($adminRoles->isNotEmpty())
        <div class="row g-2 border rounded p-3 bg-light">
          @foreach($adminRoles as $role)
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                       id="role_{{ $role->id }}" {{ $account->hasRole($role) ? 'checked' : '' }}>
                <label class="form-check-label small fw-semibold" for="role_{{ $role->id }}">
                  {{ $role->name }}
                </label>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-warning py-2 mb-0">
          <i class="bi bi-exclamation-triangle me-1"></i>
          No admin roles defined yet. <a href="{{ route('admin.roles.create') }}">Create a role first.</a>
        </div>
      @endif
    </div>

    <button type="submit" class="btn btn-primary">Update Staff</button>
    <a href="{{ route('admin.users.admins') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
