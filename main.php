<?php
$user = Auth::user();
$items = Menu::items($user['level']);
$tabs = Menu::tabs($user['level']);
$appName = config('app_name', 'Peminjaman Alat');
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> - <?= e($appName) ?></title>
<link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body class="has-shell">

<header class="topbar">
    <span class="brand">🛠 <?= e($appName) ?></span>
</header>

<nav class="side" id="side" aria-label="Menu utama">
    <div class="side-brand">🛠 <?= e($appName) ?></div>
    <ul>
        <?php foreach ($items as $it): ?>
            <li>
                <a href="<?= e(url($it[2])) ?>" class="<?= Menu::isActive($it[2], $items) ? 'on' : '' ?>">
                    <span aria-hidden="true"><?= $it[1] ?></span> <?= e($it[0]) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="side-user">
        <strong><?= e($user['nama']) ?></strong>
        <span><?= e(level_label($user['level'])) ?></span>
        <form method="post" action="<?= e(url('auth/logout')) ?>">
            <?= csrf_field() ?>
            <button type="submit" class="btn ghost small">Keluar</button>
        </form>
    </div>
</nav>

<main class="content">
    <div class="page-head">
        <h1><?= e($title) ?></h1>
        <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
    </div>

    <?php foreach (pull_flash() as $f): ?>
        <div class="alert <?= e($f['type']) ?>" role="<?= $f['type'] === 'error' ? 'alert' : 'status' ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>

    <?= $content ?>
</main>

<nav class="tabbar" aria-label="Pintasan">
    <?php foreach ($tabs as $it): ?>
        <a href="<?= e(url($it[2])) ?>" class="<?= Menu::isActive($it[2], $items) ? 'on' : '' ?>">
            <span aria-hidden="true"><?= $it[1] ?></span><?= e($it[0]) ?>
        </a>
    <?php endforeach; ?>
    <button type="button" data-menu-toggle><span aria-hidden="true">☰</span>Menu</button>
</nav>

<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
