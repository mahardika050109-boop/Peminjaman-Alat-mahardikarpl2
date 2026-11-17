<?php if ($errors): ?><div class="alert error">Periksa kembali isian yang ditandai.</div><?php endif; ?>
<?php if (!$alat): ?>
    <div class="alert info">Saat ini belum ada alat yang tersedia untuk dipinjam.</div>
<?php endif; ?>
<form method="post" action="<?= e(url('peminjaman/simpan')) ?>" class="card form-card">
    <?= csrf_field() ?>
    <div class="field<?= isset($errors['id_alat']) ? ' has-err' : '' ?>">
        <label for="id_alat">Alat</label>
        <select id="id_alat" name="id_alat" required>
            <option value="">Pilih alat</option>
            <?php foreach ($alat as $a): ?><option value="<?= (int)$a['id_alat'] ?>"<?= selected($row['id_alat'], $a['id_alat']) ?>><?= e($a['nama_alat']) ?> (tersedia <?= (int)$a['tersedia'] ?>)</option><?php endforeach; ?>
        </select>
        <?= field_error($errors, 'id_alat') ?>
    </div>
    <div class="field<?= isset($errors['jumlah']) ? ' has-err' : '' ?>">
        <label for="jumlah">Jumlah</label>
        <input type="number" id="jumlah" name="jumlah" min="1" step="1" value="<?= e($row['jumlah']) ?>" required>
        <?= field_error($errors, 'jumlah') ?>
    </div>
    <div class="field<?= isset($errors['tanggal_pinjam']) ? ' has-err' : '' ?>">
        <label for="tanggal_pinjam">Tanggal pinjam</label>
        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="<?= e($row['tanggal_pinjam']) ?>" min="<?= e(date('Y-m-d')) ?>" required>
        <?= field_error($errors, 'tanggal_pinjam') ?>
    </div>
    <div class="field<?= isset($errors['tanggal_kembali']) ? ' has-err' : '' ?>">
        <label for="tanggal_kembali">Tanggal kembali</label>
        <input type="date" id="tanggal_kembali" name="tanggal_kembali" value="<?= e($row['tanggal_kembali']) ?>" required>
        <?= field_error($errors, 'tanggal_kembali') ?>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Kirim pengajuan</button>
        <a class="btn ghost" href="<?= e(url('dashboard/index')) ?>">Batal</a>
    </div>
</form>
