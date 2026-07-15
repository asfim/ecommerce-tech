@extends('layouts.backend.app')

@section('title', 'Sub Categories')

@section('content')
<div class="clearfix mb-4">
  <h4>Sub Categories</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2 text-primary"></i>All Sub Categories</h5>
    <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> Add Sub Category
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
    <thead class="table-light">
      <tr>
        <th style="width:200px;">Parent Category</th>
        <th>Sub Category Name</th>
        <th>Slug</th>
        <th style="width:90px;" class="text-center">Status</th>
        <th style="width:110px;" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subCategories as $sub)
        <tr>
          <td><span class="badge bg-secondary">{{ $sub->category->name }}</span></td>
          <td><strong>{{ $sub->name }}</strong></td>
          <td><code class="small text-muted">{{ $sub->slug }}</code></td>
          <td class="text-center">
            @if($sub->is_active)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-danger">Inactive</span>
            @endif
          </td>
          <td class="text-center">
            <a href="{{ route('admin.sub-categories.edit', $sub) }}"
               class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.sub-categories.destroy', $sub) }}"
                  method="POST" class="d-inline" onsubmit="return confirm('Delete this sub-category?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-3">No sub categories added yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
