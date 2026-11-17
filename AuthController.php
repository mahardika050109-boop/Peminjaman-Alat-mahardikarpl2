<?php
class AuthController extends Controller
{
    protected $access = array(
        'login'  => array('public'),
        'proses' => array('public'),
        'logout' => array('admin', 'petugas', 'peminjam'),
    );
    protected $postOnly = array('proses', 'logout');

    public function loginAction()
    {
        if (Auth::check()) {
            redirect('dashboard/index');
        }
        $this->render('auth/login', array('title' => 'Masuk'), 'blank');
    }

    public function prosesAction()
    {
        if (!empty($_SESSION['login_lock']) && $_SESSION['login_lock'] > time()) {
            $menit = (int)ceil(($_SESSION['login_lock'] - time()) / 60);
            flash('error', 'Terlalu banyak percobaan gagal. Coba lagi dalam ' . $menit . ' menit.');
            redirect('auth/login');
        }

        $username = post('username');
        $password = post_raw('password');
        if ($username === '' || $password === '') {
            flash('error', 'Username dan password wajib diisi.');
            redirect('auth/login');
        }

        $user = UserModel::findByUsername($username);
        if (!$user || !Auth::verify($password, $user['password'])) {
            $_SESSION['login_fail'] = (isset($_SESSION['login_fail']) ? $_SESSION['login_fail'] : 0) + 1;
            if ($_SESSION['login_fail'] >= 5) {
                $_SESSION['login_lock'] = time() + 300;
                $_SESSION['login_fail'] = 0;
            }
            flash('error', 'Username atau password salah.');
            redirect('auth/login');
        }

        if (!in_array($user['level'], UserModel::$levels, true)) {
            flash('error', 'Akun ini tidak memiliki peran yang valid. Hubungi administrator.');
            redirect('auth/login');
        }

        // Akun lama menyimpan password sebagai teks biasa: ubah jadi hash saat login berhasil.
        if (!Auth::isHashed($user['password'])) {
            UserModel::upgradeHash($user['id_user'], $password);
        }

        Auth::login($user);
        if (hash_equals((string)config('default_admin_password'), $password)) {
            $_SESSION['must_change'] = true;
        } else {
            unset($_SESSION['must_change']);
        }
        $this->log('Login ke sistem');
        redirect('dashboard/index');
    }

    public function logoutAction()
    {
        $this->log('Logout dari sistem');
        Auth::logout();
        flash('success', 'Anda sudah keluar.');
        redirect('auth/login');
    }
}
