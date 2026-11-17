<?php if ($plain > 0): ?>
    <div class="alert warn">
        <?= (int)$plain ?> akun masih menyimpan password sebagai teks biasa. Amankan sekarang; password pengguna tetap sama.
        <?= post_form('user/hashall', array(), 'Amankan semua password', 'btn small', 'Ubah semua password teks biasa menjadi hash?') ?>
    </div>
<?php endif; ?>

<div class="flow">
    <a class="t-warn" href="<?= e(url('peminjaman/index', array('status' => 'Menunggu'))) ?>"><b><?= (int)$pinjam['Menunggu'] ?></b><span>Menunggu persetujuan</span></a>
    <a class="t-info" href="<?= e(url('peminjaman/index', array('status' => 'Disetujui'))) ?>"><b><?= (int)$pinjam['Disetujui'] ?></b><span>Sedang dipinjam</span></a>
    <a class="t-bad" href="<?= e(url('peminjaman/index', array('status' => 'Terlambat'))) ?>"><b><?= (int)$pinjam['Terlambat'] ?></b><span>Terlambat kembali</span></a>
    <a class="t-ok" href="<?= e(url('peminjaman/index', array('status' => 'Dikembalikan'))) ?>"><b><?= (int)$pinjam['Dikembalikan'] ?></b><span>Sudah dikembalikan</span></a>
</div>

<div class="quick">
    <a class="btn" href="<?= e(url('peminjaman/create')) ?>">Tambah peminjaman</a>
    <a class="btn ghost" href="<?= e(url('alat/create')) ?>">Tambah alat</a>
    <a class="btn ghost" href="<?= e(url('user/create')) ?>">Tambah user</a>
    <a class="btn ghost" href="<?= e(url('laporan/index')) ?>">Laporan</a>
</div>

<div class="stats">
    <div class="stat"><b><?= (int)$alat['jenis'] ?></b><span>Jenis alat</span></div>
    <div class="stat"><b><?= (int)$alat['tersedia'] ?> / <?= (int)$alat['stok'] ?></b><span>Unit tersedia</span>
        <div class="meter" aria-hidden="true"><i style="width: <?= $alat['stok'] > 0 ? round($alat['tersedia'] / $alat['stok'] * 100) : 0 ?>%"></i></div></div>
    <div class="stat"><b><?= (int)$kategori ?></b><span>Kategori</span></div>
    <div class="stat"><b><?= (int)array_sum($users) ?></b><span>Akun (<?= (int)$users['admin'] ?> admin, <?= (int)$users['petugas'] ?> petugas, <?= (int)$users['peminjam'] ?> peminjam)</span></div>
    <div class="stat"><b><?= (int)$kembali['total'] ?></b><span>Pengembalian tercatat</span></div>
    <div class="stat"><b><?= e(rupiah($kembali['denda'])) ?></b><span>Total denda</span></div>
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
                        <span><strong><?= e($r['nama']) ?></strong><span class="sub"><?= e($r['alat_list']) ?> | <?= e(tgl($r['tanggal_pinjam'])) ?></span></span>
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
        <h2>Stok menipis</h2>
        <?php if (!$menipis): ?>
            <p class="hint">Semua alat masih cukup tersedia.</p>
        <?php else: ?>
            <ul class="list">
                <?php foreach (array_slice($menipis, 0, 6) as $a): ?>
                    <li>
                        <span><strong><?= e($a['nama_alat']) ?></strong><span class="sub"><?= e($a['nama_kategori']) ?></span></span>
                        <a href="<?= e(url('alat/edit', array('id' => $a['id_alat']))) ?>"><?= $a['tersedia'] === 0 ? badge('Habis', 'bad') : badge($a['tersedia'] . ' tersedia', 'warn') ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>

<section class="card">
    <h2>Aktivitas terbaru</h2>
    <ul class="list">
        <?php foreach ($logs as $l): ?>
            <li><span><?= e($l['aktivitas']) ?><span class="sub"><?= e($l['nama'] ? $l['nama'] : 'User dihapus') ?></span></span><small><?= e(tgl_jam($l['tanggal'])) ?></small></li>
        <?php endforeach; ?>
    </ul>
    <a href="<?= e(url('log/index')) ?>">Lihat semua log</a>
</section>
