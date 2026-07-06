@extends('layouts.backend.app')

@section('title', 'Edit Role')

@section('content')
<div class="clearfix mb-4">
  <h4>Edit Role: {{ $role->name }}</h4>
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

  <form method="POST" action="{{ route('admin.roles.update', $role) }}">
    @csrf
    @method('PUT')
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
      </div>
      
      <div class="col-md-6 mb-3">
        <label class="form-label">Guard Name</label>
        <input type="text" class="form-control bg-light" value="{{ $role->guard_name }}" disabled>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label d-block fw-semibold mb-2">Assign Permissions</label>
      
      <div class="row g-2 border rounded p-3 bg-light">
        @foreach($permissions as $permission)
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
              <label class="form-check-label small" for="perm_{{ $permission->id }}">
                {{ $permission->name }} <span class="text-muted text-xsmall">({{ $permission->guard_name }})</span>
              </label>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Role</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
