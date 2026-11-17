<?php
class UserController extends Controller
{
    protected $access = array('*' => array('admin'));
    protected $postOnly = array('store', 'update', 'delete', 'hashall');

    private function validasi(array $in, $id = 0)
    {
        $err = array();
        if ($in['nama'] === '') {
            $err['nama'] = 'Nama wajib diisi.';
        } elseif (mb_strlen($in['nama']) > 100) {
            $err['nama'] = 'Nama maksimal 100 karakter.';
        }
        if (!preg_match('/^[A-Za-z0-9_.\-]{3,50}$/', $in['username'])) {
            $err['username'] = 'Username 3-50 karakter: huruf, angka, titik, garis bawah, atau strip.';
        } elseif (UserModel::usernameExists($in['username'], $id)) {
            $err['username'] = 'Username sudah dipakai akun lain.';
        }
        if (!in_array($in['level'], UserModel::$levels, true)) {
            $err['level'] = 'Pilih peran yang valid.';
        }
        if (($id === 0 || $in['password'] !== '') && strlen($in['password']) < 6) {
            $err['password'] = 'Password minimal 6 karakter.';
        }
        return $err;
    }

    private function input()
    {
        return array(
            'nama'     => post('nama'),
            'username' => post('username'),
            'level'    => post('level'),
            'password' => post_raw('password'),
        );
    }

    public function indexAction()
    {
        $f = array('q' => qs('q'), 'level' => qs('level'));
        list($rows, $pager) = UserModel::search($f, $this->page(), $this->perPage());
        $this->render('user/index', array(
            'title'    => 'Data User',
            'subtitle' => 'Kelola akun admin, petugas, dan peminjam',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
            'plain'    => UserModel::countPlain(),
        ));
    }

    public function createAction()
    {
        $this->render('user/form', array(
            'title'  => 'Tambah User',
            'mode'   => 'create',
            'row'    => array('nama' => '', 'username' => '', 'level' => 'peminjam'),
            'errors' => array(),
        ));
    }

    public function storeAction()
    {
        $in = $this->input();
        $err = $this->validasi($in, 0);
        if ($err) {
            $this->render('user/form', array('title' => 'Tambah User', 'mode' => 'create', 'row' => $in, 'errors' => $err));
            return;
        }
        UserModel::create($in['nama'], $in['username'], $in['password'], $in['level']);
        $this->log('Menambah data user');
        flash('success', 'User "' . $in['username'] . '" berhasil ditambahkan.');
        redirect('user/index');
    }

    public function editAction()
    {
        $row = UserModel::find((int)qs('id'));
        if (!$row) {
            abort(404);
        }
        $this->render('user/form', array('title' => 'Edit User', 'mode' => 'edit', 'row' => $row, 'errors' => array()));
    }

    public function updateAction()
    {
        $id = (int)post('id_user');
        $row = UserModel::find($id);
        if (!$row) {
            abort(404);
        }
        $in = $this->input();
        $err = $this->validasi($in, $id);

        if (!$err && $row['level'] === 'admin' && $in['level'] !== 'admin' && UserModel::countAdmins() <= 1) {
            $err['level'] = 'Minimal harus ada satu akun admin. Jadikan akun lain admin terlebih dahulu.';
        }
        if ($err) {
            $in['id_user'] = $id;
            $this->render('user/form', array('title' => 'Edit User', 'mode' => 'edit', 'row' => $in, 'errors' => $err));
            return;
        }

        UserModel::update($id, $in['nama'], $in['username'], $in['level'], $in['password']);
        if ($id === Auth::id()) {
            $_SESSION['user']['nama'] = $in['nama'];
            $_SESSION['user']['username'] = $in['username'];
            $_SESSION['user']['level'] = $in['level'];
        }
        $this->log('Mengubah data user');
        flash('success', 'Data user berhasil diperbarui.' . ($in['password'] !== '' ? ' Password sudah diganti.' : ''));
        redirect('user/index');
    }

    public function deleteAction()
    {
        $id = (int)qs('id');
        $row = UserModel::find($id);
        if (!$row) {
            flash('error', 'User tidak ditemukan.');
            redirect('user/index');
        }
        if ($id === Auth::id()) {
            flash('error', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
        } elseif ($row['level'] === 'admin' && UserModel::countAdmins() <= 1) {
            flash('error', 'Akun admin terakhir tidak boleh dihapus.');
        } elseif (UserModel::hasPeminjaman($id)) {
            flash('error', 'User "' . $row['username'] . '" masih punya riwayat peminjaman sehingga tidak dihapus. Ubah password atau perannya bila perlu.');
        } else {
            UserModel::delete($id);
            $this->log('Menghapus data user');
            flash('success', 'User "' . $row['username'] . '" dihapus.');
        }
        redirect('user/index');
    }

    public function hashallAction()
    {
        $n = UserModel::hashAllPlain();
        $this->log('Mengamankan password user (hash)');
        flash('success', $n > 0 ? $n . ' password berhasil diamankan. Password lama tetap berlaku.' : 'Semua password sudah dalam bentuk hash.');
        redirect('user/index');
    }
}
