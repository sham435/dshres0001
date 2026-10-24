<div class="login-container">
    <div class="card login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-2">Reset Password</h3>
            </div>
            <form method="POST" action="/reset-password">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '', ENT_QUOTES) ?>">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password" required minlength="8">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
            </form>
        </div>
    </div>
</div>