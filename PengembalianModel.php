<?php
class PengembalianModel
{
    public static $kondisi = array('Baik', 'Rusak Ringan', 'Rusak Berat');
    public static $statuses = array('Dikembalikan', 'Terlambat');

    public static function search(array $f, $page, $perPage)
    {
        $where = array('1=1');
        $params = array();
        if (!empty($f['status'])) {
            $where[] = 'pg.status = ?';
            $params[] = $f['status'];
        }
        if (!empty($f['q'])) {
            $where[] = '(u.nama LIKE ? OR u.username LIKE ? OR pg.id_peminjam = ?)';
            $like = '%' . like_escape($f['q']) . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = (int)$f['q'];
        }
        if (!empty($f['from'])) {
            $where[] = 'pg.tanggal_pengembalian >= ?';
            $params[] = $f['from'];
        }
        if (!empty($f['to'])) {
            $where[] = 'pg.tanggal_pengembalian <= ?';
            $params[] = $f['to'];
        }
        $from = 'FROM tabel_pengembalian pg
                 LEFT JOIN peminjaman p ON p.id_peminjaman = pg.id_peminjam
                 LEFT JOIN tabel_user u ON u.id_user = p.id_user
                 WHERE ' . implode(' AND ', $where);
        $total = (int)Database::value('SELECT COUNT(*) ' . $from, $params);
        $pager = Pager::make($total, $page, $perPage > 0 ? $perPage : max(1, $total));
        $limit = $perPage > 0 ? ' LIMIT ' . (int)$pager->perPage . ' OFFSET ' . (int)$pager->offset : '';
        $rows = Database::all(
            'SELECT pg.*, u.nama, u.username, p.tanggal_pinjam, p.tanggal_kembali AS batas_kembali ' . $from . ' ORDER BY pg.id_pengembalian DESC' . $limit,
            $params
        );
        return array($rows, $pager);
    }

    public static function find($id)
    {
        return Database::row(
            'SELECT pg.*, u.nama, p.tanggal_kembali AS batas_kembali
             FROM tabel_pengembalian pg
             LEFT JOIN peminjaman p ON p.id_peminjaman = pg.id_peminjam
             LEFT JOIN tabel_user u ON u.id_user = p.id_user
             WHERE pg.id_pengembalian = ?',
            array((int)$id)
        );
    }

    public static function existsFor($idPeminjaman, $exceptId = 0)
    {
        return (int)Database::value(
            'SELECT COUNT(*) FROM tabel_pengembalian WHERE id_peminjam = ? AND id_pengembalian <> ?',
            array((int)$idPeminjaman, (int)$exceptId)
        ) > 0;
    }

    /** Hitung denda dari selisih hari terlambat x tarif per hari (config). */
    public static function hitungDenda($batasKembali, $tglPengembalian)
    {
        $rate = (float)config('denda_per_hari', 0);
        if ($rate <= 0 || !$batasKembali || $tglPengembalian <= $batasKembali) {
            return 0;
        }
        $days = (int)floor((strtotime($tglPengembalian) - strtotime($batasKembali)) / 86400);
        return max(0, $days) * $rate;
    }

    public static function statusOtomatis($batasKembali, $tglPengembalian)
    {
        return ($batasKembali && $tglPengembalian > $batasKembali) ? 'Terlambat' : 'Dikembalikan';
    }

    /** Mencatat pengembalian dan menandai peminjaman Dikembalikan. */
    public static function create($idPeminjaman, $tgl, $kondisi, $status, $denda)
    {
        Database::begin();
        try {
            Database::exec(
                'INSERT INTO tabel_pengembalian (id_peminjam, tanggal_pengembalian, kondisi_pengembalian, status, denda) VALUES (?, ?, ?, ?, ?)',
                array((int)$idPeminjaman, $tgl, $kondisi, $status, (float)$denda)
            );
            $id = Database::insertId();
            PeminjamanModel::setStatus($idPeminjaman, 'Dikembalikan');
            Database::commit();
            return $id;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    public static function update($id, $tgl, $kondisi, $status, $denda)
    {
        Database::exec(
            'UPDATE tabel_pengembalian SET tanggal_pengembalian = ?, kondisi_pengembalian = ?, status = ?, denda = ? WHERE id_pengembalian = ?',
            array($tgl, $kondisi, $status, (float)$denda, (int)$id)
        );
    }

    /** Menghapus catatan pengembalian; peminjaman dikembalikan ke status Disetujui. */
    public static function delete($id)
    {
        $row = self::find($id);
        if (!$row) {
            return 0;
        }
        Database::begin();
        try {
            $n = Database::exec('DELETE FROM tabel_pengembalian WHERE id_pengembalian = ?', array((int)$id));
            Database::exec(
                "UPDATE peminjaman SET status = 'Disetujui' WHERE id_peminjaman = ? AND status = 'Dikembalikan'",
                array((int)$row['id_peminjam'])
            );
            Database::commit();
            return $n;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    public static function stats()
    {
        $r = Database::row("SELECT COUNT(*) AS total, COALESCE(SUM(denda), 0) AS denda,
                                   SUM(status = 'Terlambat') AS terlambat FROM tabel_pengembalian");
        return array(
            'total'     => (int)$r['total'],
            'denda'     => (float)$r['denda'],
            'terlambat' => (int)$r['terlambat'],
        );
    }
}
