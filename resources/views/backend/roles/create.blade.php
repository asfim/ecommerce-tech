@extends('layouts.backend.app')

@section('title', 'Add Role')

@section('content')
    <div class="clearfix mb-4">
        <h4>Add Role</h4>
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

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Role Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Manager"
                        value="{{ old('name') }}" required>
                </div>
                <input type="hidden" name="guard_name" id="guardSelect" value="admin">
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
                        <div class="col-12 col-md-6 col-lg-4 permission-module-card">
                            <div class="card h-100 border border-light shadow-sm">
                                <div class="card-header bg-light border-0 fw-bold py-2">
                                    {{ preg_replace('/(?<!^)(?=[A-Z])/', ' ', $moduleName) }}
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        @foreach ($modulePerms as $permission)
                                            <div class="col-6 permission-checkbox-item"
                                                data-guard="{{ $permission->guard_name }}">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                        name="permissions[]" value="{{ $permission->id }}"
                                                        id="perm_{{ $permission->id }}">
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

            <button type="submit" class="btn btn-primary">Save Role</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guardSelect = document.getElementById('guardSelect');
            const items = document.querySelectorAll('.permission-checkbox-item');
            const cards = document.querySelectorAll('.permission-module-card');

            function filterPermissions() {
                const selectedGuard = guardSelect.value;

                items.forEach(item => {
                    const guard = item.getAttribute('data-guard');
                    if (guard === selectedGuard) {
                        item.classList.remove('d-none');
                    } else {
                        item.classList.add('d-none');
                        const checkbox = item.querySelector('.permission-checkbox');
                        if (checkbox) checkbox.checked = false;
                    }
                });

                cards.forEach(card => {
                    const visibleItems = card.querySelectorAll('.permission-checkbox-item:not(.d-none)');
                    if (visibleItems.length > 0) {
                        card.classList.remove('d-none');
                    } else {
                        card.classList.add('d-none');
                    }
                });
            }

            guardSelect.addEventListener('change', filterPermissions);
            filterPermissions(); // run initially
        });
    </script>
@endpush
