@extends('layouts.backend.app')

@section('title', 'Edit Role')

@section('content')
    <div class="clearfix mb-4">
        <h4>Edit Role: {{ $role->name }}</h4>
    </div>

    <div class="stat-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
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
                    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}"
                        required style="border-color: #a1a1a1 !important;">
                </div>

                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">Guard Name</label>
                    <input type="text" class="form-control bg-light" value="{{ $role->guard_name }}" disabled style="border-color: #a1a1a1 !important;">
                </div> --}}
            </div>

            @php
                $groupedPermissions = $permissions->groupBy(function ($permission) {
                    $parts = explode('-', $permission->name);
                    if (count($parts) > 1) {
                        return ucfirst($parts[1]);
                    }
                    return 'Others';
                });
            @endphp

            <div class="mb-4">
                <label class="form-label d-block fw-bold mb-3">Assign Permissions</label>

                <div id="permissions-container" class="row g-3">
                    @foreach ($groupedPermissions as $moduleName => $modulePerms)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border border-light shadow-sm">
                                <div class="card-header bg-light border-0 fw-bold py-2">
                                    {{ preg_replace('/(?<!^)(?=[A-Z])/', ' ', $moduleName) }}
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        @foreach ($modulePerms as $permission)
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                        {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                                    <label class="form-check-label small text-capitalize"
                                                        for="perm_{{ $permission->id }}">
                                                        {{ str_replace('-', ' ', explode('-', $permission->name)[0]) }}
                                                        {{-- <span
                                                            class="text-muted text-xsmall">({{ $permission->guard_name }})</span> --}}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
