@extends('layouts.backend.app')

@section('title', 'Orders')

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
  <h4>Orders</h4>
</div>

<!-- Status Filter Tabs -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div class="d-flex gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
      All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
      Pending <span class="badge bg-warning text-dark ms-1">{{ $statusCounts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
      Delivered <span class="badge bg-success ms-1">{{ $statusCounts['delivered'] }}</span>
    </a>
  </div>
  <div>
    <button type="button" id="bulkPrintBtn" class="btn btn-sm btn-info text-white" style="display: none; background-color: #0dcaf0 !important; border-color: #0dcaf0 !important;">
      <i class="bi bi-printer me-1"></i> Print Selected Invoices (<span id="selectedCount">0</span>)
    </button>
  </div>
</div>

<div class="stat-card">
  <!-- Search and Per Page Filter -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex align-items-center gap-2">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
      @endif
      <label class="small text-muted mb-0">Show</label>
      <select name="per_page" class="form-select form-select-sm" style="width: 85px;" onchange="this.form.submit()">
        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
        <option value="15" {{ request('per_page') == '15' || !request('per_page') ? 'selected' : '' }}>15</option>
        <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
        <option value="30" {{ request('per_page') == '30' ? 'selected' : '' }}>30</option>
        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
      </select>
      <label class="small text-muted mb-0">entries</label>
    </form>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      @if(request('per_page'))
        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
      @endif
      <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by invoice, name, phone..." style="width:280px;">
      <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
      @if(request('search') || request('per_page'))
        <a href="{{ route('admin.orders.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
      @endif
    </form>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead>
      <tr>
        <th style="width:40px; text-align:center;"><input type="checkbox" id="selectAllOrders" class="form-check-input"></th>
        <th style="width:60px;">#</th>
        <th>Product Name</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Total</th>
        <th style="width:170px;">Payment</th>
        <th style="width:190px;">Status</th>
        <th>Date</th>
        <th style="width:100px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td class="text-center"><input type="checkbox" class="order-checkbox form-check-input" value="{{ $order->id }}"></td>
          <td>{{ $loop->iteration}}</td>
          <td>
            @foreach($order->items as $item)
              <div class="small fw-semibold text-wrap">{{ $item->product_name }} <span class="text-muted">(x{{ $item->quantity }})</span></div>
            @endforeach
          </td>
          <td>{{ $order->customer_name }}</td>
          <td>{{ $order->customer_phone }}</td>
          <td class="fw-bold">৳{{ number_format($order->total, 2) }}</td>
          <td>
            @if($order->payment_status === 'paid')
              <span class="badge bg-success">Paid</span>
            @else
              <span class="badge bg-warning text-dark">Pending</span>
            @endif
          </td>
          <td>
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-flex gap-1 align-items-center">
              @csrf
              @method('PATCH')
              <select name="order_status" class="form-select form-select-sm" style="width: 120px;">
                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
              <button type="submit" class="btn btn-sm btn-primary" title="Update Status">
                <i class="bi bi-check-lg"></i>
              </button>
            </form>
          </td>
          <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
          <td>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View">
              <i class="bi bi-eye"></i>
            </a>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="10" class="text-center text-muted py-4">No orders found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-3">
      {{ $orders->withQueryString()->links() }}
    </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllOrders');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkPrintBtn = document.getElementById('bulkPrintBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkPrintButton() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkPrintBtn.style.display = 'inline-block';
            selectedCountSpan.textContent = count;
        } else {
            bulkPrintBtn.style.display = 'none';
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateBulkPrintButton();
        });
    }

    orderCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked && selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            const allChecked = Array.from(orderCheckboxes).every(c => c.checked);
            if (allChecked && selectAllCheckbox) {
                selectAllCheckbox.checked = true;
            }
            updateBulkPrintButton();
        });
    });

    if (bulkPrintBtn) {
        bulkPrintBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            if (ids.length > 0) {
                const url = "{{ route('admin.orders.bulk-print') }}?ids=" + ids.join(',');
                window.open(url, '_blank');
            }
        });
    }
});
</script>
@endpush
@endsection
