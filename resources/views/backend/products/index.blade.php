@extends('layouts.backend.app')

@section('title', 'Products')

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
  <h4>Products</h4>
</div>

<div class="stat-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Product</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
        <tr>
          <td>{{ $product->id }}</td>
          <td>{{ $product->name }}</td>
          <td>{{ $product->category->name ?? '-' }}</td>
          <td>{{ $product->brand->name ?? '-' }}</td>
          <td>${{ number_format($product->price, 2) }}</td>
          <td>{{ $product->stock }}</td>
          <td>
            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
              {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $products->links() }}
</div>
@endsection
