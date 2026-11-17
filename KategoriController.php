<?php
class KategoriController extends Controller
{
    protected $access = array('*' => array('admin'));
    protected $postOnly = array('store', 'update', 'delete');

    private function input()
    {
        return array(
            'nama_kategori' => post('nama_kategori'),
            'keterangan'    => post('keterangan'),
            'status'        => post('status', 'Aktif'),
        );
    }

    private function validasi(array $in, $id = 0)
    {
        $err = array();
        if ($in['nama_kategori'] === '') {
            $err['nama_kategori'] = 'Nama kategori wajib diisi.';
        } elseif (mb_strlen($in['nama_kategori']) > 100) {
            $err['nama_kategori'] = 'Nama kategori maksimal 100 karakter.';
        } elseif (KategoriModel::nameExists($in['nama_kategori'], $id)) {
            $err['nama_kategori'] = 'Nama kategori sudah ada.';
        }
        if (!in_array($in['status'], KategoriModel::$statuses, true)) {
            $err['status'] = 'Pilih status yang valid.';
        }
        return $err;
    }

    public function indexAction()
    {
        $this->render('kategori/index', array(
            'title'    => 'Kategori Alat',
            'subtitle' => 'Kategori nonaktif tidak muncul di daftar pinjam',
            'rows'     => KategoriModel::all(),
        ));
    }

    public function createAction()
    {
        $this->render('kategori/form', array(
            'title' => 'Tambah Kategori', 'mode' => 'create', 'errors' => array(),
            'row'   => array('nama_kategori' => '', 'keterangan' => '', 'status' => 'Aktif'),
        ));
    }

    public function storeAction()
    {
        $in = $this->input();
        $err = $this->validasi($in);
        if ($err) {
            $this->render('kategori/form', array('title' => 'Tambah Kategori', 'mode' => 'create', 'row' => $in, 'errors' => $err));
            return;
        }
        KategoriModel::create($in['nama_kategori'], $in['keterangan'], $in['status']);
        $this->log('Menambah kategori');
        flash('success', 'Kategori "' . $in['nama_kategori'] . '" ditambahkan.');
        redirect('kategori/index');
    }

    public function editAction()
    {
        $row = KategoriModel::find((int)qs('id'));
        if (!$row) {
            abort(404);
        }
        $this->render('kategori/form', array('title' => 'Edit Kategori', 'mode' => 'edit', 'row' => $row, 'errors' => array()));
    }

    public function updateAction()
    {
        $id = (int)post('id_kategori');
        if (!KategoriModel::find($id)) {
            abort(404);
        }
        $in = $this->input();
        $err = $this->validasi($in, $id);
        if ($err) {
            $in['id_kategori'] = $id;
            $this->render('kategori/form', array('title' => 'Edit Kategori', 'mode' => 'edit', 'row' => $in, 'errors' => $err));
            return;
        }
        KategoriModel::update($id, $in['nama_kategori'], $in['keterangan'], $in['status']);
        $this->log('Mengubah kategori');
        flash('success', 'Kategori diperbarui.');
        redirect('kategori/index');
    }

    public function deleteAction()
    {
        $id = (int)qs('id');
        $row = KategoriModel::find($id);
        if (!$row) {
            flash('error', 'Kategori tidak ditemukan.');
        } elseif (KategoriModel::countAlat($id) > 0) {
            flash('error', 'Kategori "' . $row['nama_kategori'] . '" masih dipakai ' . KategoriModel::countAlat($id) . ' alat. Pindahkan atau hapus alatnya dulu, atau ubah statusnya menjadi Nonaktif.');
        } else {
            KategoriModel::delete($id);
            $this->log('Menghapus kategori');
            flash('success', 'Kategori dihapus.');
        }
        redirect('kategori/index');
    }
}
