<?php if (!$rows): ?>
    <p>Tidak ada data untuk periode ini.</p>
<?php else: ?>
    <table>
        <thead><tr><th>No</th><th>ID</th><th>Peminjam</th><th>Alat</th><th>Tgl pinjam</th><th>Tgl kembali</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td>#<?= (int)$r['id_peminjaman'] ?></td>
                <td><?= e($r['nama']) ?></td>
                <td><?= e($r['alat_list']) ?></td>
                <td><?= e(tgl($r['tanggal_pinjam'])) ?></td>
                <td><?= e(tgl($r['tanggal_kembali'])) ?></td>
                <td><?= e($r['status']) ?><?= is_late($r) ? ' (terlambat)' : '' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="total">Jumlah: <?= count($rows) ?> peminjaman</p>
<?php endif; ?>
