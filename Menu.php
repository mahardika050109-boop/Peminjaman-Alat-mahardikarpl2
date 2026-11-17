<?php
class Menu
{
    /** Daftar menu: array(label, ikon, rute). */
    public static function items($level)
    {
        switch ($level) {
            case 'admin':
                return array(
                    array('Dashboard', '🏠', 'dashboard/index'),
                    array('Peminjaman', '📋', 'peminjaman/index'),
                    array('Pengembalian', '🔄', 'pengembalian/index'),
                    array('Data Alat', '🧰', 'alat/index'),
                    array('Kategori', '📁', 'kategori/index'),
                    array('Data User', '👥', 'user/index'),
                    array('Laporan', '🖨️', 'laporan/index'),
                    array('Log Aktivitas', '📜', 'log/index'),
                    array('Sistem', '⚙️', 'sistem/index'),
                    array('Profil', '👤', 'profil/index'),
                );
            case 'petugas':
                return array(
                    array('Dashboard', '🏠', 'dashboard/index'),
                    array('Persetujuan', '✅', 'peminjaman/index'),
                    array('Pengembalian', '🔄', 'pengembalian/index'),
                    array('Daftar Alat', '🧰', 'alat/katalog'),
                    array('Laporan', '🖨️', 'laporan/index'),
                    array('Profil', '👤', 'profil/index'),
                );
            default:
                return array(
                    array('Beranda', '🏠', 'dashboard/index'),
                    array('Daftar Alat', '🧰', 'alat/katalog'),
                    array('Ajukan Pinjam', '📝', 'peminjaman/ajukan'),
                    array('Peminjaman Saya', '📋', 'peminjaman/saya'),
                    array('Profil', '👤', 'profil/index'),
                );
        }
    }

    /** Rute yang tampil di bilah bawah (mobile). */
    public static function tabs($level)
    {
        $pick = array(
            'admin'    => array('dashboard/index', 'peminjaman/index', 'pengembalian/index', 'alat/index'),
            'petugas'  => array('dashboard/index', 'peminjaman/index', 'pengembalian/index', 'laporan/index'),
            'peminjam' => array('dashboard/index', 'alat/katalog', 'peminjaman/ajukan', 'peminjaman/saya'),
        );
        $want = isset($pick[$level]) ? $pick[$level] : $pick['peminjam'];
        $out = array();
        foreach (self::items($level) as $it) {
            if (in_array($it[2], $want, true)) {
                $out[] = $it;
            }
        }
        return $out;
    }

    public static function currentRoute()
    {
        $r = trim(qs('r'), '/');
        $parts = explode('/', $r);
        return strtolower($parts[0]) . '/' . (isset($parts[1]) && $parts[1] !== '' ? strtolower($parts[1]) : 'index');
    }

    public static function isActive($route, array $items)
    {
        $cur = self::currentRoute();
        if ($cur === $route) {
            return true;
        }
        $ctrl = explode('/', $route);
        $curCtrl = explode('/', $cur);
        if ($ctrl[0] !== $curCtrl[0]) {
            return false;
        }
        foreach ($items as $it) {
            if ($it[2] === $cur) {
                return false;
            }
        }
        // Rute lain pada kontroler yang sama (mis. user/edit) dianggap bagian dari item pertama kontroler itu.
        foreach ($items as $it) {
            $c = explode('/', $it[2]);
            if ($c[0] === $curCtrl[0]) {
                return $it[2] === $route;
            }
        }
        return false;
    }
}
