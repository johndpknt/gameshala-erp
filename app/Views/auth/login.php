<div class="auth-card">
    <a href="<?= base_url('/') ?>">
        <img src="<?= asset_url('assets/images/gameshaala_howrah.png') ?>" alt="Gameshaala Howrah" class="auth-logo">
    </a>
    <h1 class="auth-title"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in</h1>

    <?php if (session('message')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= esc(session('message')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (! empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <?= form_open('auth/attemptLogin', ['class' => 'needs-validation', 'novalidate' => '']) ?>
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= esc(old('email')) ?>"
                   placeholder="you@example.com" required autocomplete="email" autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                   required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in</button>
    <?= form_close() ?>

    <p class="auth-footer mb-0 mt-3">
        <a href="https://gameshaala.com" rel="noopener noreferrer"><i class="bi bi-house me-1"></i>Back to home</a>
    </p>
    <p class="text-center text-secondary small mb-0 mt-3">&copy; <?= date('Y') ?> Gameshala ERP. Developed by <a href="https://www.linkedin.com/in/johndpknt" target="_blank" rel="noopener noreferrer" class="text-secondary text-decoration-none">John</a> &amp; <a href="https://chandranathpatra.com" target="_blank" rel="noopener noreferrer" class="text-secondary text-decoration-none">Patra</a>.</p>
</div>
