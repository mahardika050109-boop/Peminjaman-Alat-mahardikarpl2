<?php
class PeminjamanController extends Controller
{
    protected $access = array(
        '*'         => array('admin'),
        'index'     => array('admin', 'petugas'),
        'setujui'   => array('admin', 'petugas'),
        'tolak'     => array('admin', 'petugas'),
        'saya'      => array('peminjam'),
        'ajukan'    => array('peminjam'),
        'simpan'    => array('peminjam'),
        'kembalikan' => array('peminjam'),
    );
    protected $postOnly = array('store', 'update', 'delete', 'setujui', 'tolak', 'simpan', 'kembalikan');

    private function filters()
    {
        return array(
            'status' => qs('status'),
            'q'      => qs('q'),
            'from'   => qs_date('from'),
            'to'     => qs_date('to'),
        );
    }

    /** Validasi tanggal pinjam & kembali; $cekMasaLalu = tanggal pinjam tidak boleh sebelum hari ini. */
    private function validasiTanggal(array $in, array &$err, $cekMasaLalu)
    {
        if (!valid_date($in['tanggal_pinjam'])) {
            $err['tanggal_pinjam'] = 'Tanggal pinjam tidak valid.';
        } elseif ($cekMasaLalu && $in['tanggal_pinjam'] < date('Y-m-d')) {
            $err['tanggal_pinjam'] = 'Tanggal pinjam tidak boleh sebelum hari ini.';
        }
        if (!valid_date($in['tanggal_kembali'])) {
            $err['tanggal_kembali'] = 'Tanggal kembali tidak valid.';
        } elseif (!isset($err['tanggal_pinjam']) && $in['tanggal_kembali'] < $in['tanggal_pinjam']) {
            $err['tanggal_kembali'] = 'Tanggal kembali tidak boleh sebelum tanggal pinjam.';
        }
    }

    private function validasiItem(array $in, array &$err)
    {
        $alat = AlatModel::find((int)$in['id_alat']);
        if (!$alat || $alat['status_kategori'] !== 'Aktif') {
            $err['id_alat'] = 'Pilih alat yang tersedia.';
            return null;
        }
        if ($alat['kondisi_alat'] === 'Rusak Berat') {
            $err['id_alat'] = 'Alat ini rusak berat dan tidak dapat dipinjam.';
            return $alat;
        }
        if (!ctype_digit($in['jumlah']) || (int)$in['jumlah'] < 1) {
            $err['jumlah'] = 'Jumlah minimal 1.';
        } elseif ((int)$in['jumlah'] > $alat['tersedia']) {
            $err['jumlah'] = 'Stok tersedia hanya ' . $alat['tersedia'] . '.';
        }
        return $alat;
    }

    private function inputBaru()
    {
        return array(
            'id_user'         => post('id_user'),
            'id_alat'         => post('id_alat'),
            'jumlah'          => post('jumlah', '1'),
            'tanggal_pinjam'  => post('tanggal_pinjam'),
            'tanggal_kembali' => post('tanggal_kembali'),
            'status'          => post('status', 'Menunggu'),
        );
    }

    public function indexAction()
    {
        $f = $this->filters();
        list($rows, $pager) = PeminjamanModel::search($f, $this->page(), $this->perPage());
        $this->render('peminjaman/index', array(
            'title'    => 'Data Peminjaman',
            'subtitle' => 'Persetujuan dan pemantauan peminjaman alat',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
        ));
    }

    // ---------- Admin: tambah / ubah / hapus ----------

    private function formAdmin($mode, $row, $errors, $items = array())
    {
        $this->render('peminjaman/form', array(
            'title'   => $mode === 'create' ? 'Tambah Peminjaman' : 'Edit Peminjaman',
            'mode'    => $mode,
            'row'     => $row,
            'errors'  => $errors,
            'items'   => $items,
            'peminjam' => UserModel::peminjamList(),
            'alat'    => AlatModel::borrowable(),
        ));
    }

    public function createAction()
    {
        $this->formAdmin('create', array(
            'id_user' => '', 'id_alat' => '', 'jumlah' => '1',
            'tanggal_pinjam' => date('Y-m-d'), 'tanggal_kembali' => date('Y-m-d', strtotime('+3 days')),
            'status' => 'Menunggu',
        ), array());
    }

    public function storeAction()
    {
        $in = $this->inputBaru();
        $err = array();
        $user = UserModel::find((int)$in['id_user']);
        if (!$user || $user['level'] !== 'peminjam') {
            $err['id_user'] = 'Pilih peminjam.';
        }
        $this->validasiTanggal($in, $err, false);
        $alat = $this->validasiItem($in, $err);
        if (!in_array($in['status'], PeminjamanModel::$statuses, true)) {
            $err['status'] = 'Pilih status yang valid.';
        }
        if ($err) {
            $this->formAdmin('create', $in, $err);
            return;
        }
        $id = PeminjamanModel::create((int)$in['id_user'], $in['tanggal_pinjam'], $in['tanggal_kembali'], $in['status'], array(
            array('id_alat' => (int)$in['id_alat'], 'jumlah' => (int)$in['jumlah']),
        ));
        $this->log('Menambah data peminjaman');
        flash('success', 'Peminjaman #' . $id . ' (' . $alat['nama_alat'] . ') ditambahkan.');
        redirect('peminjaman/index');
    }

    public function editAction()
    {
        $row = PeminjamanModel::find((int)qs('id'));
        if (!$row) {
            abort(404);
        }
        $this->formAdmin('edit', $row, array(), PeminjamanModel::items($row['id_peminjaman']));
    }

    public function updateAction()
    {
        $id = (int)post('id_peminjaman');
        $row = PeminjamanModel::find($id);
        if (!$row) {
            abort(404);
        }
        $in = array(
            'id_user'         => post('id_user'),
            'tanggal_pinjam'  => post('tanggal_pinjam'),
            'tanggal_kembali' => post('tanggal_kembali'),
            'status'          => post('status'),
        );
        $err = array();
        $user = UserModel::find((int)$in['id_user']);
        if (!$user || $user['level'] !== 'peminjam') {
            $err['id_user'] = 'Pilih peminjam.';
        }
        $this->validasiTanggal($in, $err, false);
        if (!in_array($in['status'], PeminjamanModel::$statuses, true)) {
            $err['status'] = 'Pilih status yang valid.';
        } elseif ($in['status'] === 'Disetujui' && $row['status'] !== 'Disetujui') {
            $msg = PeminjamanModel::cekStok($id);
            if ($msg) {
                $err['status'] = $msg;
            }
        }
        if ($err) {
            $in['id_peminjaman'] = $id;
            $this->formAdmin('edit', $in, $err, PeminjamanModel::items($id));
            return;
        }
        PeminjamanModel::updateHeader($id, (int)$in['id_user'], $in['tanggal_pinjam'], $in['tanggal_kembali'], $in['status']);
        $this->log('Mengubah data peminjaman');
        flash('success', 'Peminjaman #' . $id . ' diperbarui.');
        redirect('peminjaman/index');
    }

    public function deleteAction()
    {
        $id = (int)qs('id');
        if (!PeminjamanModel::find($id)) {
            flash('error', 'Peminjaman tidak ditemukan.');
        } else {
            PeminjamanModel::delete($id);
            $this->log('Menghapus data peminjaman');
            flash('success', 'Peminjaman #' . $id . ' beserta catatan pengembaliannya dihapus.');
        }
        redirect('peminjaman/index');
    }

    // ---------- Admin & petugas: persetujuan ----------

    public function setujuiAction()
    {
        $id = (int)qs('id');
        $row = PeminjamanModel::find($id);
        if (!$row || $row['status'] !== 'Menunggu') {
            flash('error', 'Hanya peminjaman berstatus Menunggu yang dapat disetujui.');
            redirect('peminjaman/index');
        }
        $msg = PeminjamanModel::cekStok($id);
        if ($msg) {
            flash('error', $msg);
            redirect('peminjaman/index', array('status' => 'Menunggu'));
        }
        PeminjamanModel::setStatus($id, 'Disetujui');
        $this->log('Menyetujui peminjaman #' . $id);
        flash('success', 'Peminjaman #' . $id . ' disetujui.');
        redirect('peminjaman/index', array('status' => 'Menunggu'));
    }

    public function tolakAction()
    {
        $id = (int)qs('id');
        $row = PeminjamanModel::find($id);
        if (!$row || $row['status'] !== 'Menunggu') {
            flash('error', 'Hanya peminjaman berstatus Menunggu yang dapat ditolak.');
            redirect('peminjaman/index');
        }
        PeminjamanModel::setStatus($id, 'Ditolak');
        $this->log('Menolak peminjaman #' . $id);
        flash('success', 'Peminjaman #' . $id . ' ditolak.');
        redirect('peminjaman/index', array('status' => 'Menunggu'));
    }

    // ---------- Peminjam ----------

    public function sayaAction()
    {
        $f = array('status' => qs('status'), 'id_user' => Auth::id());
        list($rows, $pager) = PeminjamanModel::search($f, $this->page(), $this->perPage());
        $this->render('peminjaman/saya', array(
            'title'    => 'Peminjaman Saya',
            'subtitle' => 'Riwayat dan status pengajuan Anda',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
        ));
    }

    public function ajukanAction()
    {
        $this->render('peminjaman/ajukan', array(
            'title'    => 'Ajukan Peminjaman',
            'subtitle' => 'Pengajuan akan diperiksa petugas',
            'row'      => array(
                'id_alat' => qs('id_alat'), 'jumlah' => '1',
                'tanggal_pinjam' => date('Y-m-d'), 'tanggal_kembali' => date('Y-m-d', strtotime('+3 days')),
            ),
            'errors'   => array(),
            'alat'     => AlatModel::borrowable(),
        ));
    }

    public function simpanAction()
    {
        $in = $this->inputBaru();
        $err = array();
        $this->validasiTanggal($in, $err, true);
        $alat = $this->validasiItem($in, $err);
        if ($err) {
            $this->render('peminjaman/ajukan', array(
                'title' => 'Ajukan Peminjaman', 'subtitle' => 'Pengajuan akan diperiksa petugas',
                'row' => $in, 'errors' => $err, 'alat' => AlatModel::borrowable(),
            ));
            return;
        }
        PeminjamanModel::create(Auth::id(), $in['tanggal_pinjam'], $in['tanggal_kembali'], 'Menunggu', array(
            array('id_alat' => (int)$in['id_alat'], 'jumlah' => (int)$in['jumlah']),
        ));
        $this->log('Mengajukan peminjaman alat');
        flash('success', 'Pengajuan peminjaman "' . $alat['nama_alat'] . '" terkirim. Tunggu persetujuan petugas.');
        redirect('peminjaman/saya');
    }

    public function kembalikanAction()
    {
        $id = (int)qs('id');
        $row = PeminjamanModel::find($id);
        if (!$row || (int)$row['id_user'] !== Auth::id()) {
            abort(404);
        }
        if ($row['status'] !== 'Disetujui') {
            flash('error', 'Hanya peminjaman yang sedang berjalan yang dapat dikembalikan.');
            redirect('peminjaman/saya');
        }
        if (PengembalianModel::existsFor($id)) {
            flash('error', 'Pengembalian untuk peminjaman ini sudah tercatat.');
            redirect('peminjaman/saya');
        }
        $tgl = date('Y-m-d');
        $status = PengembalianModel::statusOtomatis($row['tanggal_kembali'], $tgl);
        $denda = PengembalianModel::hitungDenda($row['tanggal_kembali'], $tgl);
        PengembalianModel::create($id, $tgl, 'Baik', $status, $denda);
        $this->log('Mengembalikan alat');
        flash('success', 'Alat dikembalikan. Petugas dapat mengoreksi kondisi dan denda bila diperlukan.');
        redirect('peminjaman/saya');
    }
}
