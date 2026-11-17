<?php
class AlatModel
{
    public static $kondisi = array('Baik', 'Rusak Ringan', 'Rusak Berat');

    // Jumlah unit yang sedang dipinjam (peminjaman berstatus Disetujui).
    const DIPINJAM = "COALESCE((SELECT SUM(d.jumlah) FROM detail_peminjaman d
        JOIN peminjaman p ON p.id_peminjaman = d.id_peminjaman
        WHERE d.id_alat = a.id_alat AND p.status = 'Disetujui'), 0)";

    private static function withTersedia(array $rows)
    {
        foreach ($rows as $i => $r) {
            $rows[$i]['dipinjam'] = (int)$r['dipinjam'];
            $rows[$i]['tersedia'] = max(0, (int)$r['stok'] - (int)$r['dipinjam']);
        }
        return $rows;
    }

    public static function search(array $f, $page, $perPage)
    {
        $where = array('1=1');
        $params = array();
        if (!empty($f['q'])) {
            $where[] = 'a.nama_alat LIKE ?';
            $params[] = '%' . like_escape($f['q']) . '%';
        }
        if (!empty($f['id_kategori'])) {
            $where[] = 'a.id_kategori = ?';
            $params[] = (int)$f['id_kategori'];
        }
        if (!empty($f['kondisi'])) {
            $where[] = 'a.kondisi_alat = ?';
            $params[] = $f['kondisi'];
        }
        $from = 'FROM tabel_alat a LEFT JOIN kategori k ON k.id_kategori = a.id_kategori WHERE ' . implode(' AND ', $where);
        $total = (int)Database::value('SELECT COUNT(*) ' . $from, $params);
        $pager = Pager::make($total, $page, $perPage > 0 ? $perPage : max(1, $total));
        $limit = $perPage > 0 ? ' LIMIT ' . (int)$pager->perPage . ' OFFSET ' . (int)$pager->offset : '';
        $rows = Database::all(
            'SELECT a.*, k.nama_kategori, k.status AS status_kategori, ' . self::DIPINJAM . ' AS dipinjam ' . $from . ' ORDER BY a.nama_alat' . $limit,
            $params
        );
        return array(self::withTersedia($rows), $pager);
    }

    public static function find($id)
    {
        $rows = Database::all(
            'SELECT a.*, k.nama_kategori, k.status AS status_kategori, ' . self::DIPINJAM . ' AS dipinjam
             FROM tabel_alat a LEFT JOIN kategori k ON k.id_kategori = a.id_kategori WHERE a.id_alat = ?',
            array((int)$id)
        );
        $rows = self::withTersedia($rows);
        return $rows ? $rows[0] : null;
    }

    /** Alat yang boleh dipinjam: kategori aktif, tidak rusak berat, stok tersedia. */
    public static function borrowable()
    {
        $rows = Database::all(
            "SELECT a.*, k.nama_kategori, k.status AS status_kategori, " . self::DIPINJAM . " AS dipinjam
             FROM tabel_alat a JOIN kategori k ON k.id_kategori = a.id_kategori
             WHERE k.status = 'Aktif' AND a.kondisi_alat <> 'Rusak Berat' ORDER BY a.nama_alat"
        );
        $out = array();
        foreach (self::withTersedia($rows) as $r) {
            if ($r['tersedia'] > 0) {
                $out[] = $r;
            }
        }
        return $out;
    }

    public static function create($idKategori, $nama, $stok, $kondisi)
    {
        Database::exec(
            'INSERT INTO tabel_alat (id_kategori, nama_alat, stok, kondisi_alat) VALUES (?, ?, ?, ?)',
            array((int)$idKategori, $nama, (int)$stok, $kondisi)
        );
        return Database::insertId();
    }

    public static function update($id, $idKategori, $nama, $stok, $kondisi)
    {
        Database::exec(
            'UPDATE tabel_alat SET id_kategori = ?, nama_alat = ?, stok = ?, kondisi_alat = ? WHERE id_alat = ?',
            array((int)$idKategori, $nama, (int)$stok, $kondisi, (int)$id)
        );
    }

    public static function delete($id)
    {
        return Database::exec('DELETE FROM tabel_alat WHERE id_alat = ?', array((int)$id));
    }

    public static function isUsed($id)
    {
        return (int)Database::value('SELECT COUNT(*) FROM detail_peminjaman WHERE id_alat = ?', array((int)$id)) > 0;
    }

    public static function lowStock($threshold)
    {
        $rows = Database::all(
            'SELECT a.*, k.nama_kategori, ' . self::DIPINJAM . ' AS dipinjam
             FROM tabel_alat a LEFT JOIN kategori k ON k.id_kategori = a.id_kategori ORDER BY a.nama_alat'
        );
        $out = array();
        foreach (self::withTersedia($rows) as $r) {
            if ($r['tersedia'] <= (int)$threshold) {
                $out[] = $r;
            }
        }
        return $out;
    }

    public static function totals()
    {
        $rows = Database::all('SELECT a.stok, ' . self::DIPINJAM . ' AS dipinjam FROM tabel_alat a');
        $t = array('jenis' => count($rows), 'stok' => 0, 'dipinjam' => 0);
        foreach ($rows as $r) {
            $t['stok'] += (int)$r['stok'];
            $t['dipinjam'] += (int)$r['dipinjam'];
        }
        $t['tersedia'] = max(0, $t['stok'] - $t['dipinjam']);
        return $t;
    }
}
