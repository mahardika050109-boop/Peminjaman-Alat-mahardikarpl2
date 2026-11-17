<?php
/**
 * Kontroler dasar.
 *
 * Setiap kontroler mengisi:
 *   $access   => array(aksi => array(peran...)); kunci '*' = bawaan; 'public' = tanpa login.
 *                Aksi yang tidak terdaftar hanya boleh diakses admin.
 *   $postOnly => daftar aksi yang wajib POST + token CSRF.
 * Aksi dipanggil dari URL index.php?r=kontroler/aksi menjadi method aksiAction().
 */
abstract class Controller
{
    protected $access = array();
    protected $postOnly = array();

    public function run($action)
    {
        $method = $action . 'Action';
        if (!method_exists($this, $method)) {
            abort(404);
        }

        $roles = isset($this->access[$action])
            ? $this->access[$action]
            : (isset($this->access['*']) ? $this->access['*'] : array('admin'));

        if ($roles !== array('public')) {
            if (!Auth::check()) {
                redirect('auth/login');
            }
            if (!in_array(Auth::level(), $roles, true)) {
                abort(403, 'Peran Anda tidak memiliki izin untuk membuka halaman ini.');
            }
            // Wajib ganti password bawaan sebelum memakai fitur lain.
            if (!empty($_SESSION['must_change']) && !($this instanceof ProfilController) && !($this instanceof AuthController)) {
                flash('error', 'Ganti password bawaan terlebih dahulu sebelum melanjutkan.');
                redirect('profil/password');
            }
        }

        if (in_array($action, $this->postOnly, true)) {
            if (!is_post()) {
                abort(405, 'Aksi ini hanya dapat dilakukan lewat formulir.');
            }
            csrf_check();
        }

        $this->$method();
    }

    protected function render($view, array $data = array(), $layout = 'main')
    {
        View::render($view, $data, $layout);
    }

    protected function log($aktivitas)
    {
        LogModel::catat(Auth::id(), $aktivitas);
    }

    protected function page()
    {
        return max(1, (int)qs('page', 1));
    }

    protected function perPage()
    {
        return (int)config('per_page', 15);
    }
}
