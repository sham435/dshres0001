<div class="login-container">
    <div class="card login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-2"><?= config('app_name') ?></h3>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <form method="POST" action="/login">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="username" class="form-label"><?= lang('username') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><?= lang('password') ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember"><?= lang('remember_me') ?></label>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> <?= lang('sign_in') ?>
                </button>

                <div class="text-center">
                    <a href="/forgot-password" class="text-decoration-none"><?= lang('forgot_password') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>