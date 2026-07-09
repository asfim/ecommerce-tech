@extends('layouts.app')

@section('title', 'Invoice - ' . $order->invoice_no)

@push('styles')
<style>
  .invoice-wrap {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
  }

  .invoice-card {
    background: #fff;
    border: 1px solid #e4e1d7;
    border-radius: 12px;
    overflow: hidden;
  }

  .invoice-header {
    background: linear-gradient(135deg, #111b16, #1a2f22);
    color: #fff;
    padding: 32px 36px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .invoice-header .inv-title {
    font-family: 'Fraunces', serif;
    font-size: 1.6rem;
    font-weight: 700;
    margin: 0;
  }

  .invoice-header .inv-subtitle {
    opacity: 0.7;
    font-size: 13px;
    margin-top: 4px;
  }

  .invoice-header .inv-right {
    text-align: right;
  }

  .invoice-header .inv-no {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 14px;
    background: rgba(255,255,255,0.12);
    padding: 6px 14px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 6px;
  }

  .invoice-header .inv-date {
    font-size: 12.5px;
    opacity: 0.7;
  }

  .invoice-body {
    padding: 32px 36px;
  }

  .invoice-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
  }

  .meta-block h6 {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #767066;
    font-weight: 700;
    margin-bottom: 8px;
  }

  .meta-block p {
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
    color: #111b16;
  }

  .invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
  }

  .invoice-table thead th {
    background: #f6f4ee;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #767066;
    font-weight: 700;
    padding: 10px 14px;
    text-align: left;
    border-bottom: 1.5px solid #e4e1d7;
  }

  .invoice-table thead th:last-child {
    text-align: right;
  }

  .invoice-table tbody td {
    padding: 14px;
    border-bottom: 1px dashed #e4e1d7;
    font-size: 13.5px;
    vertical-align: middle;
  }

  .invoice-table tbody td:last-child {
    text-align: right;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 500;
  }

  .invoice-table .item-img {
    width: 42px;
    height: 42px;
    object-fit: contain;
    border-radius: 6px;
    border: 1px solid #eee;
    background: #fff;
    margin-right: 12px;
    vertical-align: middle;
  }

  .invoice-table .item-name {
    font-weight: 600;
  }

  .invoice-table .item-variant {
    font-size: 11.5px;
    color: #767066;
  }

  .invoice-totals {
    border-top: 1.5px solid #e4e1d7;
    padding-top: 20px;
    max-width: 320px;
    margin-left: auto;
  }

  .invoice-totals .t-line {
    display: flex;
    justify-content: space-between;
    font-size: 13.5px;
    margin-bottom: 8px;
    color: #4b463e;
  }

  .invoice-totals .t-line .val {
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 500;
  }

  .invoice-totals .t-grand {
    font-size: 1.15rem;
    font-weight: 700;
    color: #111b16;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1.5px solid #e4e1d7;
  }

  .invoice-footer {
    background: #f6f4ee;
    padding: 20px 36px;
    text-align: center;
    border-top: 1px solid #e4e1d7;
  }

  .invoice-footer p {
    margin: 0;
    font-size: 12.5px;
    color: #767066;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }

  .status-pending {
    background: #fff3cd;
    color: #856404;
  }

  .status-paid {
    background: #d4edda;
    color: #155724;
  }

  .btn-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 24px;
    margin-bottom: 40px;
  }

  .btn-actions a,
  .btn-actions button {
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    border: none;
  }

  .btn-print {
    background: #111b16;
    color: #fff;
  }

  .btn-print:hover {
    background: #1a2f22;
    color: #fff;
  }

  .btn-home {
    background: #e4e1d7;
    color: #111b16;
  }

  .btn-home:hover {
    background: #d4d1c7;
    color: #111b16;
  }

  @media print {
    .btn-actions,
    nav,
    footer,
    .topbar { display: none !important; }

    .invoice-wrap { margin: 0; padding: 0; }
    .invoice-card { border: none; box-shadow: none; }
  }

  @media (max-width: 600px) {
    .invoice-header { flex-direction: column; gap: 16px; }
    .invoice-header .inv-right { text-align: left; }
    .invoice-meta { grid-template-columns: 1fr; }
    .invoice-body { padding: 24px 20px; }
    .invoice-header { padding: 24px 20px; }
  }
</style>
@endpush

@section('content')
<div class="invoice-wrap">
  <div class="invoice-card">
    <div class="invoice-header">
      @php
        $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
        $companyName = $companySettings['name'] ?? 'eCommerce';
        $companyLogo = $companySettings['logo'] ?? null;
        $companyAddress = $companySettings['address'] ?? '';
        $companyPhone = $companySettings['phone'] ?? '';
      @endphp
      <div class="d-flex align-items-center gap-3">
        @if($companyLogo)
          <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height: 45px; border-radius: 4px;">
        @else
          <span class="logo-box" style="width:36px;height:36px;background:#1a73e8;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;">{{ strtoupper(substr($companyName, 0, 1)) }}</span>
        @endif
        <div>
          <h1 class="inv-title">{{ $companyName }}</h1>
          <div class="inv-subtitle">Order Confirmation</div>
        </div>
      </div>
      <div class="inv-right text-end">
        <div class="inv-no">{{ $order->invoice_no }}</div>
        <div class="inv-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        @if($companyAddress || $companyPhone)
          <div style="font-size: 11px; opacity: 0.8; margin-top: 6px; line-height: 1.3;">
            @if($companyAddress)
              <div>{{ $companyAddress }}</div>
            @endif
            @if($companyPhone)
              <div>Phone: {{ $companyPhone }}</div>
            @endif
          </div>
        @endif
      </div>
    </div>

    <div class="invoice-body">
      <div class="invoice-meta">
        <div class="meta-block">
          <h6>Customer Details</h6>
          <p>
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->customer_phone }}<br>
            {{ $order->customer_address }}
          </p>
        </div>
        <div class="meta-block">
          <h6>Order Info</h6>
          <p>
            <strong>Payment:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'SSL Commerz' }}<br>
            <strong>Shipping:</strong> {{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}<br>
            <strong>Status:</strong>
            <span class="status-badge {{ $order->payment_status === 'paid' ? 'status-paid' : 'status-pending' }}">
              <i class="bi {{ $order->payment_status === 'paid' ? 'bi-check-circle-fill' : 'bi-clock-fill' }}"></i>
              {{ ucfirst($order->payment_status) }}
            </span>
          </p>
        </div>
      </div>

      <table class="invoice-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
            <tr>
              <td>
                @if($item->product_image)
                  <img src="{{ asset('storage/' . $item->product_image) }}" class="item-img" alt="{{ $item->product_name }}">
                @endif
                <span class="item-name">{{ $item->product_name }}</span>
                @if(is_array($item->variants) && count($item->variants) > 0)
                  <br>
                  <span class="item-variant">
                    {{ collect($item->variants)->map(fn($v, $k) => ucfirst($k) . ': ' . $v)->join(' · ') }}
                  </span>
                @endif
              </td>
              <td>{{ $item->quantity }}</td>
              <td>৳{{ number_format($item->price, 2) }}</td>
              <td>৳{{ number_format($item->line_total, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="invoice-totals">
        <div class="t-line">
          <span>Subtotal</span>
          <span class="val">৳{{ number_format($order->subtotal, 2) }}</span>
        </div>
        @if($order->coupon_code)
          <div class="t-line text-success" style="color: #2c7d35 !important;">
            <span>Discount ({{ $order->coupon_code }})</span>
            <span class="val">-৳{{ number_format($order->discount_amount, 2) }}</span>
          </div>
        @endif
        <div class="t-line">
          <span>Shipping ({{ $order->shipping_method === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }})</span>
          <span class="val">৳{{ number_format($order->shipping_cost, 2) }}</span>
        </div>
        <div class="t-line">
          <span>Tax (5%)</span>
          <span class="val">৳{{ number_format($order->tax, 2) }}</span>
        </div>
        <div class="t-line t-grand">
          <span>Total</span>
          <span class="val">৳{{ number_format($order->total, 2) }}</span>
        </div>
      </div>
    </div>

    <div class="invoice-footer">
      <p>Thank you for your order! We will process it shortly.</p>
    </div>
  </div>

  <div class="btn-actions">
    <button onclick="window.print()" class="btn-print"><i class="bi bi-printer"></i> Print Invoice</button>
    <a href="{{ route('home') }}" class="btn-home"><i class="bi bi-house"></i> Back to Home</a>
  </div>
</div>
@endsection
