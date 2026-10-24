<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Nachchaduwa Muslim Maha Vidyalaya</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/css/main.css">
<style>
:root { --color-primary: #3d1a78; --color-accent: #d4a017; --color-dark: #250f4a; --color-background: #f9f5ff; }
body { font-family: 'Noto Sans', sans-serif; background: var(--color-background); margin: 0; }
.login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.login-card { max-width: 420px; width: 100%; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
</style>
</head>
<body>
<div class="login-container">
    <div class="card login-card" style="background:white;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill" style="font-size: 3rem; color: var(--color-primary);"></i>
                <h3 class="mt-2" style="color: var(--color-primary);">Nachchaduwa Muslim Maha Vidyalaya</h3>
                <p class="text-muted">Login Page</p>
            </div>
            <div class="d-flex justify-content-center gap-2 mb-3">
                <a href="/login-si.php" class="px-2 py-0.5 rounded text-xs border border-gray-300 hover:bg-gray-100">SI</a>
                <a href="/login-en.php" class="px-2 py-0.5 rounded text-xs bg-[var(--color-accent)] text-[var(--color-primary)] font-bold">EN</a>
                <a href="/login-ta.php" class="px-2 py-0.5 rounded text-xs border border-gray-300 hover:bg-gray-100">TA</a>
            </div>
            <form method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="username" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
                <div class="text-center">
                    <a href="/forgot-password" class="text-decoration-none">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>