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

  <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
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
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" id="categoryImage">
        @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        <div class="mt-2" id="imagePreviewWrapper" style="display:none;">
          <img id="imagePreview" src="" alt="Preview" class="rounded" style="max-height:120px;">
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Category</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>
  document.getElementById('categoryImage').addEventListener('change', function(e) {
    const wrapper = document.getElementById('imagePreviewWrapper');
    const preview = document.getElementById('imagePreview');
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        preview.src = ev.target.result;
        wrapper.style.display = 'block';
      };
      reader.readAsDataURL(e.target.files[0]);
    } else {
      wrapper.style.display = 'none';
    }
  });
</script>
@endsection
