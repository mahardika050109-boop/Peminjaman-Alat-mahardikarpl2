<?php
class Auth
{
    public static function check()
    {
        return !empty($_SESSION['user']['id']);
    }

    public static function user()
    {
        return self::check() ? $_SESSION['user'] : null;
    }

    public static function id()
    {
        return self::check() ? (int)$_SESSION['user']['id'] : 0;
    }

    public static function level()
    {
        return self::check() ? $_SESSION['user']['level'] : '';
    }

    public static function is($level)
    {
        return self::level() === $level;
    }

    public static function login(array $row)
    {
        session_regenerate_id(true);
        $_SESSION['user'] = array(
            'id'       => (int)$row['id_user'],
            'nama'     => $row['nama'],
            'username' => $row['username'],
            'level'    => $row['level'],
        );
        unset($_SESSION['login_fail'], $_SESSION['login_lock']);
    }

    public static function logout()
    {
        $_SESSION = array();
        if (session_id() !== '') {
            session_regenerate_id(true);
        }
    }

    /** Hash password baru (bcrypt). */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /** Cocokkan password; mendukung hash bcrypt dan data lama yang masih teks biasa. */
    public static function verify($password, $stored)
    {
        $stored = (string)$stored;
        $info = password_get_info($stored);
        if ($info['algo']) {
            return password_verify($password, $stored);
        }
        return $stored !== '' && hash_equals($stored, (string)$password);
    }

    public static function isHashed($stored)
    {
        $info = password_get_info((string)$stored);
        return (bool)$info['algo'];
    }
}
