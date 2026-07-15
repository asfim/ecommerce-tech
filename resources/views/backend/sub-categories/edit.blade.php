@extends('layouts.backend.app')

@section('title', 'Edit Sub Category')

@section('content')
<div class="clearfix mb-4">
  <h4>Edit Sub Category</h4>
</div>

<div class="stat-card">
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.sub-categories.update', $subCategory) }}">
    @csrf
    @method('PUT')

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Parent Category <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select" required>
          <option value="">— Select Category —</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id', $subCategory->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Sub Category Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $subCategory->name) }}" required style="border-color: #a1a1a1 !important;">
        <div class="form-text text-muted">Current slug: <code>{{ $subCategory->slug }}</code> (auto-updated on save)</div>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="mb-4">
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
               {{ old('is_active', $subCategory->is_active) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="isActive">Active</label>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Sub Category</button>
    <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
