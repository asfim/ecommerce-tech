@extends('layouts.backend.app')

@section('title', 'Stock Report')

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
  <h4>Stock Report</h4>
</div>

<!-- Stock Metrics Cards -->
<div class="d-flex flex-wrap gap-3 mb-4">
  <div class="flex-grow-1" style="min-width: 180px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-top: 4px solid #0d6efd;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Total Products</p>
      <h2 class="mt-2">{{ number_format($totalProducts) }}</h2>
      <div class="small text-muted mt-1">Unique items</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 180px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-top: 4px solid #17a2b8;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Total Stock Qty</p>
      <h2 class="mt-2">{{ number_format($totalStockQty) }}</h2>
      <div class="small text-muted mt-1">Total physical units</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 180px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-top: 4px solid #f0ad4e;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Stock Value (Cost)</p>
      <h2 class="mt-2">৳{{ number_format($stockValueCost, 2) }}</h2>
      <div class="small text-muted mt-1">At purchasing price</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 180px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-top: 4px solid #198754;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Stock Value (Retail)</p>
      <h2 class="mt-2">৳{{ number_format($stockValueRetail, 2) }}</h2>
      <div class="small text-muted mt-1">At selling price</div>
    </div>
  </div>
  <div class="flex-grow-1" style="min-width: 180px; flex-basis: 0;">
    <div class="stat-card h-100" style="border-top: 4px solid #20c997;">
      <p class="text-uppercase tracking-wider small fw-bold text-muted">Potential Profit</p>
      <h2 class="mt-2">৳{{ number_format($potentialProfit, 2) }}</h2>
      <div class="small text-muted mt-1">Retail Value - Cost Value</div>
    </div>
  </div>
</div>

<!-- Alerts Row -->
@if($outOfStockCount > 0 || $lowStockCount > 0)
  <div class="row g-3 mb-4">
    @if($outOfStockCount > 0)
      <div class="col-md-6">
        <div class="alert alert-danger d-flex align-items-center mb-0" role="alert" style="border-radius: 8px;">
          <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
          <div>
            <strong>Critical Alert:</strong> {{ $outOfStockCount }} products are completely <strong>Out of Stock</strong>!
          </div>
        </div>
      </div>
    @endif
    @if($lowStockCount > 0)
      <div class="col-md-6">
        <div class="alert alert-warning d-flex align-items-center mb-0" role="alert" style="border-radius: 8px;">
          <i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>
          <div>
            <strong>Low Stock Warning:</strong> {{ $lowStockCount }} products have <strong>low stock</strong> (5 units or less).
          </div>
        </div>
      </div>
    @endif
  </div>
@endif

<!-- Charts and Alerts Summary -->
<div class="row g-3 mb-4">
  <!-- Stock Value by Category -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
      <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart text-info me-2"></i>Stock Value by Category</h6>
      </div>
      <div class="card-body">
        <div style="position: relative; height: 280px;">
          <canvas id="categoryStockChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Low Stock Alerts Summary -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
      <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-bell text-danger me-2"></i>Critical Stock Alerts</h6>
      </div>
      <div class="card-body p-0" style="max-height: 280px; overflow-y: auto;">
        @php
          $criticalItems = $paginatedProducts->filter(fn($p) => $p->stock <= 5);
        @endphp
        @if($criticalItems->isEmpty())
          <div class="text-center text-muted py-5">
            <i class="bi bi-check-circle text-success fs-2 d-block mb-2"></i>
            All items are well-stocked.
          </div>
        @else
          <ul class="list-group list-group-flush" style="font-size: 13px;">
            @foreach($criticalItems as $product)
              <li class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                <span class="fw-medium text-truncate" style="max-width: 70%;">{{ $product->name }}</span>
                @if($product->stock === 0)
                  <span class="badge bg-danger rounded-pill">Out of Stock</span>
                @else
                  <span class="badge bg-warning text-dark rounded-pill">{{ $product->stock }} Left</span>
                @endif
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Stock Details Table -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
  <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Detailed Inventory Table</h6>
    <button onclick="window.print()" class="btn btn-light btn-sm border"><i class="bi bi-printer"></i> Print Report</button>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 13.5px; border-color: #a1a1a1 !important;">
        <thead class="table-light">
          <tr>
            <th class="ps-3" style="width: 80px;">ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th class="text-end">Unit Cost</th>
            <th class="text-end">Unit Retail</th>
            <th class="text-center">Stock Level</th>
            <th class="text-end">Value (Cost)</th>
            <th class="text-end">Value (Retail)</th>
            <th class="text-center pe-3">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($paginatedProducts as $product)
            @php
              $prodBuyPrice = $product->buy_price ?? 0.00;
              $prodRetailPrice = $product->price ?? 0.00;
              $itemCostVal = $prodBuyPrice * $product->stock;
              $itemRetailVal = $prodRetailPrice * $product->stock;
            @endphp
            <tr>
              <td class="ps-3 text-muted">{{ $product->id }}</td>
              <td class="fw-medium">{{ $product->name }}</td>
              <td>{{ $product->category->name ?? '-' }}</td>
              <td class="text-end text-muted">৳{{ number_format($prodBuyPrice, 2) }}</td>
              <td class="text-end">৳{{ number_format($prodRetailPrice, 2) }}</td>
              <td class="text-center fw-bold">{{ $product->stock }}</td>
              <td class="text-end text-muted">৳{{ number_format($itemCostVal, 2) }}</td>
              <td class="text-end fw-semibold">৳{{ number_format($itemRetailVal, 2) }}</td>
              <td class="text-center pe-3">
                @if($product->stock === 0)
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1 rounded">Out of Stock</span>
                @elseif($product->stock <= 5)
                  <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle px-2 py-1 rounded">Low Stock</span>
                @else
                  <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1 rounded">In Stock</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white border-0 py-3">
    <div class="d-flex justify-content-center">
      {{ $paginatedProducts->links() }}
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('categoryStockChart').getContext('2d');
    
    const rawData = @json($categoryStock);
    const labels = rawData.map(item => item.category_name);
    const stockQuantities = rawData.map(item => item.total_stock);
    const retailValues = rawData.map(item => parseFloat(item.retail_value));

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Retail Value ($)',
            data: retailValues,
            backgroundColor: 'rgba(23, 162, 184, 0.7)',
            borderColor: '#17a2b8',
            borderWidth: 1,
            yAxisID: 'y'
          },
          {
            label: 'Stock Quantity',
            data: stockQuantities,
            backgroundColor: 'rgba(108, 117, 125, 0.4)',
            borderColor: '#6c757d',
            borderWidth: 1,
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
              text: 'Retail Value ($)',
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
              text: 'Stock Qty',
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
