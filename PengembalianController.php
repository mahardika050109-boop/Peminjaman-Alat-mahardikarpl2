<?php
class PengembalianController extends Controller
{
    protected $access = array(
        '*'      => array('admin', 'petugas'),
        'delete' => array('admin'),
    );
    protected $postOnly = array('store', 'update', 'delete');

    private function filters()
    {
        return array('status' => qs('status'), 'q' => qs('q'), 'from' => qs_date('from'), 'to' => qs_date('to'));
    }

    private function denda($raw, $auto)
    {
        if ($raw === '') {
            return array($auto, null);
        }
        if (!preg_match('/^\d{1,9}(\.\d{1,2})?$/', $raw)) {
            return array(0, 'Denda harus berupa angka 0 atau lebih.');
        }
        return array((float)$raw, null);
    }

    public function indexAction()
    {
        $f = $this->filters();
        list($rows, $pager) = PengembalianModel::search($f, $this->page(), $this->perPage());
        $this->render('pengembalian/index', array(
            'title'    => 'Data Pengembalian',
            'subtitle' => 'Catatan pengembalian alat, kondisi, dan denda',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
            'stat'     => PengembalianModel::stats(),
            'aktif'    => count(PeminjamanModel::aktif()),
        ));
    }

    private function formBaru($row, $errors)
    {
        $this->render('pengembalian/form', array(
            'title'  => 'Catat Pengembalian', 'mode' => 'create', 'row' => $row, 'errors' => $errors,
            'aktif'  => PeminjamanModel::aktif(),
        ));
    }

    public function createAction()
    {
        $this->formBaru(array(
            'id_peminjam' => qs('id'), 'tanggal_pengembalian' => date('Y-m-d'),
            'kondisi_pengembalian' => 'Baik', 'denda' => '',
        ), array());
    }

    public function storeAction()
    {
        $in = array(
            'id_peminjam'          => post('id_peminjam'),
            'tanggal_pengembalian' => post('tanggal_pengembalian'),
            'kondisi_pengembalian' => post('kondisi_pengembalian', 'Baik'),
            'denda'                => post('denda'),
        );
        $err = array();
        $pinjam = PeminjamanModel::find((int)$in['id_peminjam']);
        if (!$pinjam || $pinjam['status'] !== 'Disetujui') {
            $err['id_peminjam'] = 'Pilih peminjaman yang sedang berjalan.';
        } elseif (PengembalianModel::existsFor($pinjam['id_peminjaman'])) {
            $err['id_peminjam'] = 'Pengembalian untuk peminjaman ini sudah tercatat.';
        }
        if (!valid_date($in['tanggal_pengembalian'])) {
            $err['tanggal_pengembalian'] = 'Tanggal pengembalian tidak valid.';
        } elseif ($pinjam && $in['tanggal_pengembalian'] < $pinjam['tanggal_pinjam']) {
            $err['tanggal_pengembalian'] = 'Tanggal pengembalian tidak boleh sebelum tanggal pinjam.';
        }
        if (!in_array($in['kondisi_pengembalian'], PengembalianModel::$kondisi, true)) {
            $err['kondisi_pengembalian'] = 'Pilih kondisi yang valid.';
        }
        $batas = $pinjam ? $pinjam['tanggal_kembali'] : null;
        $auto = (!$err && $batas) ? PengembalianModel::hitungDenda($batas, $in['tanggal_pengembalian']) : 0;
        list($denda, $msg) = $this->denda($in['denda'], $auto);
        if ($msg) {
            $err['denda'] = $msg;
        }
        if ($err) {
            $this->formBaru($in, $err);
            return;
        }
        $status = PengembalianModel::statusOtomatis($batas, $in['tanggal_pengembalian']);
        PengembalianModel::create($pinjam['id_peminjaman'], $in['tanggal_pengembalian'], $in['kondisi_pengembalian'], $status, $denda);
        $this->log('Menambah data pengembalian');
        flash('success', 'Pengembalian peminjaman #' . $pinjam['id_peminjaman'] . ' dicatat (' . $status . ').');
        redirect('pengembalian/index');
    }

    private function formEdit($row, $errors)
    {
        $this->render('pengembalian/form', array(
            'title' => 'Edit Pengembalian', 'mode' => 'edit', 'row' => $row, 'errors' => $errors, 'aktif' => array(),
        ));
    }

    public function editAction()
    {
        $row = PengembalianModel::find((int)qs('id'));
        if (!$row) {
            abort(404);
        }
        $this->formEdit($row, array());
    }

    public function updateAction()
    {
        $id = (int)post('id_pengembalian');
        $row = PengembalianModel::find($id);
        if (!$row) {
            abort(404);
        }
        $in = array(
            'id_pengembalian'      => $id,
            'id_peminjam'          => $row['id_peminjam'],
            'nama'                 => $row['nama'],
            'tanggal_pengembalian' => post('tanggal_pengembalian'),
            'kondisi_pengembalian' => post('kondisi_pengembalian'),
            'status'               => post('status'),
            'denda'                => post('denda'),
        );
        $err = array();
        if (!valid_date($in['tanggal_pengembalian'])) {
            $err['tanggal_pengembalian'] = 'Tanggal pengembalian tidak valid.';
        }
        if (!in_array($in['kondisi_pengembalian'], PengembalianModel::$kondisi, true)) {
            $err['kondisi_pengembalian'] = 'Pilih kondisi yang valid.';
        }
        if (!in_array($in['status'], PengembalianModel::$statuses, true)) {
            $err['status'] = 'Pilih status yang valid.';
        }
        list($denda, $msg) = $this->denda($in['denda'], 0);
        if ($msg) {
            $err['denda'] = $msg;
        }
        if ($in['denda'] === '') {
            $err['denda'] = 'Isi denda (0 bila tidak ada).';
        }
        if ($err) {
            $this->formEdit($in, $err);
            return;
        }
        PengembalianModel::update($id, $in['tanggal_pengembalian'], $in['kondisi_pengembalian'], $in['status'], $denda);
        $this->log('Mengubah data pengembalian');
        flash('success', 'Data pengembalian diperbarui.');
        redirect('pengembalian/index');
    }

    public function deleteAction()
    {
        $id = (int)qs('id');
        if (!PengembalianModel::find($id)) {
            flash('error', 'Data pengembalian tidak ditemukan.');
        } else {
            PengembalianModel::delete($id);
            $this->log('Menghapus data pengembalian');
            flash('success', 'Catatan pengembalian dihapus. Peminjaman terkait kembali berstatus Disetujui.');
        }
        redirect('pengembalian/index');
    }
}
