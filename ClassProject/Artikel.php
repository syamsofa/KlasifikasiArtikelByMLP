<?php

namespace ClassProject;

class Artikel
{
    const ROOT_URL = 'https://detik.com';
    public $judulArtikel;
    public $kontenArtikel;
    public $kontenArtikelClean;
    public $urlArtikel;

    public  function __construct()
    {
    }
    public function getJudulArtikel()
    {
        return  $this::ROOT_URL . $this->judulArtikel;
    }
    public function setKontenArtikel(string $url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        // var_dump($resp);

        $this->kontenArtikel = $resp;
    }
    public function setUrlArtikel(string $urArtikel)
    {
        $this->urlArtikel = $urArtikel;
    }
    public function setJudulArtikel(string $judArtikel)
    {
        $this->judulArtikel = $judArtikel;
    }
    public function setKontenArtikelClean($konArtikel)
    {
        $this->kontenArtikelClean = $konArtikel;
    }
}
