@extends('layouts.backend.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="clearfix mb-4">
  <div class="dropdown float-end" style="padding-right: 10px;">
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
        <div class="stat-card" style="border-left: 4px solid #0d6efd;">
            <p class="text-uppercase small fw-bold text-muted">Total Orders</p>
            <h2 class="text-primary">{{ number_format($totalOrders) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid #198754;">
            <p class="text-uppercase small fw-bold text-muted">Total Sales</p>
            <h2 class="text-success">৳{{ number_format($totalSales, 2) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid #6f42c1;">
            <p class="text-uppercase small fw-bold text-muted">Total Users</p>
            <h2 style="color: #6f42c1;">{{ number_format($totalUsers) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid #fd7e14;">
            <p class="text-uppercase small fw-bold text-muted">Total Products</p>
            <h2 style="color: #fd7e14;">{{ number_format($totalProducts) }}</h2>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-md-8">
        <div class="stat-card">
            <h5 class="mb-3 fw-bold text-dark">Business Overview Graph</h5>
            <div style="position: relative; height: 350px;">
                <canvas id="businessBarChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <h5 class="mb-3 fw-bold text-dark">Distribution Mix</h5>
            <div style="position: relative; height: 350px;">
                <canvas id="businessPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data from backend
        const totalOrders = {{ $totalOrders }};
        const totalSales = {{ $totalSales }};
        const totalUsers = {{ $totalUsers }};
        const totalProducts = {{ $totalProducts }};

        // 1. Business Overview Bar Chart (Multi-axis to accommodate large sales amounts)
        const ctxBar = document.getElementById('businessBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Total Orders', 'Total Users', 'Total Products', 'Total Sales (৳)'],
                datasets: [{
                    label: 'Metric Value',
                    data: [totalOrders, totalUsers, totalProducts, totalSales],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.75)',  // Blue
                        'rgba(111, 66, 193, 0.75)',  // Purple
                        'rgba(253, 126, 20, 0.75)',  // Orange
                        'rgba(25, 135, 84, 0.75)'    // Green
                    ],
                    borderColor: [
                        '#0d6efd',
                        '#6f42c1',
                        '#fd7e14',
                        '#198754'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 6,
                    yAxisID: 'y'
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
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataIndex === 3) {
                                    label += '৳' + context.raw.toLocaleString();
                                } else {
                                    label += context.raw.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Counts / Value'
                        },
                        grid: {
                            drawOnChartArea: true
                        }
                    }
                }
            }
        });

        // 2. Business Distribution Pie Chart (Counts only)
        const ctxPie = document.getElementById('businessPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Orders', 'Users', 'Products'],
                datasets: [{
                    data: [totalOrders, totalUsers, totalProducts],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.75)',  // Blue
                        'rgba(111, 66, 193, 0.75)',  // Purple
                        'rgba(253, 126, 20, 0.75)'   // Orange
                    ],
                    borderColor: [
                        '#0d6efd',
                        '#6f42c1',
                        '#fd7e14'
                    ],
                    borderWidth: 1.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });
    });
</script>
@endpush
@endsection
