<div class="login-card">
    <h1><?= e($title) ?></h1>
    <p class="lead"><?= e($message !== '' ? $message : 'Anda tidak memiliki izin untuk membuka halaman ini.') ?></p>
    <a class="btn" href="<?= e(url('dashboard/index')) ?>">Ke dashboard</a>
</div>
