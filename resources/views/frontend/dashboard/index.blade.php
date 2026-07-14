@extends('layouts.frontend.app')

@section('title', 'My Dashboard')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end">
    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
      <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" class="rounded-circle">
      <span>
        <span class="name d-block">{{ Auth::user()->name }}</span>
        <span class="role">My Account</span>
      </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ route('user.logout') }}">
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
            <h2>{{ $totalOrders }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Wishlist</p>
            <h2>0</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Coupons</p>
            <h2>0</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p>Reviews</p>
            <h2>0</h2>
        </div>
    </div>
</div>

<div class="mt-4">
    <h5 class="mb-3">Monthly Order History</h5>
    <div class="stat-card">
        <div style="height: 320px; position: relative;">
            <canvas id="monthlyOrdersChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('monthlyOrdersChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Orders Placed',
                    data: {!! json_encode($monthlyOrderCounts) !!},
                    borderColor: '#1a73e8',
                    backgroundColor: 'rgba(26, 115, 232, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#1a73e8',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 10,
                        backgroundColor: '#111',
                        titleColor: '#fff',
                        bodyColor: '#ccc',
                        borderColor: '#222',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
