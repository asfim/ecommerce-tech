@extends('layouts.backend.app')

@section('title', 'Product Reviews')

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
  <h4>Product Reviews</h4>
</div>

<div class="stat-card">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
    <thead>
      <tr>
        <th style="width: 5%">#</th>
        <th style="width: 25%">Product</th>
        <th style="width: 20%">Reviewer</th>
        <th style="width: 15%">Rating</th>
        <th style="width: 25%">Comment</th>
        <th style="width: 20%">Date</th>
        <th style="width: 10%">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reviews as $review)
        <tr>
          <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}</td>
          <td>
            @if($review->product)
              <a href="{{ route('product.details', $review->product->slug) }}" target="_blank" class="text-primary fw-semibold">
                {{ $review->product->name }}
              </a>
            @else
              <span class="text-muted">N/A</span>
            @endif
          </td>
          <td>{{ $review->name }}</td>
          <td>
            <div class="text-warning">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= $review->rating)
                  <i class="bi bi-star-fill"></i>
                @else
                  <i class="bi bi-star"></i>
                @endif
              @endfor
            </div>
          </td>
          <td>
            <div class="text-muted small" style="max-height: 80px; overflow-y: auto;">
              {{ $review->comment }}
            </div>
          </td>
          <td>
            <span class="small text-muted">{{ $review->created_at->format('d M Y, h:i A') }}</span>
            <br>
            <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
          </td>
          <td>
            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
              @csrf 
              @method('DELETE')
              <button class="btn btn-sm btn-danger" title="Delete Review">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center py-4 text-muted">No reviews found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-3">
    {{ $reviews->links() }}
  </div>
</div>
@endsection
