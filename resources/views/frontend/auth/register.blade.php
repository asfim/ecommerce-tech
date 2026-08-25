@php
    $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
    $companyName = $companySettings['name'] ?? config('app.name', 'EcommerceTech');
    $companyLogo = $companySettings['logo'] ?? null;
    $companyTagline = $companySettings['tagline'] ?? 'Join thousands of happy shoppers discovering the best tech deals.';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — {{ $companyName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; }

        .auth-panel-left {
            width: 40%;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f4c81 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }
        .auth-panel-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .auth-panel-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .brand-logo { font-size: 26px; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 12px; }
        .brand-logo span { color: #60a5fa; }
        .brand-tagline { color: rgba(255,255,255,0.6); font-size: 14px; text-align: center; line-height: 1.6; max-width: 260px; margin-bottom: 40px; }
        .steps-list { width: 100%; max-width: 280px; }
        .step-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 16px 18px; border-radius: 12px;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 12px; color: #e2e8f0;
        }
        .step-num {
            width: 28px; height: 28px; border-radius: 50%;
            background: #3b82f6; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .step-text strong { display: block; font-size: 13px; font-weight: 600; color: #fff; }
        .step-text span { font-size: 12px; color: rgba(255,255,255,0.5); }

        .auth-panel-right {
            width: 60%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 60px;
            overflow-y: auto;
        }
        .auth-form-wrap { width: 100%; max-width: 480px; }
        .auth-heading { font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .auth-subheading { font-size: 14px; color: #64748b; margin-bottom: 32px; }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
        .input-group-custom { position: relative; margin-bottom: 18px; }
        .input-icon {
            position: absolute; top: 50%; left: 14px;
            transform: translateY(-50%); color: #94a3b8;
            font-size: 16px; pointer-events: none;
        }
        .form-control-custom {
            width: 100%; padding: 13px 14px 13px 42px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; background: #fff; color: #0f172a;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control-custom:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .form-control-custom::placeholder { color: #b0bec5; }
        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn-register {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff; font-size: 15px; font-weight: 700;
            border: none; border-radius: 10px; cursor: pointer;
            letter-spacing: 0.3px; transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59,130,246,0.35);
            margin-top: 8px;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59,130,246,0.45); background: linear-gradient(135deg, #1d4ed8, #2563eb); }
        .divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; color: #94a3b8; font-size: 13px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .auth-footer-link { text-align: center; font-size: 14px; color: #64748b; }
        .auth-footer-link a { color: #3b82f6; font-weight: 600; text-decoration: none; }
        .auth-footer-link a:hover { text-decoration: underline; }
        .alert-custom { padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; color: #dc2626; font-size: 13px; margin-bottom: 20px; }
        .password-strength { height: 4px; border-radius: 4px; margin-top: 6px; background: #e2e8f0; overflow: hidden; }
        .password-strength-bar { height: 100%; width: 0; border-radius: 4px; transition: width 0.3s, background 0.3s; }
        .terms-note { font-size: 12px; color: #94a3b8; text-align: center; margin-top: 16px; }
        .terms-note a { color: #3b82f6; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .auth-panel-left { width: 100%; padding: 40px 30px; min-height: auto; }
            .steps-list { display: none; }
            .brand-tagline { margin-bottom: 0; }
            .auth-panel-right { width: 100%; padding: 40px 24px; }
            .form-row-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- LEFT BRAND PANEL -->
    <div class="auth-panel-left">
        @if($companyLogo)
            <a href="{{ route('home') }}" style="display:inline-block;margin-bottom:16px;">
                <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height:52px;">
            </a>
        @else
            <div class="brand-logo"><i class="bi bi-bag-heart-fill me-2"></i>{{ $companyName }}</div>
        @endif
        <p class="brand-tagline">{{ $companyTagline }}</p>
        <div class="steps-list">
            <div class="step-item"><div class="step-num">1</div><div class="step-text"><strong>Create your account</strong><span>It's free & takes 30 seconds</span></div></div>
            <div class="step-item"><div class="step-num">2</div><div class="step-text"><strong>Browse & shop</strong><span>Explore thousands of products</span></div></div>
            <div class="step-item"><div class="step-num">3</div><div class="step-text"><strong>Fast delivery</strong><span>Get orders right to your door</span></div></div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-panel-right">
        <div class="auth-form-wrap">
            <h1 class="auth-heading">Create your account ✨</h1>
            <p class="auth-subheading">Fill in the details below to get started in minutes.</p>

            @if($errors->any())
                <div class="alert-custom"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('user.register.submit') }}">
                @csrf
                <div>
                    <label class="form-label">Full Name</label>
                    <div class="input-group-custom">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                    </div>
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="form-row-2">
                    <div>
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" id="password" class="form-control-custom" placeholder="Min. 8 characters" required>
                        </div>
                        <div class="password-strength"><div class="password-strength-bar" id="strengthBar"></div></div>
                    </div>
                    <div>
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control-custom" placeholder="Repeat password" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-register">Create Account <i class="bi bi-arrow-right ms-1"></i></button>
                <p class="terms-note">By creating an account, you agree to our <a href="{{ route('page.show', 'terms-conditions') }}" target="_blank">Terms of Service</a> and <a href="{{ route('page.show', 'privacy-policy') }}" target="_blank">Privacy Policy</a>.</p>
            </form>

            <div class="divider">or</div>
            <div class="auth-footer-link">Already have an account? <a href="{{ route('user.login') }}">Sign in</a></div>
        </div>
    </div>

    <script>
        const pwd = document.getElementById('password');
        const bar = document.getElementById('strengthBar');
        pwd.addEventListener('input', () => {
            const v = pwd.value;
            let strength = 0;
            if (v.length >= 8) strength++;
            if (/[A-Z]/.test(v)) strength++;
            if (/[0-9]/.test(v)) strength++;
            if (/[^A-Za-z0-9]/.test(v)) strength++;
            const widths = ['0%', '25%', '50%', '75%', '100%'];
            const colors = ['', '#ef4444', '#f97316', '#eab308', '#22c55e'];
            bar.style.width = widths[strength];
            bar.style.background = colors[strength];
        });
    </script>
</body>
</html>
