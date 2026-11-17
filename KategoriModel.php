<?php
class KategoriModel
{
    public static $statuses = array('Aktif', 'Nonaktif');

    public static function all()
    {
        return Database::all(
            'SELECT k.*, (SELECT COUNT(*) FROM tabel_alat a WHERE a.id_kategori = k.id_kategori) AS jumlah_alat
             FROM kategori k ORDER BY k.nama_kategori'
        );
    }

    public static function active()
    {
        return Database::all("SELECT * FROM kategori WHERE status = 'Aktif' ORDER BY nama_kategori");
    }

    public static function find($id)
    {
        return Database::row('SELECT * FROM kategori WHERE id_kategori = ?', array((int)$id));
    }

    public static function create($nama, $keterangan, $status)
    {
        Database::exec(
            'INSERT INTO kategori (nama_kategori, keterangan, status) VALUES (?, ?, ?)',
            array($nama, $keterangan, $status)
        );
        return Database::insertId();
    }

    public static function update($id, $nama, $keterangan, $status)
    {
        Database::exec(
            'UPDATE kategori SET nama_kategori = ?, keterangan = ?, status = ? WHERE id_kategori = ?',
            array($nama, $keterangan, $status, (int)$id)
        );
    }

    public static function delete($id)
    {
        return Database::exec('DELETE FROM kategori WHERE id_kategori = ?', array((int)$id));
    }

    public static function countAlat($id)
    {
        return (int)Database::value('SELECT COUNT(*) FROM tabel_alat WHERE id_kategori = ?', array((int)$id));
    }

    public static function nameExists($nama, $exceptId = 0)
    {
        return (int)Database::value(
            'SELECT COUNT(*) FROM kategori WHERE nama_kategori = ? AND id_kategori <> ?',
            array($nama, (int)$exceptId)
        ) > 0;
    }

    public static function count()
    {
        return (int)Database::value('SELECT COUNT(*) FROM kategori');
    }
}
