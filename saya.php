<form method="get" action="<?= e(url()) ?>" class="filters">
    <input type="hidden" name="r" value="peminjaman/saya">
    <div class="field"><label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            <?php foreach (PeminjamanModel::$statuses as $st): ?><option<?= selected($f['status'], $st) ?>><?= e($st) ?></option><?php endforeach; ?>
        </select></div>
    <button class="btn ghost" type="submit">Terapkan</button>
    <a class="btn" href="<?= e(url('peminjaman/ajukan')) ?>">Ajukan peminjaman</a>
</form>

<div class="table-wrap">
<?php if (!$rows): ?>
    <p class="empty">Belum ada peminjaman. <a href="<?= e(url('peminjaman/ajukan')) ?>">Ajukan sekarang</a></p>
<?php else: ?>
    <table class="rtable">
        <thead><tr><th>ID</th><th>Alat</th><th>Pinjam</th><th>Batas kembali</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td data-label="ID">#<?= (int)$r['id_peminjaman'] ?></td>
                <td data-label="Alat"><?= e($r['alat_list'] !== null ? $r['alat_list'] : '-') ?></td>
                <td data-label="Pinjam"><?= e(tgl($r['tanggal_pinjam'])) ?></td>
                <td data-label="Batas kembali"><?= e(tgl($r['tanggal_kembali'])) ?></td>
                <td data-label="Status"><?= badge($r['status']) ?> <?= is_late($r) ? badge('Terlambat') : '' ?></td>
                <td class="act"><?php if ($r['status'] === 'Disetujui'): ?>
                    <?= post_form('peminjaman/kembalikan', array('id' => $r['id_peminjaman']), 'Kembalikan', 'btn small', 'Kembalikan alat ini sekarang?') ?>
                <?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>
<?php require APP_PATH . '/views/partials/pager.php'; ?>
