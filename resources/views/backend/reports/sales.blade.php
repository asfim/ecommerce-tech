@extends('layouts.backend.app')

@section('title', 'Sales Report')

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
  <h4>Sales Report</h4>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
  <div class="card-body">
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label for="start_date" class="form-label small fw-semibold text-muted">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $startDate->format('Y-m-d') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-3">
        <label for="end_date" class="form-label small fw-semibold text-muted">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $endDate->format('Y-m-d') }}" style="border-color: #a1a1a1 !important;">
      </div>
      <div class="col-md-3">
        <label for="order_status" class="form-label small fw-semibold text-muted">Order Status</label>
        <select name="order_status" id="order_status" class="form-select form-select-sm">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing</option>
          <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Shipped</option>
          <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
          <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
          <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Returned</option>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-filter"></i> Filter</button>
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-light btn-sm border"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
      </div>
    </form>
  </div>
</div>

<!-- Metrics Cards -->
<div class="d-flex flex-wrap gap-3 mb-4">
  <div class="flex-grow-1" style="min-width: 220px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-left: 4px solid #0d6efd;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Total Revenue</p>
      <h2 class="mt-2">৳{{ number_format($totalRevenue, 2) }}</h2>
      <div class="small text-muted mt-1">Product subtotal sales</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 220px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-left: 4px solid #f0ad4e;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Total Cost</p>
      <h2 class="mt-2">৳{{ number_format($totalCost, 2) }}</h2>
      <div class="small text-muted mt-1">Product buying cost</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 220px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-left: 4px solid #198754;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Net Profit</p>
      <h2 class="mt-2 {{ $netProfit < 0 ? 'text-danger' : 'text-success' }}">৳{{ number_format($netProfit, 2) }}</h2>
      <div class="small text-muted mt-1">Discounts: -৳{{ number_format($totalDiscount, 2) }}</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 220px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-left: 4px solid #20c997;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Profit Margin</p>
      <h2 class="mt-2">{{ number_format($profitMargin, 1) }}%</h2>
      <div class="small text-muted mt-1">Net profit relative to sales</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="stat-card d-flex align-items-center justify-content-between">
      <div>
        <p class="text-uppercase tracking-wider small fw-bold text-muted">Total Orders</p>
        <h2>{{ number_format($totalOrders) }}</h2>
      </div>
      <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
        <i class="bi bi-receipt fs-3"></i>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="stat-card d-flex align-items-center justify-content-between">
      <div>
        <p class="text-uppercase tracking-wider small fw-bold text-muted">Items Sold</p>
        <h2>{{ number_format($totalItemsSold) }}</h2>
      </div>
      <div class="bg-success bg-opacity-10 text-success rounded p-3">
        <i class="bi bi-cart-check fs-3"></i>
      </div>
    </div>
  </div>
</div>

<!-- Charts & Tables -->
<div class="row g-3 mb-4">
  <!-- Chart -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
      <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Sales Trend</h6>
      </div>
      <div class="card-body">
        <div style="position: relative; height: 300px;">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Top Selling Products -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
      <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-trophy text-warning me-2"></i>Top 10 Products</h6>
      </div>
      <div class="card-body p-0">
        @if($topSelling->isEmpty())
          <div class="text-center text-muted py-5">
            <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
            No sales data for this period.
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px; border-color: #a1a1a1 !important;">
              <thead>
                <tr class="table-light">
                  <th class="ps-3">Product Name</th>
                  <th class="text-center">Qty Sold</th>
                  <th class="text-end pe-3">Revenue</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topSelling as $item)
                  <tr>
                    <td class="ps-3 fw-medium">{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->total_qty }}</td>
                    <td class="text-end pe-3">৳{{ number_format($item->total_revenue, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Prepare chart data from PHP
    const rawData = @json($chartData);
    const labels = rawData.map(item => item.date);
    const revenues = rawData.map(item => parseFloat(item.revenue));
    const counts = rawData.map(item => item.count);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Revenue ($)',
            data: revenues,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.05)',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            yAxisID: 'y'
          },
          {
            label: 'Orders Count',
            data: counts,
            borderColor: '#20c997',
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            borderDash: [5, 5],
            fill: false,
            tension: 0.3,
            yAxisID: 'y1'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: {
              display: true,
              text: 'Revenue ($)',
              font: { size: 11 }
            },
            grid: {
              color: 'rgba(0,0,0,0.05)'
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
              display: true,
              text: 'Orders Count',
              font: { size: 11 }
            },
            grid: {
              drawOnChartArea: false
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 12,
              font: { size: 11 }
            }
          }
        }
      }
    });
  });
</script>
@endpush
