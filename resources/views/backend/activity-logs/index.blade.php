@extends('layouts.backend.app')

@section('title', 'Activity Logs')

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
  <h4>Activity Logs</h4>
</div>

<div class="stat-card">
  <h5 class="mb-3 fw-bold"><i class="bi bi-list-columns-reverse me-2 text-primary"></i>Audit Log Entries</h5>

  <table class="table table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:180px;">Timestamp</th>
        <th style="width:200px;">User/Staff Email</th>
        <th style="width:120px;">Role/Guard</th>
        <th>Action</th>
        <th>Description</th>
        <th style="width:140px;">IP Address</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
        <tr>
          <td><span class="text-muted small">{{ $log->created_at->format('Y-m-d H:i:s') }}</span></td>
          <td>
            @if($log->user)
              <strong>{{ $log->user->email }}</strong>
            @else
              <span class="text-muted small">System / Guest</span>
            @endif
          </td>
          <td>
            @if($log->user_type === 'App\Models\Admin')
              <span class="badge bg-primary">Admin (Staff)</span>
            @elseif($log->user_type === 'App\Models\User')
              <span class="badge bg-success">User (Customer)</span>
            @else
              <span class="badge bg-secondary">System</span>
            @endif
          </td>
          <td><code class="text-danger">{{ $log->action }}</code></td>
          <td><span class="small">{{ $log->description }}</span></td>
          <td><span class="text-muted small">{{ $log->ip_address }}</span></td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">No activity logs recorded.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-3">
    {{ $logs->links() }}
  </div>
</div>
@endsection
