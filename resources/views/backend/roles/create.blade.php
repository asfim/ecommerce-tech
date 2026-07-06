@extends('layouts.backend.app')

@section('title', 'Add Role')

@section('content')
<div class="clearfix mb-4">
  <h4>Add Role</h4>
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

  <form method="POST" action="{{ route('admin.roles.store') }}">
    @csrf
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Manager" value="{{ old('name') }}" required>
      </div>
      
      <div class="col-md-6 mb-3">
        <label class="form-label">Guard Name</label>
        <select name="guard_name" id="guardSelect" class="form-select" required>
          <option value="admin" {{ old('guard_name') == 'admin' ? 'selected' : '' }}>admin (Backend)</option>
          <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>web (Frontend)</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label d-block fw-semibold mb-2">Assign Permissions</label>
      
      <div class="row g-2 border rounded p-3 bg-light">
        @foreach($permissions as $permission)
          <div class="col-md-4 permission-checkbox-item" data-guard="{{ $permission->guard_name }}">
            <div class="form-check">
              <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
              <label class="form-check-label small" for="perm_{{ $permission->id }}">
                {{ $permission->name }} <span class="text-muted text-xsmall">({{ $permission->guard_name }})</span>
              </label>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Role</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const guardSelect = document.getElementById('guardSelect');
      const items = document.querySelectorAll('.permission-checkbox-item');

      function filterPermissions() {
          const selectedGuard = guardSelect.value;
          items.forEach(item => {
              const guard = item.getAttribute('data-guard');
              if (guard === selectedGuard) {
                  item.style.display = 'block';
              } else {
                  item.style.display = 'none';
                  // Uncheck hidden checkboxes
                  const checkbox = item.querySelector('.permission-checkbox');
                  if (checkbox) checkbox.checked = false;
              }
          });
      }

      guardSelect.addEventListener('change', filterPermissions);
      filterPermissions(); // run initially
  });
</script>
@endpush
