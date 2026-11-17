<?php
class LogModel
{
    public static function catat($idUser, $aktivitas)
    {
        try {
            Database::exec(
                'INSERT INTO log_aktivitas (id_user, aktivitas, tanggal) VALUES (?, ?, NOW())',
                array($idUser ? (int)$idUser : null, mb_substr($aktivitas, 0, 250))
            );
        } catch (Throwable $e) {
            error_log('Gagal mencatat log: ' . $e->getMessage());
        }
    }

    public static function search(array $f, $page, $perPage)
    {
        $where = array('1=1');
        $params = array();
        if (!empty($f['q'])) {
            $where[] = 'l.aktivitas LIKE ?';
            $params[] = '%' . like_escape($f['q']) . '%';
        }
        if (!empty($f['id_user'])) {
            $where[] = 'l.id_user = ?';
            $params[] = (int)$f['id_user'];
        }
        if (!empty($f['from'])) {
            $where[] = 'DATE(l.tanggal) >= ?';
            $params[] = $f['from'];
        }
        if (!empty($f['to'])) {
            $where[] = 'DATE(l.tanggal) <= ?';
            $params[] = $f['to'];
        }
        $from = 'FROM log_aktivitas l LEFT JOIN tabel_user u ON u.id_user = l.id_user WHERE ' . implode(' AND ', $where);
        $total = (int)Database::value('SELECT COUNT(*) ' . $from, $params);
        $pager = Pager::make($total, $page, $perPage);
        $rows = Database::all(
            'SELECT l.*, u.nama, u.level ' . $from . ' ORDER BY l.id_log DESC LIMIT ' . (int)$pager->perPage . ' OFFSET ' . (int)$pager->offset,
            $params
        );
        return array($rows, $pager);
    }

    public static function recent($n = 5)
    {
        return Database::all(
            'SELECT l.*, u.nama FROM log_aktivitas l LEFT JOIN tabel_user u ON u.id_user = l.id_user ORDER BY l.id_log DESC LIMIT ' . (int)$n
        );
    }

    public static function users()
    {
        return Database::all(
            'SELECT DISTINCT u.id_user, u.nama FROM log_aktivitas l JOIN tabel_user u ON u.id_user = l.id_user ORDER BY u.nama'
        );
    }

    public static function delete($id)
    {
        return Database::exec('DELETE FROM log_aktivitas WHERE id_log = ?', array((int)$id));
    }

    public static function purgeOlderThan($days)
    {
        return Database::exec('DELETE FROM log_aktivitas WHERE tanggal < DATE_SUB(NOW(), INTERVAL ' . (int)$days . ' DAY)');
    }

    public static function count()
    {
        return (int)Database::value('SELECT COUNT(*) FROM log_aktivitas');
    }
}
