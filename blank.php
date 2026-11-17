<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> - <?= e(config('app_name', 'Peminjaman Alat')) ?></title>
<link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body class="blank">
<main class="blank-wrap">
    <?php foreach (pull_flash() as $f): ?>
        <div class="alert <?= e($f['type']) ?>" role="<?= $f['type'] === 'error' ? 'alert' : 'status' ?>"><?= e($f['message']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
