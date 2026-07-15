@extends('layouts.backend.app')

@section('title', 'Edit Blog Post')

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
  <h4>Edit Blog Post</h4>
</div>

<div class="stat-card">
  <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label fw-bold">Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $blogPost->title) }}" placeholder="e.g. Top 10 E-commerce Trends" required style="border-color: #a1a1a1 !important;">
      @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Featured Image</label>
      @if($blogPost->image)
        <div class="mb-2">
          <img src="{{ asset('storage/' . $blogPost->image) }}" class="rounded border img-thumbnail" style="max-height: 150px;">
        </div>
      @endif
      <input type="file" name="image" class="form-control" accept="image/*" style="border-color: #a1a1a1 !important;">
      <div class="text-muted small mt-1">Leave blank to keep current image. Recommended size: 1200x600 pixels. Max size: 2MB.</div>
      @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Short Summary</label>
      <textarea name="summary" class="form-control" rows="3" placeholder="Brief outline of the blog post to display on list pages..." required>{{ old('summary', $blogPost->summary) }}</textarea>
      @error('summary') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Post Content (HTML allowed)</label>
      <textarea name="body" class="form-control" rows="12" placeholder="Write full HTML or plain text content here..." required>{{ old('body', $blogPost->body) }}</textarea>
      @error('body') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3 form-check form-switch">
      <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $blogPost->is_active) ? 'checked' : '' }}>
      <label class="form-check-label fw-semibold" for="is_active">Publish immediately (if unchecked, it will be saved as Draft)</label>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Post</button>
      <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
