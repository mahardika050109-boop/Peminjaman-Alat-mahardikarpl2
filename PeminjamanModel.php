<?php
class PeminjamanModel
{
    public static $statuses = array('Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan');

    private static function buildWhere(array $f, array &$params)
    {
        $where = array('1=1');
        if (!empty($f['status'])) {
            if ($f['status'] === 'Terlambat') {
                $where[] = "p.status = 'Disetujui' AND p.tanggal_kembali < CURDATE()";
            } else {
                $where[] = 'p.status = ?';
                $params[] = $f['status'];
            }
        }
        if (!empty($f['id_user'])) {
            $where[] = 'p.id_user = ?';
            $params[] = (int)$f['id_user'];
        }
        if (!empty($f['q'])) {
            $where[] = '(u.nama LIKE ? OR u.username LIKE ? OR a.nama_alat LIKE ? OR p.id_peminjaman = ?)';
            $like = '%' . like_escape($f['q']) . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = (int)$f['q'];
        }
        if (!empty($f['from'])) {
            $where[] = 'p.tanggal_pinjam >= ?';
            $params[] = $f['from'];
        }
        if (!empty($f['to'])) {
            $where[] = 'p.tanggal_pinjam <= ?';
            $params[] = $f['to'];
        }
        return implode(' AND ', $where);
    }

    /** $perPage = 0 berarti tanpa paginasi (untuk laporan). */
    public static function search(array $f, $page, $perPage)
    {
        $params = array();
        $where = self::buildWhere($f, $params);
        $from = 'FROM peminjaman p
                 LEFT JOIN tabel_user u ON u.id_user = p.id_user
                 LEFT JOIN detail_peminjaman d ON d.id_peminjaman = p.id_peminjaman
                 LEFT JOIN tabel_alat a ON a.id_alat = d.id_alat
                 WHERE ' . $where;
        $total = (int)Database::value('SELECT COUNT(DISTINCT p.id_peminjaman) ' . $from, $params);
        $pager = Pager::make($total, $page, $perPage > 0 ? $perPage : max(1, $total));
        $limit = $perPage > 0 ? ' LIMIT ' . (int)$pager->perPage . ' OFFSET ' . (int)$pager->offset : '';
        $rows = Database::all(
            "SELECT p.*, u.nama, u.username,
                    GROUP_CONCAT(CONCAT(a.nama_alat, ' x', d.jumlah) SEPARATOR ', ') AS alat_list,
                    COALESCE(SUM(d.jumlah), 0) AS total_item
             " . $from . '
             GROUP BY p.id_peminjaman, u.nama, u.username
             ORDER BY p.id_peminjaman DESC' . $limit,
            $params
        );
        return array($rows, $pager);
    }

    public static function find($id)
    {
        return Database::row(
            'SELECT p.*, u.nama, u.username FROM peminjaman p LEFT JOIN tabel_user u ON u.id_user = p.id_user WHERE p.id_peminjaman = ?',
            array((int)$id)
        );
    }

    public static function items($id)
    {
        return Database::all(
            'SELECT d.*, a.nama_alat, a.stok FROM detail_peminjaman d LEFT JOIN tabel_alat a ON a.id_alat = d.id_alat WHERE d.id_peminjaman = ?',
            array((int)$id)
        );
    }

    /** Daftar peminjaman Disetujui (belum dikembalikan), untuk formulir pengembalian. */
    public static function aktif($idUser = 0)
    {
        $params = array();
        $sql = "SELECT p.*, u.nama,
                       GROUP_CONCAT(CONCAT(a.nama_alat, ' x', d.jumlah) SEPARATOR ', ') AS alat_list
                FROM peminjaman p
                LEFT JOIN tabel_user u ON u.id_user = p.id_user
                LEFT JOIN detail_peminjaman d ON d.id_peminjaman = p.id_peminjaman
                LEFT JOIN tabel_alat a ON a.id_alat = d.id_alat
                WHERE p.status = 'Disetujui'";
        if ($idUser) {
            $sql .= ' AND p.id_user = ?';
            $params[] = (int)$idUser;
        }
        $sql .= ' GROUP BY p.id_peminjaman, u.nama ORDER BY p.tanggal_kembali, p.id_peminjaman';
        return Database::all($sql, $params);
    }

    /** Membuat peminjaman beserta item-itemnya. $items = array(array('id_alat'=>, 'jumlah'=>)). */
    public static function create($idUser, $tglPinjam, $tglKembali, $status, array $items)
    {
        Database::begin();
        try {
            Database::exec(
                'INSERT INTO peminjaman (id_user, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?)',
                array((int)$idUser, $tglPinjam, $tglKembali, $status)
            );
            $id = Database::insertId();
            foreach ($items as $it) {
                Database::exec(
                    'INSERT INTO detail_peminjaman (id_peminjaman, id_alat, jumlah) VALUES (?, ?, ?)',
                    array($id, (int)$it['id_alat'], (int)$it['jumlah'])
                );
            }
            Database::commit();
            return $id;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    public static function updateHeader($id, $idUser, $tglPinjam, $tglKembali, $status)
    {
        Database::exec(
            'UPDATE peminjaman SET id_user = ?, tanggal_pinjam = ?, tanggal_kembali = ?, status = ? WHERE id_peminjaman = ?',
            array((int)$idUser, $tglPinjam, $tglKembali, $status, (int)$id)
        );
    }

    public static function setStatus($id, $status)
    {
        Database::exec('UPDATE peminjaman SET status = ? WHERE id_peminjaman = ?', array($status, (int)$id));
    }

    /**
     * Cek apakah semua item peminjaman masih tersedia stoknya.
     * Mengembalikan null bila cukup, atau pesan kesalahan bila tidak.
     */
    public static function cekStok($id)
    {
        foreach (self::items($id) as $it) {
            $alat = AlatModel::find($it['id_alat']);
            if (!$alat) {
                return 'Alat pada peminjaman ini sudah tidak ada di data alat.';
            }
            // Peminjaman ini belum Disetujui, jadi jumlahnya belum terhitung di "dipinjam".
            if ($alat['tersedia'] < (int)$it['jumlah']) {
                return 'Stok "' . $alat['nama_alat'] . '" tidak cukup: tersedia ' . $alat['tersedia'] . ', diminta ' . (int)$it['jumlah'] . '.';
            }
        }
        return null;
    }

    /** Menghapus peminjaman beserta detail dan catatan pengembaliannya. */
    public static function delete($id)
    {
        Database::begin();
        try {
            Database::exec('DELETE FROM detail_peminjaman WHERE id_peminjaman = ?', array((int)$id));
            Database::exec('DELETE FROM tabel_pengembalian WHERE id_peminjam = ?', array((int)$id));
            $n = Database::exec('DELETE FROM peminjaman WHERE id_peminjaman = ?', array((int)$id));
            Database::commit();
            return $n;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    public static function stats()
    {
        $s = array('Menunggu' => 0, 'Disetujui' => 0, 'Ditolak' => 0, 'Dikembalikan' => 0);
        foreach (Database::all('SELECT status, COUNT(*) AS n FROM peminjaman GROUP BY status') as $r) {
            if (isset($s[$r['status']])) {
                $s[$r['status']] = (int)$r['n'];
            }
        }
        $s['Total'] = array_sum($s);
        $s['Terlambat'] = (int)Database::value("SELECT COUNT(*) FROM peminjaman WHERE status = 'Disetujui' AND tanggal_kembali < CURDATE()");
        return $s;
    }

    public static function statsUser($idUser)
    {
        $s = array('Menunggu' => 0, 'Disetujui' => 0, 'Ditolak' => 0, 'Dikembalikan' => 0);
        foreach (Database::all('SELECT status, COUNT(*) AS n FROM peminjaman WHERE id_user = ? GROUP BY status', array((int)$idUser)) as $r) {
            if (isset($s[$r['status']])) {
                $s[$r['status']] = (int)$r['n'];
            }
        }
        $s['Total'] = array_sum($s);
        return $s;
    }
}
