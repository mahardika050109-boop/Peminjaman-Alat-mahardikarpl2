<div class="login-card">
    <h1>Terjadi kesalahan</h1>
    <p class="lead">Halaman tidak dapat ditampilkan saat ini. Periksa pengaturan di config/database.php atau coba lagi nanti.</p>
    <?php if (!empty($message)): ?><p class="alert error"><?= e($message) ?></p><?php endif; ?>
    <a class="btn" href="<?= e(url('')) ?>">Coba lagi</a>
</div>
