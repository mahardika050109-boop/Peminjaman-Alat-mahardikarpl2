<div class="stats">
    <div class="stat"><b><?= (int)$stat['Menunggu'] ?></b><span>Menunggu persetujuan</span></div>
    <div class="stat"><b><?= (int)$stat['Disetujui'] ?></b><span>Sedang dipinjam</span></div>
    <div class="stat"><b><?= (int)$stat['Dikembalikan'] ?></b><span>Sudah dikembalikan</span></div>
    <div class="stat"><b><?= (int)$tersedia ?></b><span>Jenis alat bisa dipinjam</span></div>
</div>

<div class="quick">
    <a class="btn" href="<?= e(url('peminjaman/ajukan')) ?>">Ajukan peminjaman</a>
    <a class="btn ghost" href="<?= e(url('alat/katalog')) ?>">Lihat daftar alat</a>
    <a class="btn ghost" href="<?= e(url('peminjaman/saya')) ?>">Riwayat saya</a>
</div>

<section class="card">
    <h2>Alat yang sedang Anda pinjam</h2>
    <?php if (!$aktif): ?>
        <p class="hint">Belum ada alat yang sedang dipinjam.</p>
    <?php else: ?>
        <ul class="list">
            <?php foreach ($aktif as $r): ?>
                <li>
                    <span><strong><?= e($r['alat_list']) ?></strong>
                        <span class="sub">Kembali paling lambat <?= e(tgl($r['tanggal_kembali'])) ?> <?= is_late($r) ? badge('Terlambat') : '' ?></span></span>
                    <?= post_form('peminjaman/kembalikan', array('id' => $r['id_peminjaman']), 'Kembalikan', 'btn small', 'Kembalikan alat ini sekarang?') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
