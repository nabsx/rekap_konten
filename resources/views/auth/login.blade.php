<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ContentHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh; margin: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            width: 100%; max-width: 420px;
            background: #fff; border-radius: 20px;
            padding: 2.5rem 2rem; box-shadow: 0 25px 60px rgba(0,0,0,.4);
        }
        .brand-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff; margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 10px; border: 1.5px solid #e2e8f0;
            padding: .65rem 1rem; font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0;
            background: #f8fafc; color: #64748b;
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; border-left: 0; }
        .btn-login {
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border: none; border-radius: 10px; color: #fff;
            font-weight: 700; padding: .7rem; font-size: .95rem;
            transition: opacity .2s, transform .15s;
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); color: #fff; }
        .demo-box {
            background: #f8fafc; border-radius: 10px;
            padding: .85rem 1rem; font-size: .78rem; color: #475569;
            border: 1px dashed #cbd5e1; margin-top: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-icon"><i class="bi bi-grid-3x3-gap-fill"></i></div>
        <h4 class="fw-800 mb-1" style="color:#0f172a;">Rekap Konten</h4>
        <p class="text-muted mb-4" style="font-size:.875rem;">Sistem Rekap Postingan Konten</p>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 border-0 py-2 px-3 mb-3" style="font-size:.85rem;">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success rounded-3 border-0 py-2 px-3 mb-3" style="font-size:.85rem;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-600" style="font-size:.85rem;">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@example.com" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-600" style="font-size:.85rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.85rem;">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
