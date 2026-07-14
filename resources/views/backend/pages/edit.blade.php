@extends('layouts.backend.app')

@section('title', 'Edit Page: ' . $page->title)

@section('content')
<div class="clearfix mb-4">
  <h4>
    <i class="bi bi-file-text me-2 text-primary"></i>Edit Page:
    <span class="text-primary">{{ $page->title }}</span>
  </h4>
</div>

<div class="stat-card">
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.pages.update', $page) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label fw-semibold">Page Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Content</label>
      <textarea name="content" id="pageContent" class="form-control" rows="20"
                style="font-family: monospace;">{{ old('content', $page->content) }}</textarea>
      <div class="form-text">You can use plain text or basic HTML (e.g. &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;strong&gt;).</div>
    </div>

    <div class="d-flex gap-2 align-items-center">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>Save Changes
      </button>
      <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
      <a href="{{ route('page.show', $page->slug) }}" class="btn btn-outline-info ms-auto" target="_blank">
        <i class="bi bi-eye me-1"></i>View Page
      </a>
    </div>
  </form>
</div>
@endsection
