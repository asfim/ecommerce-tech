@extends('layouts.backend.app')

@section('title', 'Admin Dashboard')

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
  <h4>Dashboard</h4>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <p>Total Orders</p>
            <h2>1,245</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Total Sales</p>
            <h2>$34,560</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Total Users</p>
            <h2>892</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Total Products</p>
            <h2>156</h2>
        </div>
    </div>
</div>
@endsection
