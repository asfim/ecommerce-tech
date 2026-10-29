@extends('layouts.backend.app')

@section('title', 'Order Fraud Settings')

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
  <h4 class="mb-1"><i class="bi bi-shield-check text-danger me-2"></i>Order Fraud & Cancellation Settings</h4>
  <p class="text-muted small mb-0">অর্ডার ক্যান্সেলেশন হিস্টোরি এবং ফ্রড ডিটেকশন রুলস কনফিগার করুন (কোনো এক্সটার্নাল এপিআই প্রয়োজন নেই)।</p>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="stat-card mb-4">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.settings.courier.update') }}">
        @csrf

        <div class="d-flex align-items-center mb-3">
          <span class="badge bg-danger p-2 me-2"><i class="bi bi-shield-x fs-5 text-white"></i></span>
          <div>
            <h5 class="fw-bold text-dark mb-0">Order Cancellation Fraud Rules (ক্যান্সেলেশন ফ্রড রুলস)</h5>
            <div class="text-muted small">কাস্টমারের অতীত অর্ডার বাতিলের রেকর্ডের ওপর ভিত্তি করে ফ্রড চিহ্নিত হবে।</div>
          </div>
        </div>

        <div class="bg-light p-3 rounded mb-4 border">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small text-dark">
                Cancellation Threshold (ক্যান্সেল অর্ডার লিমিট)
                <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-x-circle text-danger"></i></span>
                <input type="number" name="cancel_threshold" class="form-control" value="{{ old('cancel_threshold', $fraudSettings['cancel_threshold'] ?? 2) }}" min="1" max="50" required style="border-color: #a1a1a1 !important;">
                <span class="input-group-text bg-white text-muted">অর্ডার</span>
              </div>
              <div class="form-text text-muted" style="font-size: 11px;">
                কাস্টমারের মোট এতটি বা তার বেশি অর্ডার বাতিল (Cancelled / Returned) থাকলে নতুন অর্ডার সরাসরি <strong>FRAUD</strong> চিহ্নিত হবে এবং রো লাল হবে।
              </div>
              @error('cancel_threshold') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold small text-dark">
                Cancel Rate Threshold (ক্যান্সেল শতকরা হার)
                <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-percent text-warning"></i></span>
                <input type="number" step="0.1" name="cancel_rate_threshold" class="form-control" value="{{ old('cancel_rate_threshold', $fraudSettings['cancel_rate_threshold'] ?? 40) }}" min="1" max="100" required style="border-color: #a1a1a1 !important;">
                <span class="input-group-text bg-white text-muted">%</span>
              </div>
              <div class="form-text text-muted" style="font-size: 11px;">
                কাস্টমারের মোট অর্ডারের মধ্যে বাতিলের হার এই শতাংশ ছাড়ালে ফ্রড হিসেবে চিহ্নিত হবে (ডিফল্ট: ৪০%)।
              </div>
              @error('cancel_rate_threshold') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 pt-2 border-top">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="auto_block_ip" name="auto_block_ip" value="1" {{ (!empty($fraudSettings['auto_block_ip'])) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold small text-dark" for="auto_block_ip">
                  Auto-Block Customer IP on Fraud Detection (ফ্রড চিহ্নিত হলে স্বয়ংক্রিয়ভাবে কাস্টমারের আইপি ব্লক করুন)
                </label>
                <div class="form-text text-muted" style="font-size: 11px;">
                  অনুমোদিত ফ্রড চিহ্নিত গ্রাহক আর পুনরায় নতুন অর্ডার প্লেস করতে পারবে না।
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card p-3 mb-4 border bg-white shadow-sm">
          <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-info-circle text-primary me-2"></i>ফ্রড চেক যেভাবে কাজ করে:</h6>
          <ul class="small text-muted mb-0 ps-3">
            <li class="mb-1"><strong>১০০% ডাটাবেজ ভিত্তিক:</strong> কোনো জটিল কুরিয়ার এপিআই ছাড়াই আপনার স্টোরের নিজস্ব অর্ডার হিস্টোরি বিশ্লেষণ করে দ্রুত ফ্রড নির্ণয় হয়।</li>
            <li class="mb-1"><strong>অর্ডার ক্যান্সেলেশন বিশ্লেষণ:</strong> কোনো ফোন নাম্বারে পূর্বে কতটি অর্ডার ছিল, কতটি ডেলিভারি হয়েছে এবং কতটি বাতিল হয়েছে তা গণনা করা হয়।</li>
            <li class="mb-1"><strong>অটো-ফ্ল্যাগ ও রেড হাইলাইট:</strong> নির্ধারিত লিমিটের বেশি বাতিল অর্ডার থাকলে অ্যাডমিন প্যানেলে রো-টি লাল রঙে হাইলাইট হবে এবং কাস্টমার আইপি সুরক্ষিত থাকবে।</li>
          </ul>
        </div>

        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
          <i class="bi bi-save me-1"></i> Save Fraud Detection Rules
        </button>
      </form>
    </div>
  </div>

  <!-- Live Phone Fraud Check Tester -->
  <div class="col-lg-5">
    <div class="stat-card">
      <div class="d-flex align-items-center mb-3">
        <span class="badge bg-primary p-2 me-2"><i class="bi bi-telephone-inbound fs-5 text-white"></i></span>
        <div>
          <h5 class="fw-bold text-dark mb-0">Check Any Phone Number</h5>
          <div class="text-muted small">ফোন নাম্বার লিখে তার অর্ডার ক্যান্সেলেশন হিস্টোরি ও ফ্রড রিস্ক দেখুন</div>
        </div>
      </div>
      
      <form id="settingsPhoneTesterForm" class="mb-3">
        <div class="mb-3">
          <label class="form-label fw-bold small">Customer Mobile Number</label>
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
            <input type="text" id="settingsTestPhone" class="form-control" placeholder="017XXXXXXXX" required style="border-color: #a1a1a1 !important;">
            <button type="submit" class="btn btn-danger fw-semibold px-3">
              <i class="bi bi-search me-1"></i> Check Risk
            </button>
          </div>
        </div>
      </form>

      <div id="settingsTestResult" style="display: none;"></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const testerForm = document.getElementById('settingsPhoneTesterForm');
    const resultBox = document.getElementById('settingsTestResult');

    if (testerForm) {
        testerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const phone = document.getElementById('settingsTestPhone').value.trim();
            if (!phone) return;

            resultBox.style.display = 'block';
            resultBox.innerHTML = '<div class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Checking order cancellation history...</div>';

            fetch(`{{ route('admin.orders.check-phone') }}?phone=${encodeURIComponent(phone)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    resultBox.innerHTML = `<div class="alert alert-danger py-2 small">${data.message || 'Error checking phone.'}</div>`;
                    return;
                }

                const stats = data.stats || {};
                const total = stats.total || 0;
                const delivered = stats.delivered || 0;
                const cancelled = (stats.cancelled || 0) + (stats.returned || 0);
                const cancelRate = data.cancel_rate || 0;
                const successRate = data.success_rate || 0;

                let reasonsHtml = '';
                if (data.reasons && data.reasons.length > 0) {
                    reasonsHtml = '<div class="alert alert-danger p-2 mb-2" style="font-size:11px;"><strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Detected Risk Factors:</strong><ul class="mb-0 ps-3">' +
                        data.reasons.map(r => `<li>${r}</li>`).join('') + '</ul></div>';
                } else {
                    reasonsHtml = '<div class="alert alert-success p-2 mb-2" style="font-size:11px;"><i class="bi bi-check-circle-fill me-1"></i> No past fraudulent cancellation history detected.</div>';
                }

                resultBox.innerHTML = `
                    <div class="card p-3 border-${data.risk_color} shadow-sm" style="background-color: ${data.is_fraud ? '#fee2e2' : '#f0fdf4'}; font-size: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-6 fw-bold text-dark"><i class="bi bi-phone me-1"></i> ${data.clean_phone}</span>
                            <span class="badge bg-${data.risk_color} px-2 py-1 fs-6">${data.risk_level} (${data.risk_score}%)</span>
                        </div>

                        ${reasonsHtml}

                        <div class="bg-white p-2 rounded mb-2 border">
                            <div class="fw-bold mb-2 text-dark" style="font-size: 11px;"><i class="bi bi-clock-history me-1 text-primary"></i> Customer Store Order History:</div>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="fw-bold fs-6 text-dark">${total}</div>
                                    <div class="text-muted" style="font-size:10px;">Total Orders</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-6 text-success">${delivered}</div>
                                    <div class="text-muted" style="font-size:10px;">Delivered</div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-6 ${cancelled > 0 ? 'text-danger' : 'text-dark'}">${cancelled}</div>
                                    <div class="text-muted" style="font-size:10px;">Cancelled</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top text-muted" style="font-size: 11px;">
                                <span>Cancellation Rate: <strong class="${cancelRate >= 40 ? 'text-danger' : 'text-dark'}">${cancelRate}%</strong></span>
                                <span>Delivery Success: <strong class="text-success">${successRate}%</strong></span>
                            </div>
                        </div>

                        ${data.is_fraud ? '<div class="badge bg-danger w-100 py-1 text-uppercase"><i class="bi bi-shield-x me-1"></i> Fraud Likely - High Cancellations</div>' : '<div class="badge bg-success w-100 py-1 text-uppercase"><i class="bi bi-shield-check me-1"></i> Safe Customer</div>'}
                    </div>
                `;
            })
            .catch(err => {
                resultBox.innerHTML = `<div class="alert alert-danger py-2 small">Failed to query order cancellation data.</div>`;
            });
        });
    }
});
</script>
@endpush
@endsection
