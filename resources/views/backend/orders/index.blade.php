@extends('layouts.backend.app')

@section('title', 'Orders')

@section('content')
@push('styles')
    <style>
        tr.order-row-fraud > td {
            background-color: #fee2e2 !important;
            border-color: #fca5a5 !important;
            color: #7f1d1d !important;
        }
        tr.order-row-fraud:hover > td {
            background-color: #fecaca !important;
        }
        tr.order-row-fraud {
            border-left: 5px solid #dc2626 !important;
        }
        .btn-fraud-pill {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            padding: 2px 8px !important;
            font-size: 11px !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            background-color: #fef3c7 !important;
            border: 1px solid #f59e0b !important;
            color: #b45309 !important;
            line-height: 1.3 !important;
            margin-top: 3px !important;
        }
        .btn-fraud-pill:hover {
            background-color: #fde68a !important;
            border-color: #d97706 !important;
            color: #78350f !important;
        }
        .btn-fraud-pill.is-fraud {
            background-color: #dc2626 !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
        }
        .btn-fraud-pill.is-fraud:hover {
            background-color: #b91c1c !important;
            color: #ffffff !important;
        }
    </style>
@endpush
    <div class="clearfix mb-4">
        <div class="dropdown float-end">
            <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://placehold.co/28x28/1a73e8/fff?text={{ strtoupper(substr(Auth::guard('admin')->user()->email, 0, 1)) }}"
                    class="rounded-circle">
                <span>
                    <span class="name d-block">{{ Auth::guard('admin')->user()->email }}</span>
                    <span class="role">eCommerce</span>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Visit Site</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        <h4>Orders</h4>
    </div>

    <!-- Status Filter Tabs -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
                All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                Pending <span class="badge bg-warning text-dark ms-1">{{ $statusCounts['pending'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}"
                class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }}">
                Confirmed <span class="badge bg-primary ms-1">{{ $statusCounts['confirmed'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
                class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                Delivered <span class="badge bg-success ms-1">{{ $statusCounts['delivered'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
                class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                Cancelled <span class="badge bg-danger ms-1">{{ $statusCounts['cancelled'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'returned']) }}"
                class="btn btn-sm {{ request('status') === 'returned' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Returned <span class="badge bg-secondary ms-1">{{ $statusCounts['returned'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'fraud']) }}"
                class="btn btn-sm {{ request('status') === 'fraud' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-shield-x me-1"></i> Fraud <span class="badge bg-danger ms-1">{{ $statusCounts['fraud'] }}</span>
            </a>
        </div>
        <div class="d-flex gap-2">
            <button type="button" id="bulkPrintBtn" class="btn btn-sm btn-info text-white"
                style="display: none; background-color: #0dcaf0 !important; border-color: #0dcaf0 !important;">
                <i class="bi bi-printer me-1"></i> Print Selected Invoices (<span id="selectedCount">0</span>)
            </button>
        </div>
    </div>

    {{-- Fraud Alert Banner on Page Load --}}
    @if (($pageFraudCount ?? 0) > 0)
        <div class="alert alert-danger d-flex align-items-center justify-content-between p-3 mb-3 shadow-sm"
            style="border-left: 6px solid #dc2626 !important; background-color: #fef2f2 !important; color: #991b1b !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-shield-exclamation fs-1 text-danger"></i>
                <div>
                    <h5 class="alert-heading mb-1 text-danger fw-bold text-uppercase">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i> FRAUD WARNING: {{ $pageFraudCount }} Suspicious / High-Risk Order(s) Detected
                    </h5>
                    <p class="mb-0 text-dark small">
                        Orders from customers with previous fraudulent records, high cancellations, or blocked IP addresses are automatically highlighted with a <strong class="text-danger">Red Background</strong> below. Check risk before dispatching!
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'fraud']) }}" class="btn btn-sm btn-danger fw-bold text-white px-3"
                style="background-color: #dc2626 !important; border-color: #dc2626 !important;">
                <i class="bi bi-shield-x me-1"></i> View Fraud Filter ({{ $statusCounts['fraud'] }})
            </a>
        </div>
    @endif

    <div class="stat-card">
        {{-- Instant Phone Number Fraud Checker --}}
        <div class="p-2 mb-3 rounded d-flex align-items-center justify-content-between flex-wrap gap-2"
            style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #1a73e8 !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-telephone-inbound-fill text-primary"></i>
                <span class="small fw-bold text-dark">Instant Phone Fraud Check:</span>
                <span class="small text-muted d-none d-md-inline">Check customer order cancellation history & fraud risk in database</span>
            </div>
            <form id="quickPhoneCheckerForm" class="d-flex gap-2 align-items-center">
                <input type="text" id="quickCheckPhoneInput" class="form-control form-control-sm"
                    placeholder="Enter phone (e.g. 017xxxxxxxx)" style="width: 210px; border-color: #a1a1a1 !important;" required>
                <button type="submit" class="btn btn-sm btn-primary fw-semibold" style="background-color: #1a73e8 !important;">
                    <i class="bi bi-shield-check me-1"></i> Check Phone
                </button>
            </form>
        </div>

        <!-- Search and Per Page Filter -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex align-items-center gap-2">
                @if (request('status'))
                    <input style="border-color: #a1a1a1 !important;" type="hidden" name="status"
                        value="{{ request('status') }}">
                @endif
                @if (request('search'))
                    <input style="border-color: #a1a1a1 !important;" type="hidden" name="search"
                        value="{{ request('search') }}">
                @endif
                <label style="border-color: #a1a1a1 !important;" class="small text-muted mb-0">Show</label>
                <select style="border-color: #a1a1a1 !important;" name="per_page" class="form-select form-select-sm"
                    style="width: 85px; " onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page') == '15' || !request('per_page') ? 'selected' : '' }}>15
                    </option>
                    <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
                    <option value="30" {{ request('per_page') == '30' ? 'selected' : '' }}>30</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                </select>
                <label class="small text-muted mb-0">entries</label>
            </form>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if (request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                <input style="border-color: #a1a1a1 !important; width: 230px !important; " type="text" name="search"
                    value="{{ request('search') }}" class="form-control form-control-sm"
                    placeholder="Search by invoice, name, phone..." style="width:280px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                @if (request('search') || request('per_page'))
                    <a href="{{ route('admin.orders.index', request('status') ? ['status' => request('status')] : []) }}"
                        class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="white-space: pre-line;">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" style="white-space: pre-line;">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered align-middle" style="border-color: #a1a1a1 !important;">
            <thead>
                <tr>
                    <th style="width:40px; text-align:center;"><input type="checkbox" id="selectAllOrders"
                            class="form-check-input" style="border-color: #a1a1a1 !important;"></th>
                    <th style="width:60px;">#</th>
                    <th>Product Name</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th style="width:170px;">Order Status</th>
                    <th style="width:190px;">Update Status</th>
                    <th>Date</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $stat = $phoneStats[$order->customer_phone] ?? null;
                        $isIpBlocked = ! empty($order->ip_address) && isset($blockedIps[$order->ip_address]);
                        $hasFraudPast = $stat && $stat->fraud_count > 0;
                        $totalCancels = $stat ? ($stat->cancelled + $stat->returned) : 0;
                        $cThreshold = $cancelThreshold ?? 2;
                        $isCancelFraud = $totalCancels >= $cThreshold;
                        $hasCancelPast = $totalCancels >= 1;
                        $isSuspiciousPhone = $hasFraudPast || $isCancelFraud || ($totalCancels >= 1 && $stat->delivered == 0 && $stat->total > 1);
                        $isFraudRow = (bool) $order->is_fraud || $isIpBlocked || $isSuspiciousPhone;
                        $isFraud = $isFraudRow;
                    @endphp
                    <tr class="{{ $isFraudRow ? 'order-row-fraud' : '' }}">
                        <td class="text-center"><input type="checkbox" class="order-checkbox form-check-input"
                                value="{{ $order->id }}" style="border-color: #a1a1a1 !important;"></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @foreach ($order->items as $item)
                                <div class="small fw-semibold text-wrap">{{ $item->product_name }} <span
                                        class="text-muted">(x{{ $item->quantity }})</span></div>
                            @endforeach
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                            @if ($order->ip_address)
                                <div class="small text-muted" style="font-size: 11px;">
                                    <i class="bi bi-geo-alt"></i> {{ $order->ip_address }}
                                </div>
                            @endif
                            @if ($isFraudRow)
                                <div class="mt-1 d-flex flex-wrap gap-1 align-items-center">
                                    <span class="badge bg-danger text-white"><i class="bi bi-shield-x-fill me-1"></i> FRAUD RISK</span>
                                    @if ($isIpBlocked)
                                        <span class="badge bg-dark text-white"><i class="bi bi-slash-circle me-1"></i> IP BLOCKED</span>
                                    @endif
                                </div>
                                @if ($order->fraud_reason)
                                    <div class="small text-danger fw-bold mt-1" style="font-size: 11px;">
                                        <i class="bi bi-exclamation-octagon me-1"></i> {{ $order->fraud_reason }}
                                    </div>
                                @elseif ($hasFraudPast)
                                    <div class="small text-danger fw-bold mt-1" style="font-size: 11px;">
                                        <i class="bi bi-exclamation-octagon me-1"></i> Phone has past fraud records
                                    </div>
                                @elseif ($isCancelFraud)
                                    <div class="small text-danger fw-bold mt-1" style="font-size: 11px;">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> High cancellations: {{ $totalCancels }} cancelled/returned orders
                                    </div>
                                @elseif ($hasCancelPast)
                                    <div class="small text-danger fw-bold mt-1" style="font-size: 11px;">
                                        <i class="bi bi-exclamation-octagon me-1"></i> {{ $totalCancels }} past cancelled/returned orders
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer_phone }}</div>

                            {{-- Auto Risk Indicator Badges by Phone Number --}}
                            <div class="d-flex flex-wrap gap-1 mt-1 mb-1">
                                @if ($order->is_fraud)
                                    <span class="badge bg-danger text-white" style="font-size: 10px;">
                                        <i class="bi bi-shield-x-fill me-1"></i> Fraud Order
                                    </span>
                                @elseif ($isIpBlocked)
                                    <span class="badge bg-danger text-white" style="font-size: 10px;">
                                        <i class="bi bi-slash-circle me-1"></i> IP Blocked
                                    </span>
                                @elseif ($hasFraudPast)
                                    <span class="badge bg-danger text-white" style="font-size: 10px;">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> Prev. Fraud
                                    </span>
                                @elseif ($isCancelFraud)
                                    <span class="badge bg-danger text-white" style="font-size: 10px;"
                                        title="{{ $stat->cancelled }} cancelled, {{ $stat->returned }} returned">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> {{ $totalCancels }} Cancelled (FRAUD)
                                    </span>
                                @elseif ($hasCancelPast)
                                    <span class="badge bg-warning text-dark" style="font-size: 10px;"
                                        title="{{ $stat->cancelled }} cancelled, {{ $stat->returned }} returned">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $totalCancels }} Cancelled
                                    </span>
                                @elseif ($stat && $stat->delivered > 0)
                                    <span class="badge bg-success text-white" style="font-size: 10px;">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ $stat->delivered }} Delivered
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border" style="font-size: 10px;">
                                        New Customer
                                    </span>
                                @endif
                            </div>

                            <button type="button" class="btn btn-fraud-check btn-fraud-pill {{ $isFraudRow ? 'is-fraud' : '' }}"
                                data-id="{{ $order->id }}" title="Check Customer Risk & Fraud Data">
                                <i class="bi {{ $isFraudRow ? 'bi-shield-x-fill' : 'bi-shield-check' }}"></i> {{ $isFraudRow ? 'Fraud Risk' : 'Check Risk' }}
                            </button>
                        </td>
                        <td class="fw-bold">৳{{ number_format($order->total, 2) }}</td>
                        <td>
                            @if ($order->order_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($order->order_status === 'confirmed')
                                <span class="badge bg-primary">Confirmed</span>
                            @elseif ($order->order_status === 'delivered')
                                <span class="badge bg-success">Paid</span>
                            @elseif ($order->order_status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @elseif ($order->order_status === 'returned')
                                <span class="badge bg-secondary">Returned</span>
                            @else
                                <span class="badge bg-dark">{{ ucfirst($order->order_status) }}</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}"
                                class="d-flex gap-1 align-items-center">
                                @csrf
                                @method('PATCH')
                                <select name="order_status" class="form-select form-select-sm" style="width: 120px;">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="confirmed"
                                        {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="delivered"
                                        {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled"
                                        {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="returned" {{ $order->order_status === 'returned' ? 'selected' : '' }}>
                                        Returned</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary" title="Update Status">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 align-items-center flex-wrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary"
                                    title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Fraud Check Modal Trigger --}}
                                <button type="button" class="btn btn-sm {{ $isFraud ? 'btn-danger' : 'btn-outline-warning' }} btn-fraud-check"
                                    data-id="{{ $order->id }}" title="Fraud Checker & Risk Details">
                                    <i class="bi bi-shield-check"></i>
                                </button>

                                {{-- IP Block / Unblock Quick Action --}}
                                @if ($order->ip_address)
                                    @if ($isIpBlocked)
                                        <button type="button" class="btn btn-sm btn-dark btn-unblock-ip"
                                            data-id="{{ $order->id }}" data-ip="{{ $order->ip_address }}"
                                            title="Unblock IP: {{ $order->ip_address }}">
                                            <i class="bi bi-slash-circle-fill text-danger"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-block-ip"
                                            data-id="{{ $order->id }}" data-ip="{{ $order->ip_address }}"
                                            title="Block Customer IP: {{ $order->ip_address }}">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    @endif
                                @endif


                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('selectAllOrders');
                const orderCheckboxes = document.querySelectorAll('.order-checkbox');
                const bulkPrintBtn = document.getElementById('bulkPrintBtn');
                const selectedCountSpan = document.getElementById('selectedCount');

                const bulkSendSteadfastBtn = document.getElementById('bulkSendSteadfastBtn');
                const selectedCountSteadfastSpan = document.getElementById('selectedCountSteadfast');

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

                // Fraud Check & Cancellation Risk System
                const adminOrderBaseUrl = "{{ url('admin/orders') }}";

                function renderOrderCancellationBox(data) {
                    const stats = data.stats || {};
                    const total = stats.total || 0;
                    const delivered = stats.delivered || 0;
                    const cancelled = (stats.cancelled || 0) + (stats.returned || 0);
                    const cancelRate = data.cancel_rate || 0;
                    const successRate = data.success_rate || 0;

                    const cancelBadgeClass = cancelled >= 2 ? 'badge bg-danger text-white' : (cancelled === 1 ? 'badge bg-warning text-dark' : 'badge bg-success text-white');

                    return `
                        <div class="card p-3 mb-3 text-start bg-light border-0 shadow-sm" style="font-size: 12px; border-left: 4px solid ${data.is_fraud ? '#dc2626' : '#16a34a'} !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-dark"><i class="bi bi-clock-history me-1 text-primary"></i> Order Cancellation & History Analysis:</strong>
                                <span class="${cancelBadgeClass} px-2 py-1">${cancelled} Cancelled</span>
                            </div>
                            <div class="row g-2 mb-2 text-center bg-white py-2 rounded border mx-0">
                                <div class="col-4">
                                    <div class="fw-bold fs-5 text-dark">${total}</div>
                                    <div class="text-muted" style="font-size:11px;">Total Orders</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-5 text-success">${delivered}</div>
                                    <div class="text-muted" style="font-size:11px;">Delivered</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-5 ${cancelled > 0 ? 'text-danger' : 'text-dark'}">${cancelled}</div>
                                    <div class="text-muted" style="font-size:11px;">Cancelled</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-1 text-muted" style="font-size: 11px;">
                                <span>Cancellation Rate: <strong class="${cancelRate >= 40 ? 'text-danger' : 'text-dark'}">${cancelRate}%</strong></span>
                                <span>Delivery Success: <strong class="text-success">${successRate}%</strong></span>
                            </div>
                        </div>
                    `;
                }

                document.querySelectorAll('.btn-fraud-check').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const orderId = this.getAttribute('data-id');

                        Swal.fire({
                            title: 'Analyzing Fraud Risk...',
                            text: 'Fetching customer order history and IP risk data...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        fetch(`${adminOrderBaseUrl}/${orderId}/fraud-check`, {
                            headers: { 'Accept': 'application/json' }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Network error ' + res.status);
                            return res.json();
                        })
                        .then(data => {
                            let reasonsHtml = '';
                            if (data.reasons && data.reasons.length > 0) {
                                reasonsHtml = '<div class="alert alert-danger text-start p-2 mb-3" style="font-size: 12px;"><strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Detected Risk Factors:</strong><ul class="mb-0 ps-3">' +
                                    data.reasons.map(r => `<li>${r}</li>`).join('') + '</ul></div>';
                            } else {
                                reasonsHtml = '<div class="alert alert-success text-start p-2 mb-3" style="font-size: 12px;"><i class="bi bi-check-circle-fill me-1"></i> No suspicious activity detected for this customer.</div>';
                            }

                            const hasRecordedIp = data.ip_address && data.ip_address !== 'Not recorded';
                            let ipBlockAction = '';

                            if (hasRecordedIp) {
                                if (data.is_ip_blocked) {
                                    ipBlockAction = `<button type="button" class="btn btn-sm w-100 mb-2 swal-unblock-ip-btn" data-id="${orderId}" style="background-color:#1f2937 !important; border-color:#1f2937 !important; color:#fff !important;"><i class="bi bi-unlock me-1"></i> Unblock IP (${data.ip_address})</button>`;
                                } else {
                                    ipBlockAction = `<button type="button" class="btn btn-sm w-100 mb-2 swal-block-ip-btn" data-id="${orderId}" data-ip="${data.ip_address}" style="background-color:#dc2626 !important; border-color:#dc2626 !important; color:#fff !important;"><i class="bi bi-slash-circle me-1"></i> Block Customer IP (${data.ip_address})</button>`;
                                }
                            } else {
                                ipBlockAction = `<button type="button" class="btn btn-sm w-100 mb-2 swal-block-ip-btn" data-id="${orderId}" data-ip="" style="background-color:#fff !important; border:1px solid #dc2626 !important; color:#dc2626 !important; font-weight:600;"><i class="bi bi-slash-circle me-1"></i> Block Customer IP (Enter IP)</button>`;
                            }

                            const fraudToggleAction = data.is_fraud
                                ? `<button type="button" class="btn btn-sm w-100 swal-toggle-fraud-btn" data-id="${orderId}" style="background-color:#4b5563 !important; border-color:#4b5563 !important; color:#fff !important;"><i class="bi bi-shield-check me-1"></i> Remove Fraud Flag</button>`
                                : `<button type="button" class="btn btn-sm w-100 swal-toggle-fraud-btn" data-id="${orderId}" style="background-color:#f59e0b !important; border-color:#f59e0b !important; color:#000 !important; font-weight:bold;"><i class="bi bi-shield-x me-1"></i> Flag as Fraud Order (Turn Row Red)</button>`;

                            let fraudBannerHtml = '';
                            if (data.is_fraud) {
                                fraudBannerHtml = `
                                    <div class="alert alert-danger text-start p-2 mb-3" style="font-size: 12px; background-color: #fee2e2; border: 1px solid #dc2626; color: #991b1b;">
                                        <strong class="d-block mb-1"><i class="bi bi-shield-x-fill text-danger me-1"></i> 🚨 FRAUD DETECTED (অর্ডার ফ্রড চিহ্নিত):</strong>
                                        <div>পূর্বে কাস্টমারের পার্সেল বাতিল (অর্ডার ক্যান্সেলেশন) বা ফ্রড রেকর্ড থাকায় এই অর্ডার ফ্রড চিহ্নিত হয়েছে (রো-টি লাল রঙে প্রদর্শিত)।</div>
                                    </div>
                                `;
                            }

                            const cancellationHtml = renderOrderCancellationBox(data);

                            Swal.fire({
                                title: `<span>Fraud Check: ${data.invoice_no}</span>`,
                                html: `
                                    <div class="text-start mb-3" style="font-size: 13px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><strong>Customer:</strong> ${data.customer_name}</span>
                                            <span class="badge bg-${data.risk_color} px-2 py-1 fs-6">${data.risk_level} (${data.risk_score}%)</span>
                                        </div>
                                        <div class="mb-2"><strong>Phone:</strong> ${data.customer_phone}</div>
                                        <div class="mb-2"><strong>IP Address:</strong> <code>${data.ip_address}</code> ${data.is_ip_blocked ? '<span class="badge bg-danger ms-1">BLOCKED</span>' : ''}</div>
                                    </div>

                                    ${fraudBannerHtml}
                                    ${reasonsHtml}
                                    ${cancellationHtml}

                                    <div class="mt-3">
                                        ${ipBlockAction}
                                        ${fraudToggleAction}
                                    </div>
                                `,
                                showConfirmButton: false,
                                showCloseButton: true,
                                didOpen: () => {
                                    const blockBtn = document.querySelector('.swal-block-ip-btn');
                                    const unblockBtn = document.querySelector('.swal-unblock-ip-btn');
                                    const toggleBtn = document.querySelector('.swal-toggle-fraud-btn');

                                    if (blockBtn) {
                                        blockBtn.addEventListener('click', () => {
                                            blockCustomerIp(orderId, blockBtn.getAttribute('data-ip'));
                                        });
                                    }
                                    if (unblockBtn) {
                                        unblockBtn.addEventListener('click', () => {
                                            unblockCustomerIp(orderId);
                                        });
                                    }
                                    if (toggleBtn) {
                                        toggleBtn.addEventListener('click', () => {
                                            toggleFraudStatus(orderId);
                                        });
                                    }
                                }
                            });
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Failed to load fraud risk data: ' + err.message, 'error');
                        });
                    });
                });

                function toggleFraudStatus(orderId) {
                    Swal.fire({
                        title: 'Updating Fraud Status...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch(`${adminOrderBaseUrl}/${orderId}/toggle-fraud`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire('Updated!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Failed to update fraud status.', 'error');
                    });
                }

                function blockCustomerIp(orderId, ip) {
                    if (!ip || ip === 'Not recorded' || ip.trim() === '') {
                        Swal.fire({
                            title: 'Enter IP to Block',
                            text: 'Enter the customer IP address to block and mark as fraud:',
                            input: 'text',
                            inputPlaceholder: 'e.g. 103.150.24.12',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'Block This IP',
                            inputValidator: (val) => {
                                if (!val || !val.trim()) {
                                    return 'Please enter a valid IP address!';
                                }
                            }
                        }).then(result => {
                            if (result.isConfirmed && result.value) {
                                executeBlockIp(orderId, result.value.trim());
                            }
                        });
                        return;
                    }

                    Swal.fire({
                        title: `Block IP: ${ip}?`,
                        text: 'This customer will be prevented from placing further orders, and this order will be highlighted as FRAUD (turning the full row RED).',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Yes, Block IP!'
                    }).then(result => {
                        if (result.isConfirmed) {
                            executeBlockIp(orderId, ip);
                        }
                    });
                }

                function executeBlockIp(orderId, ip) {
                    Swal.fire({
                        title: 'Blocking IP...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch(`${adminOrderBaseUrl}/${orderId}/block-ip`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ip_address: ip })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Blocked!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Failed to block IP.', 'error');
                    });
                }

                function unblockCustomerIp(orderId) {
                    Swal.fire({
                        title: 'Unblock IP?',
                        text: 'Allow this IP address to place orders again?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        confirmButtonText: 'Yes, Unblock'
                    }).then(result => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Unblocking IP...',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); }
                            });

                            fetch(`${adminOrderBaseUrl}/${orderId}/unblock-ip`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire('Unblocked!', data.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(() => {
                                Swal.fire('Error', 'Failed to unblock IP.', 'error');
                            });
                        }
                    });
                }

                document.querySelectorAll('.btn-block-ip').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        const ip = this.getAttribute('data-ip');
                        blockCustomerIp(id, ip);
                    });
                });

                document.querySelectorAll('.btn-unblock-ip').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        unblockCustomerIp(id);
                    });
                });

                // Page Load Fraud Warning Toast
                @if (($pageFraudCount ?? 0) > 0)
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: '⚠️ Fraud Warning Alert!',
                        text: '{{ $pageFraudCount }} suspicious / high-risk order(s) detected on this page.',
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true
                    });
                @endif

                // Instant Phone Number Fraud Checker
                const quickPhoneForm = document.getElementById('quickPhoneCheckerForm');
                if (quickPhoneForm) {
                    quickPhoneForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const phone = document.getElementById('quickCheckPhoneInput').value.trim();
                        if (!phone) return;

                        Swal.fire({
                            title: 'Checking Phone Number...',
                            text: `Checking fraud risk for ${phone}...`,
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        fetch(`{{ route('admin.orders.check-phone') }}?phone=${encodeURIComponent(phone)}`, {
                            headers: { 'Accept': 'application/json' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                Swal.fire('Error', data.message || 'Failed to check phone number.', 'error');
                                return;
                            }

                            let reasonsHtml = '';
                            if (data.reasons && data.reasons.length > 0) {
                                reasonsHtml = '<div class="alert alert-danger text-start p-2 mb-3" style="font-size: 12px;"><strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Risk Factors Detected:</strong><ul class="mb-0 ps-3">' +
                                    data.reasons.map(r => `<li>${r}</li>`).join('') + '</ul></div>';
                            } else {
                                reasonsHtml = '<div class="alert alert-success text-start p-2 mb-3" style="font-size: 12px;"><i class="bi bi-check-circle-fill me-1"></i> No past fraudulent activity detected for this phone number.</div>';
                            }

                            let fraudBannerHtml = '';
                            if (data.is_fraud) {
                                fraudBannerHtml = `
                                    <div class="alert alert-danger text-start p-2 mb-3" style="font-size: 12px; background-color: #fee2e2; border: 1px solid #dc2626; color: #991b1b;">
                                        <strong class="d-block mb-1"><i class="bi bi-shield-x-fill text-danger me-1"></i> 🚨 FRAUD NUMBER DETECTED:</strong>
                                        <div>পূর্বে কাস্টমারের পার্সেল বাতিল (অর্ডার ক্যান্সেলেশন) অথবা ফ্রড রেকর্ড থাকায় এই নাম্বারকে ফ্রড চিহ্নিত করা হয়েছে।</div>
                                    </div>
                                `;
                            }

                            const cancellationHtml = renderOrderCancellationBox(data);

                            Swal.fire({
                                title: `<span>Phone Risk: ${data.phone}</span>`,
                                html: `
                                    <div class="text-start mb-3" style="font-size: 13px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><strong>Target Number:</strong> <code>${data.phone}</code></span>
                                            <span class="badge bg-${data.risk_color} px-2 py-1 fs-6">${data.risk_level} (${data.risk_score}%)</span>
                                        </div>
                                    </div>

                                    ${fraudBannerHtml}
                                    ${reasonsHtml}
                                    ${cancellationHtml}
                                `,
                                showCloseButton: true,
                                confirmButtonText: 'Done',
                                confirmButtonColor: '#1a73e8'
                            });
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Failed to check phone number risk.', 'error');
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
