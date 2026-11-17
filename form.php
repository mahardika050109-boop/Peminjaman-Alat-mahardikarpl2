<?php $isEdit = ($mode === 'edit'); ?>
<?php if ($errors): ?><div class="alert error">Periksa kembali isian yang ditandai.</div><?php endif; ?>
<?php if (!$isEdit && !$aktif): ?>
    <div class="alert info">Tidak ada peminjaman yang sedang berjalan. Setujui pengajuan terlebih dahulu.</div>
<?php endif; ?>
<form method="post" action="<?= e(url($isEdit ? 'pengembalian/update' : 'pengembalian/store')) ?>" class="card form-card">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?><input type="hidden" name="id_pengembalian" value="<?= (int)$row['id_pengembalian'] ?>"><?php endif; ?>

    <?php if ($isEdit): ?>
        <div class="field"><label>Peminjaman</label><div>#<?= (int)$row['id_peminjam'] ?> - <?= e(isset($row['nama']) && $row['nama'] !== null ? $row['nama'] : 'User dihapus') ?></div></div>
    <?php else: ?>
        <div class="field<?= isset($errors['id_peminjam']) ? ' has-err' : '' ?>">
            <label for="id_peminjam">Peminjaman yang dikembalikan</label>
            <select id="id_peminjam" name="id_peminjam" required>
                <option value="">Pilih peminjaman</option>
                <?php foreach ($aktif as $p): ?>
                    <option value="<?= (int)$p['id_peminjaman'] ?>"<?= selected($row['id_peminjam'], $p['id_peminjaman']) ?>>#<?= (int)$p['id_peminjaman'] ?> - <?= e($p['nama']) ?> - <?= e($p['alat_list']) ?> (batas <?= e(tgl($p['tanggal_kembali'])) ?>)</option>
                <?php endforeach; ?>
            </select>
            <?= field_error($errors, 'id_peminjam') ?>
        </div>
    <?php endif; ?>

    <div class="field<?= isset($errors['tanggal_pengembalian']) ? ' has-err' : '' ?>">
        <label for="tanggal_pengembalian">Tanggal pengembalian</label>
        <input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian" value="<?= e($row['tanggal_pengembalian']) ?>" required>
        <?= field_error($errors, 'tanggal_pengembalian') ?>
    </div>
    <div class="field<?= isset($errors['kondisi_pengembalian']) ? ' has-err' : '' ?>">
        <label for="kondisi_pengembalian">Kondisi alat saat kembali</label>
        <select id="kondisi_pengembalian" name="kondisi_pengembalian">
            <?php foreach (PengembalianModel::$kondisi as $kd): ?><option<?= selected($row['kondisi_pengembalian'], $kd) ?>><?= e($kd) ?></option><?php endforeach; ?>
        </select>
        <?= field_error($errors, 'kondisi_pengembalian') ?>
    </div>
    <?php if ($isEdit): ?>
        <div class="field<?= isset($errors['status']) ? ' has-err' : '' ?>">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach (PengembalianModel::$statuses as $st): ?><option<?= selected($row['status'], $st) ?>><?= e($st) ?></option><?php endforeach; ?>
            </select>
            <?= field_error($errors, 'status') ?>
        </div>
    <?php endif; ?>
    <div class="field<?= isset($errors['denda']) ? ' has-err' : '' ?>">
        <label for="denda">Denda (Rp)</label>
        <input type="number" id="denda" name="denda" min="0" step="1" value="<?= e($row['denda']) ?>"<?= $isEdit ? ' required' : '' ?>>
        <?php if (!$isEdit): ?><small class="hint">Kosongkan untuk dihitung otomatis (<?= (float)config('denda_per_hari', 0) > 0 ? e(rupiah(config('denda_per_hari'))) . ' per hari terlambat' : 'denda otomatis belum diaktifkan, hasilnya Rp 0' ?>).</small><?php endif; ?>
        <?= field_error($errors, 'denda') ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn"><?= $isEdit ? 'Simpan perubahan' : 'Simpan pengembalian' ?></button>
        <a class="btn ghost" href="<?= e(url('pengembalian/index')) ?>">Batal</a>
    </div>
</form>
