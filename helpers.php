<?php
/**
 * Fungsi bantu global. Dimuat paling awal oleh bootstrap.php.
 */

function config($key, $default = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require BASE_PATH . '/config/config.php';
    }
    return array_key_exists($key, $cfg) ? $cfg[$key] : $default;
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function base_url()
{
    $dir = str_replace('\\', '/', dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php'));
    return rtrim($dir, '/');
}

function url($route = '', array $params = array())
{
    $parts = array();
    if ($route !== '') {
        // Garis miring pada rute dibiarkan apa adanya agar URL mudah dibaca.
        $parts[] = 'r=' . str_replace('%2F', '/', rawurlencode($route));
    }
    if ($params) {
        $parts[] = http_build_query($params);
    }
    return base_url() . '/index.php' . ($parts ? '?' . implode('&', $parts) : '');
}

function asset($path)
{
    $file = BASE_PATH . '/assets/' . $path;
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return base_url() . '/assets/' . $path . $v;
}

function redirect($route, array $params = array())
{
    header('Location: ' . url($route, $params));
    exit;
}

function flash($type, $message)
{
    $_SESSION['flash'][] = array('type' => $type, 'message' => $message);
}

function pull_flash()
{
    $items = isset($_SESSION['flash']) ? $_SESSION['flash'] : array();
    unset($_SESSION['flash']);
    return $items;
}

function is_post()
{
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}

function post($key, $default = '')
{
    return (isset($_POST[$key]) && is_string($_POST[$key])) ? trim($_POST[$key]) : $default;
}

function qs($key, $default = '')
{
    return (isset($_GET[$key]) && is_string($_GET[$key])) ? trim($_GET[$key]) : $default;
}

function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check()
{
    $sent = isset($_POST['_csrf']) && is_string($_POST['_csrf']) ? $_POST['_csrf'] : '';
    if ($sent === '' || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $sent)) {
        abort(419, 'Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.');
    }
}

function abort($code, $message = '')
{
    http_response_code($code);
    $view = in_array($code, array(403, 404), true) ? 'errors/' . $code : 'errors/generic';
    View::render($view, array(
        'title'   => $code === 404 ? 'Halaman tidak ditemukan' : ($code === 403 ? 'Akses ditolak' : 'Permintaan ditolak'),
        'message' => $message,
        'code'    => $code,
    ), Auth::check() ? 'main' : 'blank');
    exit;
}

function valid_date($s)
{
    if (!is_string($s) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $s, $m)) {
        return false;
    }
    return checkdate((int)$m[2], (int)$m[3], (int)$m[1]);
}

function tgl($date)
{
    if (!$date || $date === '0000-00-00') {
        return '-';
    }
    $t = strtotime($date);
    return $t ? date('d/m/Y', $t) : e($date);
}

function tgl_jam($datetime)
{
    if (!$datetime) {
        return '-';
    }
    $t = strtotime($datetime);
    return $t ? date('d/m/Y H:i', $t) : $datetime;
}

function rupiah($n)
{
    return 'Rp ' . number_format((float)$n, 0, ',', '.');
}

function like_escape($s)
{
    return addcslashes($s, '%_\\');
}

function level_label($level)
{
    $map = array('admin' => 'Administrator', 'petugas' => 'Petugas', 'peminjam' => 'Peminjam');
    return isset($map[$level]) ? $map[$level] : $level;
}

function badge($text, $kind = null)
{
    $map = array(
        'Menunggu' => 'warn', 'Disetujui' => 'info', 'Ditolak' => 'bad', 'Dikembalikan' => 'ok',
        'Terlambat' => 'bad', 'Aktif' => 'ok', 'Nonaktif' => 'muted', 'Baik' => 'ok',
        'Rusak Ringan' => 'warn', 'Rusak Berat' => 'bad',
        'admin' => 'info', 'petugas' => 'warn', 'peminjam' => 'muted',
    );
    if ($kind === null) {
        $kind = isset($map[$text]) ? $map[$text] : 'muted';
    }
    $label = in_array($text, array('admin', 'petugas', 'peminjam'), true) ? level_label($text) : $text;
    return '<span class="badge ' . e($kind) . '">' . e($label) . '</span>';
}

/** Peminjaman berstatus Disetujui yang sudah melewati tanggal kembali. */
function is_late($row)
{
    return isset($row['status'], $row['tanggal_kembali'])
        && $row['status'] === 'Disetujui'
        && $row['tanggal_kembali']
        && $row['tanggal_kembali'] < date('Y-m-d');
}

/** Tombol/formulir POST dengan token CSRF (hapus, setujui, dll). */
function post_form($route, array $params, $label, $class = 'btn small', $confirm = '')
{
    $html = '<form method="post" action="' . e(url($route, $params)) . '" class="inline"';
    if ($confirm !== '') {
        $html .= ' data-confirm="' . e($confirm) . '"';
    }
    $html .= '>' . csrf_field() . '<button type="submit" class="' . e($class) . '">' . $label . '</button></form>';
    return $html;
}

function field_error($errors, $field)
{
    return isset($errors[$field]) ? '<small class="err">' . e($errors[$field]) . '</small>' : '';
}

function selected($a, $b)
{
    return (string)$a === (string)$b ? ' selected' : '';
}

function current_user()
{
    return Auth::user();
}

/** Parameter tanggal dari query string; kosong bila tidak valid. */
function qs_date($key)
{
    $v = qs($key);
    return valid_date($v) ? $v : '';
}

/** Nilai password mentah dari POST (tidak di-trim). */
function post_raw($key)
{
    return (isset($_POST[$key]) && is_string($_POST[$key])) ? $_POST[$key] : '';
}

// Cadangan bila ekstensi mbstring tidak aktif di hosting.
if (!function_exists('mb_strlen')) {
    function mb_strlen($s, $enc = null)
    {
        return preg_match_all('/./su', (string)$s, $m) !== false ? count($m[0]) : strlen((string)$s);
    }
}
if (!function_exists('mb_substr')) {
    function mb_substr($s, $start, $length = null, $enc = null)
    {
        preg_match_all('/./su', (string)$s, $m);
        return implode('', array_slice($m[0], $start, $length));
    }
}
