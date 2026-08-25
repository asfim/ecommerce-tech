@php
    $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
    $companyName = $companySettings['name'] ?? config('app.name', 'EcommerceTech');
    $companyLogo = $companySettings['logo'] ?? null;
    $companyTagline = $companySettings['tagline'] ?? 'Your ultimate destination for premium tech products and accessories.';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $companyName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; }

        /* LEFT PANEL */
        .auth-panel-left {
            width: 45%;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f4c81 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
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
        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }
        .brand-logo span { color: #60a5fa; }
        .brand-tagline {
            color: rgba(255,255,255,0.6);
            font-size: 14px;
            text-align: center;
            line-height: 1.6;
            max-width: 280px;
            margin-bottom: 48px;
        }
        .feature-list { width: 100%; max-width: 300px; }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 12px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 12px;
            color: #e2e8f0;
            font-size: 14px;
        }
        .feature-item i { color: #60a5fa; font-size: 20px; flex-shrink: 0; }

        /* RIGHT PANEL */
        .auth-panel-right {
            width: 55%;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 60px;
        }
        .auth-form-wrap { width: 100%; max-width: 420px; }
        .auth-heading { font-size: 30px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .auth-subheading { font-size: 14px; color: #64748b; margin-bottom: 36px; }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute;
            top: 50%; left: 14px;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
        }
        .form-control-custom {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .form-control-custom::placeholder { color: #b0bec5; }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .remember-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; cursor: pointer; }
        .remember-label input { accent-color: #3b82f6; width: 15px; height: 15px; }
        .forgot-link { font-size: 13px; color: #3b82f6; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59,130,246,0.35);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.45);
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }
        .divider { display: flex; align-items: center; gap: 12px; margin: 28px 0; color: #94a3b8; font-size: 13px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .auth-footer-link { text-align: center; font-size: 14px; color: #64748b; }
        .auth-footer-link a { color: #3b82f6; font-weight: 600; text-decoration: none; }
        .auth-footer-link a:hover { text-decoration: underline; }
        .alert-custom {
            padding: 12px 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #dc2626;
            font-size: 13px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .auth-panel-left { width: 100%; padding: 40px 30px; min-height: auto; }
            .feature-list { display: none; }
            .brand-tagline { margin-bottom: 0; }
            .auth-panel-right { width: 100%; padding: 40px 24px; }
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
        <div class="feature-list">
            <div class="feature-item"><i class="bi bi-shield-check-fill"></i> Secure & Encrypted Login</div>
            <div class="feature-item"><i class="bi bi-truck"></i> Fast & Reliable Nationwide Delivery</div>
            <div class="feature-item"><i class="bi bi-patch-check-fill"></i> 100% Authentic Products Guarantee</div>
            <div class="feature-item"><i class="bi bi-headset"></i> 24/7 Customer Support</div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-panel-right">
        <div class="auth-form-wrap">
            <h1 class="auth-heading">Welcome back 👋</h1>
            <p class="auth-subheading">Sign in to continue shopping and manage your orders.</p>

            @if($errors->any())
                <div class="alert-custom"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('user.login.submit') }}">
                @csrf
                <div>
                    <label class="form-label">Email Address</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    </div>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="form-control-custom" placeholder="Enter your password" required>
                        <i class="bi bi-eye-slash" id="togglePassword" style="position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:16px;"></i>
                    </div>
                </div>
                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
                <button type="submit" class="btn-login">Sign In <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <div class="divider">or</div>
            <div class="auth-footer-link">Don't have an account? <a href="{{ route('user.register') }}">Create one now</a></div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const pwd = document.getElementById('password');
        toggle.addEventListener('click', () => {
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            toggle.className = pwd.type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.style.cssText = 'position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:16px;';
        });
    </script>
</body>
</html>
