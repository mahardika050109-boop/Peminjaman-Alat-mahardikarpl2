<?php
class LogController extends Controller
{
    protected $access = array('*' => array('admin'));
    protected $postOnly = array('delete', 'purge');

    public function indexAction()
    {
        $f = array('q' => qs('q'), 'id_user' => (int)qs('id_user'), 'from' => qs_date('from'), 'to' => qs_date('to'));
        list($rows, $pager) = LogModel::search($f, $this->page(), 20);
        $this->render('log/index', array(
            'title'    => 'Log Aktivitas',
            'subtitle' => 'Riwayat aktivitas seluruh pengguna',
            'rows'     => $rows,
            'pager'    => $pager,
            'f'        => $f,
            'users'    => LogModel::users(),
            'total'    => LogModel::count(),
        ));
    }

    public function deleteAction()
    {
        LogModel::delete((int)qs('id'));
        flash('success', 'Catatan log dihapus.');
        redirect('log/index', array_filter(array('page' => qs('page'), 'q' => qs('q'))));
    }

    public function purgeAction()
    {
        $days = (int)post('hari');
        if (!in_array($days, array(30, 90, 180, 365), true)) {
            flash('error', 'Pilih rentang waktu yang valid.');
            redirect('log/index');
        }
        $n = LogModel::purgeOlderThan($days);
        $this->log('Membersihkan log lebih dari ' . $days . ' hari');
        flash('success', $n . ' catatan log yang lebih lama dari ' . $days . ' hari dihapus.');
        redirect('log/index');
    }
}
