<?php

namespace ClassProject;

class Input
{
    public $pageAwal;
    public $pageAkhir;
    public $kataKunci;
    public $stringQuery1;
    public $stringQuery2;
    public function __construct(int $pgAwal, int $pgAkhir, string $ktKunci, string $stQuery1, string $stQuery2)
    {
        $this->pageAwal = $pgAwal;
        $this->pageAkhir = $pgAkhir;
        $this->kataKunci = $ktKunci;
        $this->stringQuery1 = $stQuery1;
        $this->stringQuery2 = $stQuery2;
    }
}
