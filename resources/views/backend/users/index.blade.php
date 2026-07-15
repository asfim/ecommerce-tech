@extends('layouts.backend.app')

@section('title', 'Admin Users')

@section('content')
<div class="clearfix mb-4">
  <h4>Admin Users</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Staff / Admin Accounts</h5>
    <a href="{{ route('admin.users.admins.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-person-plus"></i> Register Staff
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
    <thead class="table-light">
      <tr>
        <th>Email</th>
        <th>Roles</th>
        <th>Permissions (via Roles)</th>
        <th style="width:100px;" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($admins as $admin)
        <tr>
          <td>
            <strong>{{ $admin->email }}</strong>
            @if(auth('admin')->id() == $admin->id)
              <span class="badge bg-success ms-1">You</span>
            @endif
          </td>
          <td>
            @forelse($admin->roles as $role)
              <span class="badge bg-primary me-1">{{ $role->name }}</span>
            @empty
              <span class="text-muted small">No role assigned</span>
            @endforelse
          </td>
          <td>
            @php $perms = $admin->getPermissionsViaRoles(); @endphp
            @forelse($perms as $perm)
              <span class="badge bg-light text-secondary border me-1 mb-1">{{ $perm->name }}</span>
            @empty
              <span class="text-muted small">None</span>
            @endforelse
          </td>
          <td class="text-center">
            <a href="{{ route('admin.users.admins.edit', $admin->id) }}"
               class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center text-muted">No staff accounts registered.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
