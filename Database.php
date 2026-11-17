<?php
/**
 * Pembungkus mysqli dengan prepared statement.
 * Seluruh query aplikasi memakai parameter terikat, bukan penyambungan string.
 */
class Database
{
    private static $conn = null;

    public static function conn()
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $file = BASE_PATH . '/config/database.php';
        if (!is_file($file)) {
            throw new RuntimeException('config/database.php tidak ditemukan.');
        }
        $db = require $file;
        $local = BASE_PATH . '/config/database.local.php';
        if (is_file($local)) {
            $db = array_merge($db, require $local);
        }

        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = mysqli_init();
        $ok = $conn && @mysqli_real_connect(
            $conn,
            $db['host'],
            $db['user'],
            $db['pass'],
            $db['name'],
            (int)(isset($db['port']) ? $db['port'] : 3306)
        );
        if (!$ok) {
            throw new RuntimeException('Koneksi database gagal: ' . ($conn ? mysqli_connect_error() : 'mysqli_init gagal'));
        }
        mysqli_set_charset($conn, isset($db['charset']) ? $db['charset'] : 'utf8mb4');
        self::$conn = $conn;
        return $conn;
    }

    private static function run($sql, array $params)
    {
        $conn = self::conn();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Query tidak valid: ' . $conn->error);
        }
        if ($params) {
            $types = '';
            $bind = array();
            $values = array_values($params);
            foreach ($values as $i => $v) {
                if (is_bool($v)) {
                    $values[$i] = $v ? 1 : 0;
                    $v = $values[$i];
                }
                $types .= is_int($v) ? 'i' : (is_float($v) ? 'd' : 's');
                $bind[] = &$values[$i];
            }
            array_unshift($bind, $types);
            call_user_func_array(array($stmt, 'bind_param'), $bind);
        }
        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            throw new RuntimeException('Query gagal dijalankan: ' . $err);
        }
        return $stmt;
    }

    public static function all($sql, array $params = array())
    {
        $stmt = self::run($sql, $params);
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : array();
        $stmt->close();
        return $rows;
    }

    public static function row($sql, array $params = array())
    {
        $rows = self::all($sql, $params);
        return $rows ? $rows[0] : null;
    }

    public static function value($sql, array $params = array())
    {
        $row = self::row($sql, $params);
        return $row ? reset($row) : null;
    }

    /** Menjalankan INSERT/UPDATE/DELETE; mengembalikan jumlah baris terpengaruh. */
    public static function exec($sql, array $params = array())
    {
        $stmt = self::run($sql, $params);
        $n = $stmt->affected_rows;
        $stmt->close();
        return $n;
    }

    public static function insertId()
    {
        return (int)self::conn()->insert_id;
    }

    public static function begin()
    {
        self::conn()->begin_transaction();
    }

    public static function commit()
    {
        self::conn()->commit();
    }

    public static function rollback()
    {
        self::conn()->rollback();
    }
}
