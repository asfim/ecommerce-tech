<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; }
        .auth-card { max-width:400px; margin:80px auto; padding:30px; background:#fff; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.08); }
        .auth-card h3 { font-weight:800; margin-bottom:6px; }
        .auth-card p { color:#888; font-size:13px; margin-bottom:20px; }
        .form-control { border-radius:8px; border:1.5px solid #015475; padding:10px 14px; }
        .form-control:focus { box-shadow:none; border-color:#1a73e8; }
        .btn-auth { background:#1a73e8; color:#fff; border:none; border-radius:8px; padding:10px; font-weight:600; width:100%; transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.2s ease-in-out !important; }
        .btn-auth:hover { background:#1e6fd9 !important; transform: scale(1.03) !important; color:#fff !important; }
        .auth-footer { text-align:center; margin-top:16px; font-size:13px; color:#888; }
        .auth-footer a { color:#1a73e8; font-weight:600; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h3>Welcome Back</h3>
        <p>Login to your account to continue shopping.</p>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('user.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus style="border-color: #a1a1a1 !important;">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required style="border-color: #a1a1a1 !important;">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-auth">Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('user.register') }}">Register</a>
        </div>
    </div>
</body>
</html>
