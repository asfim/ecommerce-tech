<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bulk Print Invoices</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap');

    body {
      background: #f8f9fa;
      font-family: 'Inter', sans-serif;
      color: #111b16;
      padding: 20px;
    }

    @media print {
      .no-print {
        display: none !important;
      }
      .page-break {
        page-break-after: always;
        break-after: page;
      }
      body {
        background: #fff !important;
        padding: 0 !important;
      }
      .invoice-wrap {
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
      }
    }

    .invoice-wrap {
      max-width: 800px;
      margin: 20px auto;
      background: #fff;
      border: 1px solid #e4e1d7;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .invoice-card {
      background: #fff;
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
      font-size: 1.6rem;
      font-weight: 700;
      margin: 0;
      letter-spacing: -0.5px;
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
      margin-bottom: 10px;
    }

    .invoice-totals .t-grand {
      font-size: 16px;
      font-weight: 700;
      border-top: 1.5px solid #e4e1d7;
      padding-top: 12px;
      margin-top: 10px;
    }

    .invoice-footer {
      border-top: 1px solid #f6f4ee;
      padding: 20px 36px;
      text-align: center;
      background: #fafaf8;
      font-size: 13px;
      color: #767066;
    }
  </style>
</head>
<body>

  <!-- Action buttons for display only -->
  <div class="no-print d-flex justify-content-center gap-3 my-4">
    <button onclick="window.print()" class="btn btn-primary btn-lg px-5 py-2 fw-semibold shadow-sm"><i class="bi bi-printer me-2"></i> Print All Invoices</button>
    <button onclick="window.close()" class="btn btn-outline-secondary btn-lg px-4 py-2">Close Window</button>
  </div>

  @foreach($orders as $index => $order)
    <div class="invoice-wrap {{ $index < count($orders) - 1 ? 'page-break' : '' }}">
      <div class="invoice-card">
        <div class="invoice-header">
          <div>
            <h1 class="inv-title">E-Commerce</h1>
            <div class="inv-subtitle">Invoice Receipt</div>
          </div>
          <div class="inv-right">
            <div class="inv-no">#{{ $order->invoice_no }}</div>
            <div class="inv-date">{{ $order->created_at->format('d M Y') }}</div>
          </div>
        </div>

        <div class="invoice-body">
          <div class="invoice-meta">
            <div class="meta-block">
              <h6>Billed To:</h6>
              <p>
                <strong>{{ $order->customer_name }}</strong><br>
                Phone: {{ $order->customer_phone }}<br>
                Address: {{ $order->customer_address }}
              </p>
            </div>
            <div class="meta-block">
              <h6>Payment details:</h6>
              <p>
                Method: {{ strtoupper($order->payment_method) }}<br>
                Status: {{ ucfirst($order->payment_status) }}
              </p>
            </div>
          </div>

          <table class="invoice-table">
            <thead>
              <tr>
                <th>Product Description</th>
                <th style="width: 100px; text-align: center;">Price</th>
                <th style="width: 80px; text-align: center;">Qty</th>
                <th style="width: 120px;">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <div class="item-name">{{ $item->product_name }}</div>
                    @if($item->variants && count($item->variants) > 0)
                      <div class="item-variant">
                        @foreach($item->variants as $k => $v)
                          {{ ucfirst($k) }}: {{ $v }} &nbsp;
                        @endforeach
                      </div>
                    @endif
                  </td>
                  <td style="text-align: center;">৳{{ number_format($item->price, 2) }}</td>
                  <td style="text-align: center;">{{ $item->quantity }}</td>
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
              <span>Shipping</span>
              <span class="val">৳{{ number_format($order->shipping_cost, 2) }}</span>
            </div>
            @if($order->tax > 0)
              <div class="t-line">
                <span>Tax (5%)</span>
                <span class="val">৳{{ number_format($order->tax, 2) }}</span>
              </div>
            @endif
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
    </div>
  @endforeach

  <script>
    // Trigger system print dialog automatically after styles load
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        window.print();
      }, 500);
    });
  </script>
</body>
</html>
