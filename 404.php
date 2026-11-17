<div class="login-card">
    <h1><?= e($title) ?></h1>
    <p class="lead"><?= e($message !== '' ? $message : 'Halaman atau data yang Anda cari tidak ada.') ?></p>
    <a class="btn" href="<?= e(url('')) ?>">Ke beranda</a>
</div>
