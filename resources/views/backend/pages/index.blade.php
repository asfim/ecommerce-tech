@extends('layouts.backend.app')

@section('title', 'Pages')

@section('content')
<div class="clearfix mb-4">
  <h4><i class="bi bi-file-text me-2 text-primary"></i>Pages</h4>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="stat-card">
  <table class="table table-bordered align-middle mb-0">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Slug</th>
        <th>Last Updated</th>
        <th style="width:100px;" class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pages as $page)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td><strong>{{ $page->title }}</strong></td>
          <td><code>{{ $page->slug }}</code></td>
          <td class="text-muted small">{{ $page->updated_at->format('d M Y, h:i A') }}</td>
          <td class="text-center">
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i> Edit
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">No pages found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
