<?php if (!empty($_SESSION['must_change'])): ?>
    <div class="alert warn">Anda masuk memakai password bawaan. Buat password baru sebelum menggunakan fitur lain.</div>
<?php endif; ?>
<form method="post" action="<?= e(url('profil/gantipassword')) ?>" class="card form-card">
    <?= csrf_field() ?>
    <div class="field<?= isset($errors['password_lama']) ? ' has-err' : '' ?>">
        <label for="password_lama">Password saat ini</label>
        <input type="password" id="password_lama" name="password_lama" autocomplete="current-password" required>
        <?= field_error($errors, 'password_lama') ?>
    </div>
    <div class="field<?= isset($errors['password_baru']) ? ' has-err' : '' ?>">
        <label for="password_baru">Password baru</label>
        <input type="password" id="password_baru" name="password_baru" autocomplete="new-password" required>
        <small class="hint">Minimal 8 karakter.</small>
        <?= field_error($errors, 'password_baru') ?>
    </div>
    <div class="field<?= isset($errors['password_ulang']) ? ' has-err' : '' ?>">
        <label for="password_ulang">Ulangi password baru</label>
        <input type="password" id="password_ulang" name="password_ulang" autocomplete="new-password" required>
        <?= field_error($errors, 'password_ulang') ?>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn">Simpan password</button>
        <?php if (empty($_SESSION['must_change'])): ?><a class="btn ghost" href="<?= e(url('profil/index')) ?>">Batal</a><?php endif; ?>
    </div>
</form>
