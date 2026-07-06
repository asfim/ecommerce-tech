@extends('layouts.backend.app')

@section('title', 'Add Category')

@section('content')
<div class="clearfix mb-4">
  <h4>Add Category</h4>
</div>

<div class="stat-card">
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check form-switch">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive"
                 {{ old('is_active', true) ? 'checked' : '' }}>
          <label class="form-check-label fw-semibold" for="isActive">Active</label>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Category</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
