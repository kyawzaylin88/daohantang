<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Login</h2>
                <form method="POST" action="<?= app_url('login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3 mb-0">
                    Don't have an account? <a href="<?= app_url('register') ?>">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
