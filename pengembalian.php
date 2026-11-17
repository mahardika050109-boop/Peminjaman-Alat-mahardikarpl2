<?php if (!$rows): ?>
    <p>Tidak ada data untuk periode ini.</p>
<?php else: ?>
    <table>
        <thead><tr><th>No</th><th>Peminjaman</th><th>Peminjam</th><th>Tgl pengembalian</th><th>Kondisi</th><th>Status</th><th>Denda</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td>#<?= (int)$r['id_peminjam'] ?></td>
                <td><?= e($r['nama']) ?></td>
                <td><?= e(tgl($r['tanggal_pengembalian'])) ?></td>
                <td><?= e($r['kondisi_pengembalian']) ?></td>
                <td><?= e($r['status']) ?></td>
                <td><?= e(rupiah($r['denda'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="total">Total denda: <?= e(rupiah($total)) ?> (<?= count($rows) ?> pengembalian)</p>
<?php endif; ?>
