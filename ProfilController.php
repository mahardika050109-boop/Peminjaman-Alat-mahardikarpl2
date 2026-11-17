<?php
class ProfilController extends Controller
{
    protected $access = array('*' => array('admin', 'petugas', 'peminjam'));
    protected $postOnly = array('simpan', 'gantipassword');

    public function indexAction()
    {
        $row = UserModel::find(Auth::id());
        $this->render('profil/index', array(
            'title' => 'Profil Saya', 'subtitle' => 'Data akun Anda', 'row' => $row, 'errors' => array(),
        ));
    }

    public function simpanAction()
    {
        $nama = post('nama');
        $row = UserModel::find(Auth::id());
        if ($nama === '' || mb_strlen($nama) > 100) {
            $this->render('profil/index', array(
                'title' => 'Profil Saya', 'subtitle' => 'Data akun Anda', 'row' => $row,
                'errors' => array('nama' => 'Nama wajib diisi (maksimal 100 karakter).'),
            ));
            return;
        }
        UserModel::setNama(Auth::id(), $nama);
        $_SESSION['user']['nama'] = $nama;
        $this->log('Mengubah profil');
        flash('success', 'Profil diperbarui.');
        redirect('profil/index');
    }

    public function passwordAction()
    {
        $this->render('profil/password', array(
            'title' => 'Ganti Password', 'subtitle' => 'Gunakan password yang sulit ditebak', 'errors' => array(),
        ));
    }

    public function gantipasswordAction()
    {
        $lama = post_raw('password_lama');
        $baru = post_raw('password_baru');
        $ulang = post_raw('password_ulang');
        $row = UserModel::find(Auth::id());
        $err = array();

        if (!$row || !Auth::verify($lama, $row['password'])) {
            $err['password_lama'] = 'Password saat ini salah.';
        }
        if (strlen($baru) < 8) {
            $err['password_baru'] = 'Password baru minimal 8 karakter.';
        } elseif (hash_equals((string)config('default_admin_password'), $baru)) {
            $err['password_baru'] = 'Password baru tidak boleh sama dengan password bawaan.';
        } elseif ($baru === $lama) {
            $err['password_baru'] = 'Password baru harus berbeda dari password lama.';
        }
        if ($baru !== $ulang) {
            $err['password_ulang'] = 'Konfirmasi password tidak cocok.';
        }
        if ($err) {
            $this->render('profil/password', array(
                'title' => 'Ganti Password', 'subtitle' => 'Gunakan password yang sulit ditebak', 'errors' => $err,
            ));
            return;
        }
        UserModel::setPassword(Auth::id(), $baru);
        unset($_SESSION['must_change']);
        $this->log('Mengganti password');
        flash('success', 'Password berhasil diganti.');
        redirect('dashboard/index');
    }
}
