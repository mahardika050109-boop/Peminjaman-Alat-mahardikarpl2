<?php if (!$rows): ?>
    <p>Belum ada data alat.</p>
<?php else: ?>
    <table>
        <thead><tr><th>No</th><th>Nama alat</th><th>Kategori</th><th>Stok</th><th>Dipinjam</th><th>Tersedia</th><th>Kondisi</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($r['nama_alat']) ?></td>
                <td><?= e($r['nama_kategori']) ?></td>
                <td><?= (int)$r['stok'] ?></td>
                <td><?= (int)$r['dipinjam'] ?></td>
                <td><?= (int)$r['tersedia'] ?></td>
                <td><?= e($r['kondisi_alat']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
