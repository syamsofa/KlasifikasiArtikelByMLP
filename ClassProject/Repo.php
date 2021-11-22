<?php

namespace ClassProject;

class Repo
{
    public $folderArtikel;

    public function __construct(string $fdArtikel)
    {
        $this->folderArtikel = $fdArtikel;
    }
}
