@extends('layouts.backend.app')

@section('title', 'Blog Posts')

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
  <h4>Blog Posts</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Blog Post</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
    <thead>
      <tr>
        <th style="width: 60px;">#</th>
        <th style="width: 80px;">Image</th>
        <th>Title</th>
        <th>Slug</th>
        <th style="width: 100px;" class="text-center">Views</th>
        <th style="width: 120px;" class="text-center">Status</th>
        <th style="width: 150px;">Created At</th>
        <th style="width: 120px;" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($posts as $post)
        <tr>
          <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
          <td>
            @if($post->image)
              <img src="{{ asset('storage/' . $post->image) }}" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
            @else
              <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px; font-size: 11px;">No Img</div>
            @endif
          </td>
          <td class="fw-semibold">{{ $post->title }}</td>
          <td class="text-muted">{{ $post->slug }}</td>
          <td class="text-center">{{ number_format($post->views) }}</td>
          <td class="text-center">
            <span class="badge bg-{{ $post->is_active ? 'success' : 'secondary' }}">
              {{ $post->is_active ? 'Published' : 'Draft' }}
            </span>
          </td>
          <td>{{ $post->created_at->format('d M Y, h:i A') }}</td>
          <td class="text-center">
            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog post?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">No blog posts found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="d-flex justify-content-center mt-3">
    {{ $posts->links() }}
  </div>
</div>
@endsection
