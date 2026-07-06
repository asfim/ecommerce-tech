@extends('layouts.backend.app')

@section('title', 'Roles')

@section('content')
<div class="clearfix mb-4">
  <h4>Roles</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>All Roles</h5>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Role</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:180px;">Role Name</th>
        <th style="width:100px;">Guard</th>
        <th>Permissions Assigned</th>
        <th style="width:110px;" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($roles as $role)
        <tr>
          <td><strong>{{ $role->name }}</strong></td>
          <td><span class="badge bg-secondary">{{ $role->guard_name }}</span></td>
          <td>
            @forelse($role->permissions as $permission)
              <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1 mb-1">
                {{ $permission->name }}
              </span>
            @empty
              <span class="text-muted small">No permissions assigned</span>
            @endforelse
          </td>
          <td class="text-center">
            @if($role->name !== 'Super Admin')
              <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this role?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
              </form>
            @else
              <span class="text-muted small">Locked</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
