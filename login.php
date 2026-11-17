<div class="login-card">
    <h1>🛠 <?= e(config('app_name', 'Peminjaman Alat')) ?></h1>
    <p class="lead">Masuk dengan akun Anda untuk melanjutkan.</p>
    <form method="post" action="<?= e(url('auth/proses')) ?>">
        <?= csrf_field() ?>
        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" autocapitalize="none" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn">Masuk</button>
    </form>
</div>
