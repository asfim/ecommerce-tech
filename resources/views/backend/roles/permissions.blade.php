@extends('layouts.backend.app')

@section('title', 'Permissions')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
        <span class="role">Super Admin</span>
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
  <h4>Permissions</h4>
</div>

<div class="row g-4">
  {{-- Permissions List Column --}}
  <div class="col-md-8">
    <div class="stat-card">
      <h5 class="mb-3 fw-bold"><i class="bi bi-key me-2 text-primary"></i>All Permissions</h5>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Permission Name</th>
            <th style="width:150px;">Guard Name</th>
            <th style="width:180px;">Created At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permissions as $permission)
            <tr>
              <td><strong>{{ $permission->name }}</strong></td>
              <td><span class="badge bg-secondary">{{ $permission->guard_name }}</span></td>
              <td><span class="text-muted small">{{ $permission->created_at->format('Y-m-d H:i') }}</span></td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted">No permissions defined yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Quick Create Permission Column --}}
  <div class="col-md-4">
    <div class="stat-card">
      <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Create Permission</h5>
      
      <form method="POST" action="{{ route('admin.permissions.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label small fw-semibold">Permission Name</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. manage-users" required>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold">Guard Name</label>
          <select name="guard_name" class="form-select" required>
            <option value="admin">admin (Backend)</option>
            <option value="web">web (Frontend)</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-plus-lg"></i> Create Permission</button>
      </form>
    </div>
  </div>
</div>
@endsection
