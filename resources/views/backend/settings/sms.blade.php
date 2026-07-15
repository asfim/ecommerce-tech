@extends('layouts.backend.app')

@section('title', 'SMS Settings')

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
  <h4>SMS Settings</h4>
</div>

<div class="stat-card">
  @if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
  @endif

  <div class="mb-4">
    <h5 class="fw-bold text-dark"><i class="bi bi-chat-left-text me-2 text-primary"></i>SMS Gateway Integration</h5>
    <p class="text-muted small">Configure your SMS gateway credentials below. When enabled, a customizable SMS will be automatically sent to customers when their order status is updated to "Delivered".</p>
  </div>

  <form method="POST" action="{{ route('admin.settings.sms.update') }}">
    @csrf

    <div class="form-check form-switch mb-4">
      <input class="form-check-input" type="checkbox" name="enabled" id="smsEnabled" value="1" {{ old('enabled', $settings['enabled'] ?? false) ? 'checked' : '' }}>
      <label class="form-check-label fw-bold" for="smsEnabled">Enable SMS Notifications</label>
      <div class="form-text text-muted">Toggle to turn on/off SMS notifications globally.</div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">SMS Gateway Provider</label>
      <select name="gateway" class="form-select" required>
        <option value="mim_sms" {{ old('gateway', $settings['gateway'] ?? '') === 'mim_sms' ? 'selected' : '' }}>MimSMS (mimsms.com)</option>
        <option value="greenweb" {{ old('gateway', $settings['gateway'] ?? '') === 'greenweb' ? 'selected' : '' }}>Greenweb SMS (greenweb.com.bd)</option>
        <option value="elitbuzz" {{ old('gateway', $settings['gateway'] ?? '') === 'elitbuzz' ? 'selected' : '' }}>ElitBuzz SMS (elitbuzz-bd.com)</option>
        <option value="bulksmsbd" {{ old('gateway', $settings['gateway'] ?? '') === 'bulksmsbd' ? 'selected' : '' }}>BulkSMSBD (bulksmsbd.com)</option>
      </select>
      @error('gateway') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">API Key / Token</label>
      <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $settings['api_key'] ?? '') }}" placeholder="Enter your SMS API key or token" required style="border-color: #a1a1a1 !important;">
      <div class="form-text text-muted">Use the API Token/Key provided by your selected SMS gateway.</div>
      @error('api_key') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Sender ID / Mask / Number</label>
      <input type="text" name="sender_id" class="form-control" value="{{ old('sender_id', $settings['sender_id'] ?? '') }}" placeholder="Enter Sender ID (e.g. 88017XXXXXXXX or Approved Mask)" style="border-color: #a1a1a1 !important;">
      <div class="form-text text-muted">Leave empty if your gateway does not require a Sender ID/Mask (e.g., Greenweb non-masking).</div>
      @error('sender_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Message Template</label>
      <textarea name="message_template" class="form-control" rows="4" required placeholder="Enter SMS message template">{{ old('message_template', $settings['message_template'] ?? '') }}</textarea>
      @error('message_template') <div class="text-danger small">{{ $message }}</div> @enderror
      
      <div class="card bg-light border-0 mt-2">
        <div class="card-body p-3">
          <h6 class="fw-bold mb-2 small"><i class="bi bi-info-circle text-primary me-1"></i> Available Dynamic Variables</h6>
          <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-secondary text-white"><code>{customer_name}</code> - Customer's Full Name</span>
            <span class="badge bg-secondary text-white"><code>{invoice_no}</code> - Order Invoice Code</span>
            <span class="badge bg-secondary text-white"><code>{total_amount}</code> - Total Paid Amount</span>
            <span class="badge bg-secondary text-white"><code>{order_status}</code> - Order status (e.g., delivered)</span>
          </div>
          <div class="form-text text-muted mt-2 small">These placeholders will be dynamically replaced when sending the message.</div>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Configuration</button>
  </form>
</div>
@endsection
