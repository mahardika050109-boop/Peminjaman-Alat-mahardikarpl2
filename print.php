<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> - <?= e(config('app_name', 'Peminjaman Alat')) ?></title>
<link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body class="print-page">
<div class="noprint print-bar">
    <button type="button" class="btn" onclick="window.print()">Cetak</button>
    <a class="btn ghost" href="<?= e(url('laporan/index')) ?>">Kembali ke laporan</a>
</div>
<main class="print-doc">
    <h1><?= e($title) ?></h1>
    <p class="print-meta"><?= e(config('app_name', 'Peminjaman Alat')) ?> | Periode: <?= e($periode) ?> | Dicetak oleh <?= e(Auth::user()['nama']) ?> pada <?= e(date('d/m/Y H:i')) ?></p>
    <?= $content ?>
</main>
</body>
</html>
