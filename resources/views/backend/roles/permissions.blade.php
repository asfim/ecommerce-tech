@extends('layouts.backend.app')

@section('title', 'Permissions')

@section('content')
<div class="clearfix mb-4">
  <h4><i class="bi bi-key me-2 text-primary"></i>Permissions</h4>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@php
  $actionOrder = ['view', 'create', 'edit', 'delete'];
  $actionColors = [
    'view'   => 'primary',
    'create' => 'success',
    'edit'   => 'warning',
    'delete' => 'danger',
  ];
  $actionIcons = [
    'view'   => 'bi-eye',
    'create' => 'bi-plus-circle',
    'edit'   => 'bi-pencil',
    'delete' => 'bi-trash',
  ];

  // Group permissions by module, skip manage-*
  $grouped = [];
  foreach ($permissions->where('guard_name', 'admin') as $perm) {
    $parts = explode('-', $perm->name, 2);
    $action = $parts[0];
    $module = $parts[1] ?? 'other';
    if ($action === 'manage') continue;
    $grouped[$module][$action] = $perm;
  }
  ksort($grouped);
@endphp

<div class="row g-4">
  {{-- Grouped Permissions Table --}}
  <div class="col-lg-8">
    <div class="stat-card">
      <h5 class="mb-4 fw-bold"><i class="bi bi-shield-check me-2 text-primary"></i>All Permissions <span class="badge bg-primary ms-1">{{ $permissions->where('guard_name', 'admin')->count() }}</span></h5>

      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0" style="border-color: #a1a1a1 !important;">
          <thead class="table-dark">
            <tr>
              <th style="width:180px;">Module</th>
              @foreach($actionOrder as $action)
                <th class="text-center" style="width:90px;">
                  <i class="bi {{ $actionIcons[$action] }} me-1"></i>{{ ucfirst($action) }}
                </th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($grouped as $module => $actions)
              <tr>
                <td>
                  <span class="fw-semibold text-capitalize">{{ str_replace('-', ' ', $module) }}</span>
                </td>
                @foreach($actionOrder as $action)
                  <td class="text-center">
                    @if(isset($actions[$action]))
                      @php $perm = $actions[$action]; @endphp
                      <span class="badge bg-{{ $actionColors[$action] }}-subtle text-{{ $actionColors[$action] }} border border-{{ $actionColors[$action] }}-subtle px-2 py-1"
                            style="font-size:11px; border-radius:6px;"
                            title="{{ $perm->name }}">
                        <i class="bi {{ $actionIcons[$action] }}"></i>
                      </span>
                    @else
                      <span class="text-muted small">—</span>
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-3 d-flex flex-wrap gap-3">
        @foreach($actionOrder as $action)
          <span class="badge bg-{{ $actionColors[$action] }}-subtle text-{{ $actionColors[$action] }} border border-{{ $actionColors[$action] }}-subtle px-2 py-1" style="font-size:11px;">
            <i class="bi {{ $actionIcons[$action] }} me-1"></i>{{ ucfirst($action) }}
          </span>
        @endforeach
        <span class="text-muted small align-self-center ms-1">— = not applicable</span>
      </div>
    </div>
  </div>

  {{-- Quick Create Permission Column --}}
  <div class="col-lg-4">
    <div class="stat-card">
      <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Create Permission</h5>

      <form method="POST" action="{{ route('admin.permissions.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label small fw-semibold">Module</label>
          <select name="_module" id="permModule" class="form-select form-select-sm" onchange="buildPermName()">
            <option value="">— Choose Module —</option>
            @foreach(array_keys($grouped) as $mod)
              <option value="{{ $mod }}">{{ ucfirst(str_replace('-', ' ', $mod)) }}</option>
            @endforeach
            <option value="__custom__">Custom…</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Action</label>
          <div class="d-flex flex-wrap gap-2" id="actionBtns">
            @foreach($actionOrder as $action)
              <button type="button"
                class="btn btn-outline-{{ $actionColors[$action] }} btn-sm action-btn"
                data-action="{{ $action }}"
                onclick="selectAction(this)">
                <i class="bi {{ $actionIcons[$action] }} me-1"></i>{{ ucfirst($action) }}
              </button>
            @endforeach
          </div>
          <input type="hidden" id="selectedAction" value="">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Permission Name</label>
          <input type="text" name="name" id="permNameInput" class="form-control form-control-sm font-monospace"
                 placeholder="e.g. create-products" required
                 value="{{ old('name') }}" style="border-color: #a1a1a1 !important;">
          <div class="form-text">Format: <code>action-module</code></div>
        </div>

        @error('name')
          <div class="text-danger small mb-2">{{ $message }}</div>
        @enderror

        <input type="hidden" name="guard_name" value="admin">
        <button type="submit" class="btn btn-primary btn-sm w-100">
          <i class="bi bi-plus-lg me-1"></i>Create Permission
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function buildPermName() {
    const module = document.getElementById('permModule').value;
    const action = document.getElementById('selectedAction').value;
    const input  = document.getElementById('permNameInput');
    if (module === '__custom__') { input.value = ''; return; }
    if (module && action) { input.value = action + '-' + module; }
    else if (action && !module) { input.value = action + '-'; }
    else if (module && !action) { input.value = '-' + module; }
  }

  function selectAction(btn) {
    document.querySelectorAll('.action-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedAction').value = btn.dataset.action;
    buildPermName();
  }
</script>
@endpush
