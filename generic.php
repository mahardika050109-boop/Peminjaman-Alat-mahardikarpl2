<div class="login-card">
    <h1><?= e($title) ?></h1>
    <p class="lead"><?= e($message !== '' ? $message : 'Permintaan tidak dapat diproses.') ?></p>
    <a class="btn" href="<?= e(url('')) ?>">Kembali</a>
</div>
