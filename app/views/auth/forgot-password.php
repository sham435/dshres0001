<div class="login-container">
    <div class="card login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-key text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-2">Forgot Password</h3>
                <p class="text-muted">Enter your email to receive a reset link</p>
            </div>
            <form method="POST" action="/forgot-password">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Link</button>
                <div class="text-center">
                    <a href="/login" class="text-decoration-none">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>