<?php
class LaporanController extends Controller
{
    protected $access = array('*' => array('admin', 'petugas'));

    private function filters()
    {
        return array('status' => qs('status'), 'from' => qs_date('from'), 'to' => qs_date('to'));
    }

    private function periode(array $f)
    {
        if ($f['from'] && $f['to']) {
            return tgl($f['from']) . ' s/d ' . tgl($f['to']);
        }
        if ($f['from']) {
            return 'Mulai ' . tgl($f['from']);
        }
        if ($f['to']) {
            return 'Sampai ' . tgl($f['to']);
        }
        return 'Semua periode';
    }

    public function indexAction()
    {
        $this->render('laporan/index', array(
            'title'    => 'Laporan',
            'subtitle' => 'Cetak atau unduh data sebagai CSV',
            'f'        => $this->filters(),
            'pinjam'   => PeminjamanModel::stats(),
            'kembali'  => PengembalianModel::stats(),
            'alat'     => AlatModel::totals(),
        ));
    }

    public function peminjamanAction()
    {
        $f = $this->filters();
        list($rows) = PeminjamanModel::search($f, 1, 0);
        $this->render('laporan/peminjaman', array(
            'title' => 'Laporan Peminjaman', 'rows' => $rows, 'periode' => $this->periode($f), 'f' => $f,
        ), 'print');
    }

    public function pengembalianAction()
    {
        $f = $this->filters();
        if (!in_array($f['status'], PengembalianModel::$statuses, true)) {
            $f['status'] = '';
        }
        list($rows) = PengembalianModel::search($f, 1, 0);
        $total = 0;
        foreach ($rows as $r) {
            $total += (float)$r['denda'];
        }
        $this->render('laporan/pengembalian', array(
            'title' => 'Laporan Pengembalian', 'rows' => $rows, 'periode' => $this->periode($f), 'f' => $f, 'total' => $total,
        ), 'print');
    }

    public function alatAction()
    {
        list($rows) = AlatModel::search(array(), 1, 0);
        $this->render('laporan/alat', array(
            'title' => 'Laporan Data Alat', 'rows' => $rows, 'periode' => 'Per ' . date('d/m/Y'), 'f' => array(),
        ), 'print');
    }

    private static function csvSafe($v)
    {
        $v = (string)$v;
        return ($v !== '' && strpos("=+-@\t\r", $v[0]) !== false) ? "'" . $v : $v;
    }

    public function csvAction()
    {
        $jenis = qs('jenis');
        $f = $this->filters();
        $header = array();
        $lines = array();

        if ($jenis === 'peminjaman') {
            list($rows) = PeminjamanModel::search($f, 1, 0);
            $header = array('ID', 'Peminjam', 'Alat', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status');
            foreach ($rows as $r) {
                $lines[] = array($r['id_peminjaman'], $r['nama'], $r['alat_list'], $r['tanggal_pinjam'], $r['tanggal_kembali'],
                    is_late($r) ? 'Disetujui (terlambat)' : $r['status']);
            }
        } elseif ($jenis === 'pengembalian') {
            if (!in_array($f['status'], PengembalianModel::$statuses, true)) {
                $f['status'] = '';
            }
            list($rows) = PengembalianModel::search($f, 1, 0);
            $header = array('ID', 'ID Peminjaman', 'Peminjam', 'Tanggal Pengembalian', 'Kondisi', 'Status', 'Denda');
            foreach ($rows as $r) {
                $lines[] = array($r['id_pengembalian'], $r['id_peminjam'], $r['nama'], $r['tanggal_pengembalian'],
                    $r['kondisi_pengembalian'], $r['status'], (int)$r['denda']);
            }
        } elseif ($jenis === 'alat') {
            list($rows) = AlatModel::search(array(), 1, 0);
            $header = array('ID', 'Nama Alat', 'Kategori', 'Stok', 'Dipinjam', 'Tersedia', 'Kondisi');
            foreach ($rows as $r) {
                $lines[] = array($r['id_alat'], $r['nama_alat'], $r['nama_kategori'], $r['stok'], $r['dipinjam'], $r['tersedia'], $r['kondisi_alat']);
            }
        } else {
            abort(404);
        }

        $this->log('Mengunduh laporan ' . $jenis . ' (CSV)');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan_' . $jenis . '_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, $header, ';');
        foreach ($lines as $line) {
            $safe = array();
            foreach ($line as $cell) {
                $safe[] = self::csvSafe($cell);
            }
            fputcsv($out, $safe, ';');
        }
        fclose($out);
        exit;
    }
}
