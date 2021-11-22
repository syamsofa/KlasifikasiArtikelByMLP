<?php

namespace ClassProject;

class Manajemen
{
    public $k;
    public $umur;
    const ALAMAT = 'Jalan Blora';
    public function __construct()
    {
        $this->k = 'jekrjekjrekjkejr';
    }
    public function __destruct()
    {
        print_r("DA");
    }
    public function getName()
    {
        return $this->k;
    }
    public function setName(string $name)
    {
        $this->k = $name;
    }
    public function setUmur(string $umur)
    {
        $this->umur = $umur;
    }
}
