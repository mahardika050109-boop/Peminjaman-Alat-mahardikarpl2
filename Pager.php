<?php
class Pager
{
    public $page = 1;
    public $perPage = 15;
    public $total = 0;
    public $pages = 1;
    public $offset = 0;

    public static function make($total, $page, $perPage)
    {
        $p = new Pager();
        $p->total = (int)$total;
        $p->perPage = max(1, (int)$perPage);
        $p->pages = max(1, (int)ceil($p->total / $p->perPage));
        $p->page = min(max(1, (int)$page), $p->pages);
        $p->offset = ($p->page - 1) * $p->perPage;
        return $p;
    }

    public function from()
    {
        return $this->total === 0 ? 0 : $this->offset + 1;
    }

    public function to()
    {
        return min($this->total, $this->offset + $this->perPage);
    }
}
