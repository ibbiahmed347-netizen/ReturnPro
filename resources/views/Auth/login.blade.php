<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReturnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login-card .card-header {
            background: #1e3c72;
            color: #fff;
            text-align: center;
            border-radius: 12px 12px 0 0 !important;
            padding: 30px 20px;
        }
        .login-card .card-header h3 {
            margin: 0;
            font-weight: 700;
        }
        .login-card .card-header small {
            opacity: 0.8;
        }
        .btn-login {
            background: #1e3c72;
            border: none;
            padding: 10px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #16305e;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-header">
            <h3>ReturnPro</h3>
            <small>Tax Practice Management System</small>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" required autofocus placeholder="admin@returnpro.pk">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           required placeholder="••••••••">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>