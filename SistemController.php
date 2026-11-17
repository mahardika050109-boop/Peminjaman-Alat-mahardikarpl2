<?php
class SistemController extends Controller
{
    protected $access = array('*' => array('admin'));
    protected $postOnly = array('backup');

    public function indexAction()
    {
        $need = array('tabel_user', 'tabel_alat', 'kategori', 'peminjaman', 'detail_peminjaman', 'tabel_pengembalian', 'log_aktivitas');
        $tables = array();
        foreach ($need as $t) {
            try {
                $tables[$t] = (int)Database::value('SELECT COUNT(*) FROM `' . $t . '`');
            } catch (Exception $e) {
                $tables[$t] = null;
            }
        }

        $defaultPw = false;
        foreach (Database::all("SELECT password FROM tabel_user WHERE level = 'admin'") as $a) {
            if (Auth::verify((string)config('default_admin_password'), $a['password'])) {
                $defaultPw = true;
            }
        }

        $this->render('sistem/index', array(
            'title'     => 'Sistem',
            'subtitle'  => 'Pemeriksaan koneksi dan cadangan database',
            'php'       => PHP_VERSION,
            'mysqli'    => extension_loaded('mysqli'),
            'mbstring'  => extension_loaded('mbstring'),
            'dbVersion' => Database::conn()->server_info,
            'tables'    => $tables,
            'defaultPw' => $defaultPw,
            'plain'     => UserModel::countPlain(),
            'debug'     => (bool)config('debug'),
            'denda'     => (float)config('denda_per_hari', 0),
        ));
    }

    /** Unduh cadangan seluruh tabel sebagai file .sql (bisa diimpor lewat phpMyAdmin). */
    public function backupAction()
    {
        $conn = Database::conn();
        $this->log('Mengunduh cadangan database');

        @set_time_limit(120);
        header('Content-Type: application/sql; charset=utf-8');
        header('Content-Disposition: attachment; filename="backup_peminjam_alat_' . date('Ymd_His') . '.sql"');

        echo "-- Cadangan database Peminjaman Alat\n-- Dibuat: " . date('d/m/Y H:i:s') . "\n\n";
        echo "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        $tables = array();
        $res = $conn->query('SHOW TABLES');
        while ($r = $res->fetch_row()) {
            $tables[] = $r[0];
        }

        foreach ($tables as $t) {
            $create = $conn->query('SHOW CREATE TABLE `' . $t . '`')->fetch_row();
            echo 'DROP TABLE IF EXISTS `' . $t . "`;\n" . $create[1] . ";\n\n";

            $data = $conn->query('SELECT * FROM `' . $t . '`', MYSQLI_USE_RESULT);
            $batch = array();
            $cols = null;
            while ($row = $data->fetch_assoc()) {
                if ($cols === null) {
                    $cols = '`' . implode('`, `', array_keys($row)) . '`';
                }
                $vals = array();
                foreach ($row as $v) {
                    $vals[] = $v === null ? 'NULL' : "'" . $conn->real_escape_string($v) . "'";
                }
                $batch[] = '(' . implode(', ', $vals) . ')';
                if (count($batch) >= 100) {
                    echo 'INSERT INTO `' . $t . '` (' . $cols . ") VALUES\n" . implode(",\n", $batch) . ";\n";
                    $batch = array();
                }
            }
            $data->free();
            if ($batch) {
                echo 'INSERT INTO `' . $t . '` (' . $cols . ") VALUES\n" . implode(",\n", $batch) . ";\n";
            }
            echo "\n";
        }
        echo "SET FOREIGN_KEY_CHECKS=1;\n";
        exit;
    }
}
