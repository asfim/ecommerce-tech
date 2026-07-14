@extends('layouts.backend.app')

@section('title', 'Customers')

@section('content')
<div class="clearfix mb-4">
  <h4>Customers</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Registered Customers</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i> Register Customer</a>
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
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th>Registered At</th>
        <th style="width:120px;" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @forelse($user->roles as $role)
              <span class="badge bg-success me-1">{{ $role->name }}</span>
            @empty
              <span class="text-muted small">No role</span>
            @endforelse
          </td>
          <td><span class="text-muted small">{{ $user->created_at->format('Y-m-d') }}</span></td>
          <td class="text-center">
            <a href="{{ route('admin.users.edit', ['type' => 'user', 'id' => $user->id]) }}"
               class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.users.destroy', ['type' => 'user', 'id' => $user->id]) }}"
                  method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-3">No customers registered yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
