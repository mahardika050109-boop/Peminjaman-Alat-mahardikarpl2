<?php
class DashboardController extends Controller
{
    protected $access = array('*' => array('admin', 'petugas', 'peminjam'));

    public function indexAction()
    {
        $level = Auth::level();

        if ($level === 'admin') {
            $menunggu = PeminjamanModel::search(array('status' => 'Menunggu'), 1, 5);
            $this->render('dashboard/admin', array(
                'title'    => 'Dashboard',
                'subtitle' => 'Ringkasan sistem peminjaman alat',
                'users'    => UserModel::countByLevel(),
                'alat'     => AlatModel::totals(),
                'kategori' => KategoriModel::count(),
                'pinjam'   => PeminjamanModel::stats(),
                'kembali'  => PengembalianModel::stats(),
                'menipis'  => AlatModel::lowStock(config('stok_menipis', 2)),
                'menunggu' => $menunggu[0],
                'logs'     => LogModel::recent(6),
                'plain'    => UserModel::countPlain(),
            ));
            return;
        }

        if ($level === 'petugas') {
            $menunggu = PeminjamanModel::search(array('status' => 'Menunggu'), 1, 6);
            $telat = PeminjamanModel::search(array('status' => 'Terlambat'), 1, 5);
            $this->render('dashboard/petugas', array(
                'title'    => 'Dashboard Petugas',
                'subtitle' => 'Persetujuan dan pengembalian alat',
                'pinjam'   => PeminjamanModel::stats(),
                'kembali'  => PengembalianModel::stats(),
                'menunggu' => $menunggu[0],
                'telat'    => $telat[0],
            ));
            return;
        }

        $this->render('dashboard/peminjam', array(
            'title'    => 'Beranda',
            'subtitle' => 'Pinjam alat dan pantau pengembalian',
            'stat'     => PeminjamanModel::statsUser(Auth::id()),
            'aktif'    => PeminjamanModel::aktif(Auth::id()),
            'tersedia' => count(AlatModel::borrowable()),
        ));
    }
}
