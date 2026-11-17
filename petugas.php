<div class="flow">
    <a class="t-warn" href="<?= e(url('peminjaman/index', array('status' => 'Menunggu'))) ?>"><b><?= (int)$pinjam['Menunggu'] ?></b><span>Menunggu persetujuan</span></a>
    <a class="t-info" href="<?= e(url('peminjaman/index', array('status' => 'Disetujui'))) ?>"><b><?= (int)$pinjam['Disetujui'] ?></b><span>Sedang dipinjam</span></a>
    <a class="t-bad" href="<?= e(url('peminjaman/index', array('status' => 'Terlambat'))) ?>"><b><?= (int)$pinjam['Terlambat'] ?></b><span>Terlambat kembali</span></a>
    <a class="t-ok" href="<?= e(url('pengembalian/index')) ?>"><b><?= (int)$kembali['total'] ?></b><span>Pengembalian tercatat</span></a>
</div>

<div class="grid-2">
    <section class="card">
        <h2>Menunggu persetujuan</h2>
        <?php if (!$menunggu): ?>
            <p class="hint">Tidak ada pengajuan baru.</p>
        <?php else: ?>
            <ul class="list">
                <?php foreach ($menunggu as $r): ?>
                    <li>
                        <span><strong><?= e($r['nama']) ?></strong><span class="sub"><?= e($r['alat_list']) ?> | <?= e(tgl($r['tanggal_pinjam'])) ?> s/d <?= e(tgl($r['tanggal_kembali'])) ?></span></span>
                        <span class="actions">
                            <?= post_form('peminjaman/setujui', array('id' => $r['id_peminjaman']), 'Setujui', 'btn ok small') ?>
                            <?= post_form('peminjaman/tolak', array('id' => $r['id_peminjaman']), 'Tolak', 'btn danger small', 'Tolak pengajuan ini?') ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Terlambat dikembalikan</h2>
        <?php if (!$telat): ?>
            <p class="hint">Tidak ada peminjaman yang terlambat.</p>
        <?php else: ?>
            <ul class="list">
                <?php foreach ($telat as $r): ?>
                    <li>
                        <span><strong><?= e($r['nama']) ?></strong><span class="sub"><?= e($r['alat_list']) ?> | batas <?= e(tgl($r['tanggal_kembali'])) ?></span></span>
                        <a class="btn small" href="<?= e(url('pengembalian/create', array('id' => $r['id_peminjaman']))) ?>">Catat pengembalian</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>
