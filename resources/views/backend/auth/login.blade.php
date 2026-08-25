@php
    $companySettings = \App\Models\HomepageSetting::get('company_settings', []);
    $companyName = $companySettings['name'] ?? config('app.name', 'EcommerceTech');
    $companyLogo = $companySettings['logo'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ $companyName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a0f1e;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        body::before {
            content: '';
            position: fixed; top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: blob1 8s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: fixed; bottom: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: blob2 10s ease-in-out infinite;
        }
        @keyframes blob1 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(80px,60px) scale(1.1)} }
        @keyframes blob2 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(-60px,-40px) scale(1.08)} }

        .admin-card {
            position: relative; z-index: 10;
            width: 100%; max-width: 440px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 24px;
            padding: 44px 40px;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
        }

        .admin-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3);
            border-radius: 100px; padding: 6px 14px;
            font-size: 12px; font-weight: 600; color: #f87171;
            letter-spacing: 0.5px; text-transform: uppercase;
            margin-bottom: 28px;
        }
        .admin-badge i { font-size: 14px; }

        .brand-logo { font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .brand-logo span { color: #60a5fa; }
        .auth-subheading { font-size: 14px; color: rgba(255,255,255,0.45); margin-bottom: 36px; }

        .form-label { font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 8px; display: block; }
        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute; top: 50%; left: 14px;
            transform: translateY(-50%); color: rgba(255,255,255,0.3);
            font-size: 16px; pointer-events: none;
        }
        .form-control-custom {
            width: 100%; padding: 13px 14px 13px 42px;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px; font-size: 14px;
            color: #fff; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-control-custom::placeholder { color: rgba(255,255,255,0.25); }
        .form-control-custom:focus {
            border-color: #3b82f6;
            background: rgba(59,130,246,0.08);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.18);
        }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .remember-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: rgba(255,255,255,0.5); cursor: pointer; }
        .remember-label input { accent-color: #3b82f6; width: 15px; height: 15px; }

        .btn-admin-login {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff; font-size: 15px; font-weight: 700;
            border: none; border-radius: 12px; cursor: pointer;
            letter-spacing: 0.3px; transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(239,68,68,0.4);
        }
        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(239,68,68,0.55);
            background: linear-gradient(135deg, #f87171, #ef4444);
        }
        .security-note {
            display: flex; align-items: center; gap: 8px;
            margin-top: 20px; font-size: 12px; color: rgba(255,255,255,0.3);
            justify-content: center;
        }
        .security-note i { color: #22c55e; }
        .alert-custom {
            padding: 12px 16px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 10px; color: #f87171;
            font-size: 13px; margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .admin-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <div class="admin-card">
        <div class="admin-badge"><i class="bi bi-shield-lock-fill"></i> Admin Access</div>
        @if($companyLogo)
            <a href="{{ route('home') }}" style="display:inline-block;margin-bottom:10px;">
                <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="max-height:48px;">
            </a>
        @else
            <div class="brand-logo"><i class="bi bi-bag-heart-fill me-2"></i>{{ $companyName }}</div>
        @endif
        <p class="auth-subheading">Restricted area. Authorized personnel only.</p>

        @if($errors->any())
            <div class="alert-custom"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div>
                <label class="form-label">Admin Email</label>
                <div class="input-group-custom">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="admin@ecommercetech.com" required autofocus>
                </div>
            </div>
            <div>
                <label class="form-label">Password</label>
                <div class="input-group-custom">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="adminPwd" class="form-control-custom" placeholder="Enter your password" required>
                    <i class="bi bi-eye-slash" id="toggleAdminPwd" style="position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:rgba(255,255,255,0.3);font-size:16px;"></i>
                </div>
            </div>
            <div class="form-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember"> Keep me signed in
                </label>
            </div>
            <button type="submit" class="btn-admin-login"><i class="bi bi-shield-lock me-2"></i>Secure Login</button>
        </form>

        <div class="security-note">
            <i class="bi bi-lock-fill"></i> 256-bit SSL encrypted connection
        </div>
    </div>

    <script>
        const toggle = document.getElementById('toggleAdminPwd');
        const pwd = document.getElementById('adminPwd');
        toggle.addEventListener('click', () => {
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            toggle.className = pwd.type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.style.cssText = 'position:absolute;top:50%;right:14px;transform:translateY(-50%);cursor:pointer;color:rgba(255,255,255,0.3);font-size:16px;';
        });
    </script>
</body>
</html>
