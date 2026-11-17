<?php
$pgParams = $_GET;
unset($pgParams['r'], $pgParams['page']);
$pgRoute = qs('r');
$pgUrl = function ($p) use ($pgRoute, $pgParams) {
    return url($pgRoute, array_merge($pgParams, array('page' => $p)));
};
?>
<div class="pager">
    <span><?= $pager->total === 0 ? 'Tidak ada data' : 'Menampilkan ' . $pager->from() . '-' . $pager->to() . ' dari ' . $pager->total ?></span>
    <?php if ($pager->pages > 1): ?>
    <nav aria-label="Halaman">
        <?php if ($pager->page > 1): ?><a href="<?= e($pgUrl($pager->page - 1)) ?>">Sebelumnya</a><?php endif; ?>
        <?php for ($p = max(1, $pager->page - 2); $p <= min($pager->pages, $pager->page + 2); $p++): ?>
            <?php if ($p === $pager->page): ?><span class="cur" aria-current="page"><?= $p ?></span>
            <?php else: ?><a href="<?= e($pgUrl($p)) ?>"><?= $p ?></a><?php endif; ?>
        <?php endfor; ?>
        <?php if ($pager->page < $pager->pages): ?><a href="<?= e($pgUrl($pager->page + 1)) ?>">Berikutnya</a><?php endif; ?>
    </nav>
    <?php endif; ?>
</div>
