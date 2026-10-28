@extends('layouts.backend.app')

@section('title', 'Courier Settings')

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
  <h4>Courier Settings</h4>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="stat-card mb-4">
      @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('admin.settings.courier.update') }}">
        @csrf

        <!-- General Fraud Rules -->
        <div class="mb-4 pb-3 border-bottom">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-danger p-2 me-2"><i class="bi bi-shield-x fs-5 text-white"></i></span>
            <div>
              <h5 class="fw-bold text-dark mb-0">Courier Fraud Detection Rules (কুরিয়ার ফ্রড রুলস)</h5>
              <div class="text-muted small">যেকোনো কুরিয়ারের এপিআই না থাকলে তা স্বয়ংক্রিয়ভাবে <strong>বাইপাস (Bypass)</strong> হয়ে যাবে এবং স্টোরের নিজস্ব ডাটা দিয়ে ফ্রড চেক চলবে।</div>
            </div>
          </div>

          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="form-label fw-bold">
                Cancellation Threshold (ক্যান্সেল লিমিট)
                <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-x-circle text-danger"></i></span>
                <input type="number" name="cancel_threshold" class="form-control" value="{{ old('cancel_threshold', $fraudSettings['cancel_threshold'] ?? 2) }}" min="1" max="50" required style="border-color: #a1a1a1 !important;">
                <span class="input-group-text bg-light text-muted">পার্সেল</span>
              </div>
              <div class="form-text text-muted">কুরিয়ার বা স্টোর মিলিয়ে এতটি পার্সেল বাতিল থাকলে অর্ডারটি লাল রঙে ফ্রড দেখাবে।</div>
              @error('cancel_threshold') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">
                Cancel Rate Threshold (ক্যান্সেল পার্সেন্টেজ)
                <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-percent text-warning"></i></span>
                <input type="number" step="0.1" name="cancel_rate_threshold" class="form-control" value="{{ old('cancel_rate_threshold', $fraudSettings['cancel_rate_threshold'] ?? 40) }}" min="1" max="100" required style="border-color: #a1a1a1 !important;">
                <span class="input-group-text bg-light text-muted">%</span>
              </div>
              <div class="form-text text-muted">কাস্টমারের মোট অর্ডারের মধ্যে ক্যান্সেলেশন রেট এই হার ছাড়ালে ফ্রড হবে।</div>
              @error('cancel_rate_threshold') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" role="switch" id="auto_block_ip" name="auto_block_ip" value="1" {{ (!empty($fraudSettings['auto_block_ip'])) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="auto_block_ip">
                  Auto-Block Customer IP on Fraud Detection (ফ্রড চিহ্নিত হলে স্বয়ংক্রিয়ভাবে আইপি ব্লক করুন)
                </label>
                <div class="form-text text-muted">চিহ্নিত ফ্রড ইউজার পরবর্তীতে আর কোনো অর্ডার দিতে পারবে না।</div>
              </div>
            </div>
          </div>
        </div>

        <!-- 1. Steadfast Courier Direct API -->
        <div class="card p-3 mb-3 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-truck text-primary fs-4 me-2"></i>
              <div>
                <h6 class="fw-bold mb-0">Steadfast Courier (সরাসরি এপিআই)</h6>
                <span class="text-muted small">সরাসরি স্টিডফাস্ট বুকিং এবং ফ্রড চেক এপিআই</span>
              </div>
            </div>
            @if(!empty($steadfastSettings['api_key']) && !empty($steadfastSettings['secret_key']))
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-slash-circle me-1"></i> Bypassed (API Key নেই)</span>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Steadfast API Key</label>
              <input type="text" name="steadfast_api_key" class="form-control form-control-sm" value="{{ old('steadfast_api_key', $steadfastSettings['api_key'] ?? '') }}" placeholder="Enter Steadfast API Key" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Steadfast Secret Key</label>
              <input type="password" name="steadfast_secret_key" class="form-control form-control-sm" value="{{ old('steadfast_secret_key', $steadfastSettings['secret_key'] ?? '') }}" placeholder="Enter Steadfast Secret Key" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Steadfast Base URL</label>
              <input type="text" name="steadfast_base_url" class="form-control form-control-sm" value="{{ old('steadfast_base_url', $steadfastSettings['base_url'] ?? 'https://portal.packzy.com/api/v1') }}" placeholder="https://portal.packzy.com/api/v1" style="border-color: #a1a1a1 !important;">
            </div>
          </div>
        </div>

        <!-- 2. Pathao Courier Direct API -->
        <div class="card p-3 mb-3 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-bicycle text-danger fs-4 me-2"></i>
              <div>
                <h6 class="fw-bold mb-0">Pathao Courier (সরাসরি এপিআই)</h6>
                <span class="text-muted small">পাঠাও সরাসরি মার্চেন্ট এপিআই (না থাকলে ফাঁকা রাখুন, বাইপাস হবে)</span>
              </div>
            </div>
            @if(!empty($pathaoSettings['token']) || !empty($pathaoSettings['client_id']))
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-slash-circle me-1"></i> Bypassed (API Key নেই)</span>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Pathao Access Token / Key</label>
              <input type="text" name="pathao_token" class="form-control form-control-sm" value="{{ old('pathao_token', $pathaoSettings['token'] ?? '') }}" placeholder="Enter Pathao Token or API Key" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Pathao Client ID (Optional)</label>
              <input type="text" name="pathao_client_id" class="form-control form-control-sm" value="{{ old('pathao_client_id', $pathaoSettings['client_id'] ?? '') }}" placeholder="Pathao Client ID" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Pathao Base URL</label>
              <input type="text" name="pathao_base_url" class="form-control form-control-sm" value="{{ old('pathao_base_url', $pathaoSettings['base_url'] ?? 'https://api-hermes.pathao.com') }}" placeholder="https://api-hermes.pathao.com" style="border-color: #a1a1a1 !important;">
            </div>
          </div>
        </div>

        <!-- 3. RedX Delivery Direct API -->
        <div class="card p-3 mb-3 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-box-seam text-danger fs-4 me-2"></i>
              <div>
                <h6 class="fw-bold mb-0">RedX Delivery (সরাসরি এপিআই)</h6>
                <span class="text-muted small">রেডএক্স মার্চেন্ট ওপেন এপিআই (না থাকলে ফাঁকা রাখুন, বাইপাস হবে)</span>
              </div>
            </div>
            @if(!empty($redxSettings['access_token']))
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-slash-circle me-1"></i> Bypassed (API Key নেই)</span>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-md-8">
              <label class="form-label small fw-bold">RedX API Access Token</label>
              <input type="text" name="redx_access_token" class="form-control form-control-sm" value="{{ old('redx_access_token', $redxSettings['access_token'] ?? '') }}" placeholder="Enter RedX API Access Token" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">RedX Base URL</label>
              <input type="text" name="redx_base_url" class="form-control form-control-sm" value="{{ old('redx_base_url', $redxSettings['base_url'] ?? 'https://openapi.redx.com.bd') }}" placeholder="https://openapi.redx.com.bd" style="border-color: #a1a1a1 !important;">
            </div>
          </div>
        </div>

        <!-- 4. Paperfly Courier Direct API -->
        <div class="card p-3 mb-3 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-send text-primary fs-4 me-2"></i>
              <div>
                <h6 class="fw-bold mb-0">Paperfly Courier (সরাসরি এপিআই)</h6>
                <span class="text-muted small">পেপারফ্লাই মার্চেন্ট এপিআই (না থাকলে ফাঁকা রাখুন, বাইপাস হবে)</span>
              </div>
            </div>
            @if(!empty($paperflySettings['username']) && !empty($paperflySettings['password']))
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-slash-circle me-1"></i> Bypassed (API Key নেই)</span>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Paperfly Username</label>
              <input type="text" name="paperfly_username" class="form-control form-control-sm" value="{{ old('paperfly_username', $paperflySettings['username'] ?? '') }}" placeholder="Enter Paperfly Username" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Paperfly Password</label>
              <input type="password" name="paperfly_password" class="form-control form-control-sm" value="{{ old('paperfly_password', $paperflySettings['password'] ?? '') }}" placeholder="Enter Paperfly Password" style="border-color: #a1a1a1 !important;">
            </div>
          </div>
        </div>

        <!-- 5. eCourier Direct API -->
        <div class="card p-3 mb-4 border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
              <i class="bi bi-lightning text-warning fs-4 me-2"></i>
              <div>
                <h6 class="fw-bold mb-0">eCourier (সরাসরি এপিআই)</h6>
                <span class="text-muted small">ই-কুরিয়ার মার্চেন্ট এপিআই (না থাকলে ফাঁকা রাখুন, বাইপাস হবে)</span>
              </div>
            </div>
            @if(!empty($ecourierSettings['api_key']))
              <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
            @else
              <span class="badge bg-secondary"><i class="bi bi-slash-circle me-1"></i> Bypassed (API Key নেই)</span>
            @endif
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold">eCourier API Key</label>
              <input type="text" name="ecourier_api_key" class="form-control form-control-sm" value="{{ old('ecourier_api_key', $ecourierSettings['api_key'] ?? '') }}" placeholder="Enter eCourier API Key" style="border-color: #a1a1a1 !important;">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">eCourier API Secret</label>
              <input type="password" name="ecourier_api_secret" class="form-control form-control-sm" value="{{ old('ecourier_api_secret', $ecourierSettings['api_secret'] ?? '') }}" placeholder="Enter eCourier API Secret" style="border-color: #a1a1a1 !important;">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary px-4 py-2"><i class="bi bi-save me-1"></i> Save All Courier Settings</button>
      </form>
    </div>
  </div>

  <!-- Live Phone Fraud Check Tester -->
  <div class="col-lg-4">
    <div class="stat-card">
      <h6 class="fw-bold text-dark mb-2"><i class="bi bi-search me-2 text-primary"></i>Test Any Phone Number</h6>
      <p class="text-muted small">যেকোনো ফোন নম্বর লিখে পরীক্ষা করুন। যে কুরিয়ারের এপিআই নেই সেটি সরাসরি <strong>Bypassed</strong> দেখাবে এবং স্টোর হিস্টোরির ভিত্তিতে ফ্রড রেজাল্ট দেখাবে।</p>
      
      <form id="settingsPhoneTesterForm">
        <div class="mb-3">
          <label class="form-label fw-bold small">Customer Mobile Number</label>
          <input type="text" id="settingsTestPhone" class="form-control" placeholder="01XXXXXXXXX" required style="border-color: #a1a1a1 !important;">
        </div>
        <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-shield-check me-1"></i> Check Multi-Courier Data</button>
      </form>

      <div id="settingsTestResult" class="mt-3" style="display: none;"></div>
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
            resultBox.innerHTML = '<div class="text-center py-3 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Checking couriers...</div>';

            fetch(`{{ route('admin.orders.check-phone') }}?phone=${encodeURIComponent(phone)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    resultBox.innerHTML = `<div class="alert alert-danger py-2 small">${data.message || 'Error checking phone.'}</div>`;
                    return;
                }

                let courierRows = '';
                if (data.couriers) {
                    for (const [cKey, cVal] of Object.entries(data.couriers)) {
                        const isBypassed = cVal.status === 'bypassed';
                        const badgeStatus = isBypassed 
                            ? '<span class="badge bg-light text-muted border" style="font-size:9px;">Bypassed</span>' 
                            : '<span class="badge bg-success-subtle text-success border border-success" style="font-size:9px;">Active</span>';

                        courierRows += `
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 11px;">
                                <span><i class="bi bi-${cVal.icon || 'box'} me-1 text-primary"></i> ${cVal.name}</span>
                                <span>
                                    ${isBypassed ? badgeStatus : `<span class="text-success fw-bold">${cVal.delivered} del</span> / <span class="text-danger fw-bold">${cVal.cancelled} can</span>`}
                                </span>
                            </div>
                        `;
                    }
                }

                resultBox.innerHTML = `
                    <div class="card p-2 border-${data.risk_color}" style="background-color: ${data.is_fraud ? '#fee2e2' : '#f0fdf4'}; font-size: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>${data.clean_phone}</strong>
                            <span class="badge bg-${data.risk_color}">${data.risk_level}</span>
                        </div>
                        <div class="mb-2">
                            Total Parcels: <strong>${data.total_parcels}</strong> | 
                            Delivered: <strong class="text-success">${data.total_delivered}</strong> | 
                            Cancelled: <strong class="text-danger">${data.total_cancelled}</strong>
                        </div>
                        <div class="mb-2">
                            Cancel Rate: <strong class="${data.cancel_rate >= 40 ? 'text-danger' : 'text-dark'}">${data.cancel_rate}%</strong>
                        </div>
                        <div class="bg-white p-2 rounded mb-2 border">
                            <div class="fw-bold mb-1" style="font-size: 11px;">Couriers Status & Breakdown:</div>
                            ${courierRows}
                        </div>
                        ${data.reasons && data.reasons.length ? `<div class="text-danger fw-bold small">${data.reasons[0]}</div>` : '<div class="text-success small">No fraud signals found.</div>'}
                    </div>
                `;
            })
            .catch(err => {
                resultBox.innerHTML = `<div class="alert alert-danger py-2 small">Failed to query courier data.</div>`;
            });
        });
    }
});
</script>
@endpush
@endsection
