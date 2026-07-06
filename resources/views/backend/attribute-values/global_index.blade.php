@extends('layouts.backend.app')

@section('title', 'Attribute Values')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
        <span class="role">eCommerce</span>
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
  <h4>Attribute Values</h4>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row g-4">
  <!-- Attribute Selection Card -->
  <div class="col-12">
    <div class="stat-card p-4" style="background: #fff; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
      <div class="row align-items-center">
        <div class="col-md-6">
          <label for="attribute_selector" class="form-label fw-bold"><i class="bi bi-funnel me-1"></i> Select Attribute to Manage Values</label>
          <select id="attribute_selector" class="form-select" onchange="switchAttribute(this.value)">
            @if($attributes->isEmpty())
              <option value="">No attributes available</option>
            @else
              @foreach($attributes as $attr)
                <option value="{{ $attr->id }}" {{ $attribute && $attribute->id == $attr->id ? 'selected' : '' }}>
                  {{ $attr->name }}
                </option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
          <a href="{{ route('admin.attributes.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Create New Attribute
          </a>
        </div>
      </div>
    </div>
  </div>

  @if($attribute)
    <!-- Left Column: Add Value Form -->
    <div class="col-md-4">
      <div class="stat-card p-4" style="background: #fff; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-1 text-primary"></i> Add Value</h5>
        <p class="text-muted small">Create a new value for the attribute <strong>{{ $attribute->name }}</strong>.</p>
        
        <form method="POST" action="{{ route('admin.attributes.values.store', $attribute) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label small fw-bold">Value Name</label>
            <input type="text" name="value" class="form-control" placeholder="e.g., Red, XL, 128GB" value="{{ old('value') }}" required autocomplete="off">
            @error('value') 
              <div class="text-danger small mt-1">{{ $message }}</div> 
            @enderror
          </div>
          <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-check-lg"></i> Save Value</button>
        </form>
      </div>
    </div>

    <!-- Right Column: Values List -->
    <div class="col-md-8">
      <div class="stat-card p-4" style="background: #fff; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0">
            <i class="bi bi-list-ul me-1 text-primary"></i> Values for <strong>{{ $attribute->name }}</strong>
          </h5>
          <span class="badge bg-secondary">{{ $values->total() }} total</span>
        </div>

        @if($values->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="bi bi-inboxes display-4 d-block mb-3 text-secondary"></i>
            <p class="mb-0">No values found for this attribute yet.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width: 80px;">#</th>
                  <th>Value</th>
                  <th style="width: 120px;" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($values as $val)
                  <tr>
                    <td>{{ $val->id }}</td>
                    <td><span class="badge bg-light text-dark border p-2">{{ $val->value }}</span></td>
                    <td class="text-end">
                      <a href="{{ route('admin.attributes.values.edit', [$attribute, $val]) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('admin.attributes.values.destroy', [$attribute, $val]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this value?')">
                        @csrf 
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <div class="mt-3">
            {{ $values->links() }}
          </div>
        @endif
      </div>
    </div>
  @else
    <!-- No Attributes State -->
    <div class="col-12">
      <div class="stat-card p-5 text-center" style="background: #fff; border-radius: 8px; border: 1px solid #eee; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <i class="bi bi-palette-fill display-1 text-muted mb-4 d-block"></i>
        <h4 class="fw-bold">No Attributes Configured</h4>
        <p class="text-muted mx-auto mb-4" style="max-width: 450px;">
          You need to create at least one attribute (e.g., Color, Size) before you can manage and add attribute values.
        </p>
        <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i> Create Your First Attribute
        </a>
      </div>
    </div>
  @endif
</div>

<script>
  function switchAttribute(attributeId) {
    if (attributeId) {
      window.location.href = "{{ route('admin.attribute-values.index') }}?attribute_id=" + attributeId;
    }
  }
</script>
@endsection
