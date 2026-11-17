<form method="get" action="<?= e(url()) ?>" class="filters">
    <input type="hidden" name="r" value="alat/katalog">
    <div class="field grow"><label for="q">Cari nama alat</label><input type="search" id="q" name="q" value="<?= e($f['q']) ?>"></div>
    <div class="field"><label for="id_kategori">Kategori</label>
        <select id="id_kategori" name="id_kategori">
            <option value="">Semua</option>
            <?php foreach ($kategori as $k): ?><option value="<?= (int)$k['id_kategori'] ?>"<?= selected($f['id_kategori'], $k['id_kategori']) ?>><?= e($k['nama_kategori']) ?></option><?php endforeach; ?>
        </select></div>
    <button class="btn ghost" type="submit">Terapkan</button>
</form>

<div class="table-wrap">
<?php if (!$rows): ?>
    <p class="empty">Tidak ada alat yang cocok.</p>
<?php else: ?>
    <table class="rtable">
        <thead><tr><th>Nama alat</th><th>Kategori</th><th class="num">Tersedia</th><th>Kondisi</th><?php if (Auth::is('peminjam')): ?><th></th><?php endif; ?></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): $bisa = $r['tersedia'] > 0 && $r['kondisi_alat'] !== 'Rusak Berat'; ?>
            <tr>
                <td data-label="Nama alat"><?= e($r['nama_alat']) ?></td>
                <td data-label="Kategori"><?= e($r['nama_kategori']) ?></td>
                <td data-label="Tersedia" class="num"><?= (int)$r['tersedia'] ?> dari <?= (int)$r['stok'] ?></td>
                <td data-label="Kondisi"><?= badge($r['kondisi_alat']) ?></td>
                <?php if (Auth::is('peminjam')): ?>
                    <td class="act"><?php if ($bisa): ?><a class="btn small" href="<?= e(url('peminjaman/ajukan', array('id_alat' => $r['id_alat']))) ?>">Pinjam</a><?php else: ?><small>Tidak tersedia</small><?php endif; ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>
