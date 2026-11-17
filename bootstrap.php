<?php
if (version_compare(PHP_VERSION, '7.2.0', '<')) {
    exit('Aplikasi membutuhkan PHP 7.2 atau lebih baru.');
}

require APP_PATH . '/core/helpers.php';

date_default_timezone_set(config('timezone', 'Asia/Jakarta'));
error_reporting(E_ALL);
ini_set('display_errors', config('debug') ? '1' : '0');
ini_set('log_errors', '1');

spl_autoload_register(function ($class) {
    foreach (array('core', 'models', 'controllers') as $dir) {
        $file = APP_PATH . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

set_exception_handler(function ($e) {
    error_log('[PeminjamanAlat] ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    View::render('errors/500', array(
        'title'   => 'Terjadi kesalahan',
        'message' => config('debug') ? $e->getMessage() : '',
    ), 'blank');
    exit;
});

// Sesi
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params(array(
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ));
} else {
    session_set_cookie_params(0, '/', '', $https, true);
}
session_name('PEMINJAMSESS');
session_start();

$idle = (int)config('session_idle_minutes', 120) * 60;
if (isset($_SESSION['last_activity']) && $idle > 0 && (time() - $_SESSION['last_activity']) > $idle) {
    $_SESSION = array();
    session_regenerate_id(true);
    flash('error', 'Sesi berakhir karena tidak ada aktivitas. Silakan login kembali.');
}
$_SESSION['last_activity'] = time();
