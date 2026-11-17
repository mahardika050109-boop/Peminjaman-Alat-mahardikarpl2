<?php
class AlatController extends Controller
{
    protected $access = array(
        '*'       => array('admin'),
        'katalog' => array('admin', 'petugas', 'peminjam'),
    );
    protected $postOnly = array('store', 'update', 'delete');

    private function input()
    {
        return array(
            'id_kategori'  => post('id_kategori'),
            'nama_alat'    => post('nama_alat'),
            'stok'         => post('stok'),
            'kondisi_alat' => post('kondisi_alat', 'Baik'),
        );
    }

    private function validasi(array $in, $dipinjam = 0)
    {
        $err = array();
        if (!KategoriModel::find((int)$in['id_kategori'])) {
            $err['id_kategori'] = 'Pilih kategori.';
        }
        if ($in['nama_alat'] === '') {
            $err['nama_alat'] = 'Nama alat wajib diisi.';
        } elseif (mb_strlen($in['nama_alat']) > 100) {
            $err['nama_alat'] = 'Nama alat maksimal 100 karakter.';
        }
        if (!ctype_digit($in['stok']) || (int)$in['stok'] > 100000) {
            $err['stok'] = 'Stok harus bilangan bulat 0 atau lebih.';
        } elseif ((int)$in['stok'] < $dipinjam) {
            $err['stok'] = 'Stok tidak boleh lebih kecil dari jumlah yang sedang dipinjam (' . $dipinjam . ').';
        }
        if (!in_array($in['kondisi_alat'], AlatModel::$kondisi, true)) {
            $err['kondisi_alat'] = 'Pilih kondisi yang valid.';
        }
        return $err;
    }

    private function formData($mode, $row, $errors)
    {
        return array(
            'title'    => $mode === 'create' ? 'Tambah Alat' : 'Edit Alat',
            'mode'     => $mode,
            'row'      => $row,
            'errors'   => $errors,
            'kategori' => KategoriModel::all(),
        );
    }

    public function indexAction()
    {
        $f = array('q' => qs('q'), 'id_kategori' => (int)qs('id_kategori'), 'kondisi' => qs('kondisi'));
        list($rows, $pager) = AlatModel::search($f, $this->page(), $this->perPage());
        $this->render('alat/index', array(
            'title'    => 'Data Alat',
            'subtitle' => 'Stok, kondisi, dan ketersediaan alat',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
            'kategori' => KategoriModel::all(),
        ));
    }

    public function katalogAction()
    {
        $f = array('q' => qs('q'), 'id_kategori' => (int)qs('id_kategori'));
        list($rows) = AlatModel::search($f, 1, 0);
        $rows = array_values(array_filter($rows, function ($r) {
            return $r['status_kategori'] === 'Aktif';
        }));
        $this->render('alat/katalog', array(
            'title'    => 'Daftar Alat',
            'subtitle' => 'Alat yang tersedia untuk dipinjam',
            'rows'     => $rows,
            'f'        => $f,
            'kategori' => KategoriModel::active(),
        ));
    }

    public function createAction()
    {
        $this->render('alat/form', $this->formData('create', array(
            'id_kategori' => qs('id_kategori'), 'nama_alat' => '', 'stok' => '1', 'kondisi_alat' => 'Baik',
        ), array()));
    }

    public function storeAction()
    {
        $in = $this->input();
        $err = $this->validasi($in);
        if ($err) {
            $this->render('alat/form', $this->formData('create', $in, $err));
            return;
        }
        AlatModel::create($in['id_kategori'], $in['nama_alat'], $in['stok'], $in['kondisi_alat']);
        $this->log('Menambah data alat');
        flash('success', 'Alat "' . $in['nama_alat'] . '" ditambahkan.');
        redirect('alat/index');
    }

    public function editAction()
    {
        $row = AlatModel::find((int)qs('id'));
        if (!$row) {
            abort(404);
        }
        $this->render('alat/form', $this->formData('edit', $row, array()));
    }

    public function updateAction()
    {
        $id = (int)post('id_alat');
        $row = AlatModel::find($id);
        if (!$row) {
            abort(404);
        }
        $in = $this->input();
        $err = $this->validasi($in, $row['dipinjam']);
        if ($err) {
            $in['id_alat'] = $id;
            $in['dipinjam'] = $row['dipinjam'];
            $this->render('alat/form', $this->formData('edit', $in, $err));
            return;
        }
        AlatModel::update($id, $in['id_kategori'], $in['nama_alat'], $in['stok'], $in['kondisi_alat']);
        $this->log('Mengubah data alat');
        flash('success', 'Data alat diperbarui.');
        redirect('alat/index');
    }

    public function deleteAction()
    {
        $id = (int)qs('id');
        $row = AlatModel::find($id);
        if (!$row) {
            flash('error', 'Alat tidak ditemukan.');
        } elseif (AlatModel::isUsed($id)) {
            flash('error', '"' . $row['nama_alat'] . '" sudah pernah dipinjam sehingga tidak dihapus agar riwayat tetap utuh. Set stok 0 atau kondisi Rusak Berat bila tidak ingin dipinjam lagi.');
        } else {
            AlatModel::delete($id);
            $this->log('Menghapus data alat');
            flash('success', 'Alat dihapus.');
        }
        redirect('alat/index');
    }
}
