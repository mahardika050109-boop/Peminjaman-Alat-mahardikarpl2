<?php
class UserModel
{
    public static $levels = array('admin', 'petugas', 'peminjam');

    public static function search(array $f, $page, $perPage)
    {
        $where = array('1=1');
        $params = array();
        if (!empty($f['q'])) {
            $where[] = '(nama LIKE ? OR username LIKE ?)';
            $like = '%' . like_escape($f['q']) . '%';
            $params[] = $like;
            $params[] = $like;
        }
        if (!empty($f['level'])) {
            $where[] = 'level = ?';
            $params[] = $f['level'];
        }
        $from = 'FROM tabel_user WHERE ' . implode(' AND ', $where);
        $total = (int)Database::value('SELECT COUNT(*) ' . $from, $params);
        $pager = Pager::make($total, $page, $perPage);
        $rows = Database::all(
            'SELECT id_user, nama, username, level, password ' . $from . ' ORDER BY FIELD(level, "admin", "petugas", "peminjam"), nama LIMIT ' . (int)$pager->perPage . ' OFFSET ' . (int)$pager->offset,
            $params
        );
        return array($rows, $pager);
    }

    public static function find($id)
    {
        return Database::row('SELECT * FROM tabel_user WHERE id_user = ?', array((int)$id));
    }

    public static function findByUsername($username)
    {
        return Database::row('SELECT * FROM tabel_user WHERE username = ? ORDER BY id_user LIMIT 1', array($username));
    }

    public static function usernameExists($username, $exceptId = 0)
    {
        return (int)Database::value(
            'SELECT COUNT(*) FROM tabel_user WHERE username = ? AND id_user <> ?',
            array($username, (int)$exceptId)
        ) > 0;
    }

    public static function create($nama, $username, $password, $level)
    {
        Database::exec(
            'INSERT INTO tabel_user (nama, username, password, level) VALUES (?, ?, ?, ?)',
            array($nama, $username, Auth::hash($password), $level)
        );
        return Database::insertId();
    }

    public static function update($id, $nama, $username, $level, $password = '')
    {
        if ($password !== '') {
            Database::exec(
                'UPDATE tabel_user SET nama = ?, username = ?, level = ?, password = ? WHERE id_user = ?',
                array($nama, $username, $level, Auth::hash($password), (int)$id)
            );
        } else {
            Database::exec(
                'UPDATE tabel_user SET nama = ?, username = ?, level = ? WHERE id_user = ?',
                array($nama, $username, $level, (int)$id)
            );
        }
    }

    public static function setPassword($id, $password)
    {
        Database::exec('UPDATE tabel_user SET password = ? WHERE id_user = ?', array(Auth::hash($password), (int)$id));
    }

    public static function setNama($id, $nama)
    {
        Database::exec('UPDATE tabel_user SET nama = ? WHERE id_user = ?', array($nama, (int)$id));
    }

    public static function upgradeHash($id, $password)
    {
        self::setPassword($id, $password);
    }

    public static function delete($id)
    {
        return Database::exec('DELETE FROM tabel_user WHERE id_user = ?', array((int)$id));
    }

    public static function countByLevel()
    {
        $out = array('admin' => 0, 'petugas' => 0, 'peminjam' => 0);
        foreach (Database::all('SELECT level, COUNT(*) AS n FROM tabel_user GROUP BY level') as $r) {
            if (isset($out[$r['level']])) {
                $out[$r['level']] = (int)$r['n'];
            }
        }
        return $out;
    }

    public static function countAdmins()
    {
        return (int)Database::value("SELECT COUNT(*) FROM tabel_user WHERE level = 'admin'");
    }

    public static function hasPeminjaman($id)
    {
        return (int)Database::value('SELECT COUNT(*) FROM peminjaman WHERE id_user = ?', array((int)$id)) > 0;
    }

    public static function peminjamList()
    {
        return Database::all("SELECT id_user, nama, username FROM tabel_user WHERE level = 'peminjam' ORDER BY nama");
    }

    /** Ubah semua password teks biasa menjadi hash bcrypt. Mengembalikan jumlah akun yang diubah. */
    public static function hashAllPlain()
    {
        $n = 0;
        foreach (Database::all('SELECT id_user, password FROM tabel_user') as $r) {
            if ($r['password'] !== null && $r['password'] !== '' && !Auth::isHashed($r['password'])) {
                self::setPassword($r['id_user'], $r['password']);
                $n++;
            }
        }
        return $n;
    }

    public static function countPlain()
    {
        $n = 0;
        foreach (Database::all('SELECT password FROM tabel_user') as $r) {
            if ($r['password'] !== null && $r['password'] !== '' && !Auth::isHashed($r['password'])) {
                $n++;
            }
        }
        return $n;
    }
}
