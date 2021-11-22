<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/ClassProject/Artikel.php';
require __DIR__ . '/ClassProject/Input.php';
require __DIR__ . '/ClassProject/Repo.php';
require __DIR__ . '/ClassProject/Cleaner.php';
require __DIR__ . '/ClassProject/BarisPencarian.php';
require __DIR__ . '/simplehtmldom_1_9_1/simple_html_dom.php';

use ClassProject\BarisPencarian;
use ClassProject\Artikel;
use ClassProject\Input;
use ClassProject\Repo;
use ClassProject\Cleaner;


$input = new Input(1, 100, "jawa tengah", "https://www.detik.com/search/searchall?", "&siteid=55&sortby=time&");
$artikel = new Artikel();
$repo = new Repo("FolderArtikel/");
$time_start = microtime(true);
$cleaner = new Cleaner();
$barisPencarian = new BarisPencarian();

for ($i = $input->pageAwal; $i <= $input->pageAkhir; $i++) {
    // https://www.detik.com/search/searchall?query=jawa%20tengah&siteid=66&sortby=time&page=2
    $artikel->setUrlArtikel($input->stringQuery1 . "query=" . $input->kataKunci . $input->stringQuery2 . "page=" . $i);
    $artikel->setKontenArtikel($artikel->urlArtikel);
    $cleaner->prosesCleaning($artikel->kontenArtikel);

    echo "\n URL :" . $artikel->urlArtikel . "\n";

    $barisPencarian->setNamaFile($repo->folderArtikel . "BarisPencarian" . $i . time() . ".html");
    if (!file_put_contents($barisPencarian->namaFile, $cleaner->outputClean)) {
    } else {
        echo " : Sukses Create File\n";
        $html = file_get_html($barisPencarian->namaFile);
        // echo $html;
        $repoRinci = new Repo("FolderArtikelRinci/");

        foreach ($html->find('article') as $row) //menemukan tag artikel 
        {
            $artikelRinci = new Artikel();
            $artikelRinci->setUrlArtikel($row->find('a', 0)->href);
            $artikelRinci->setKontenArtikel($artikelRinci->urlArtikel);

            echo $artikelRinci->urlArtikel;
            $barisPencarianRinci = new BarisPencarian();
            $cleanerRinci = new Cleaner();

            $cleanerRinci->prosesCleaning($artikelRinci->kontenArtikel);
            $barisPencarianRinci->setNamaFile($repoRinci->folderArtikel . "BarisPencarian" . $i . time() . ".html");
            // <div class="detail__body-text itp_bodycontent">
            file_put_contents($barisPencarianRinci->namaFile, $cleanerRinci->outputClean);

            $htmlRinci = file_get_html($barisPencarianRinci->namaFile);

            try{
                $artikelRinci->setJudulArtikel($htmlRinci->find('title', 0)->plaintext);
                $artikelRinci->setKontenArtikelClean($htmlRinci->find("div[id='detikdetailtext']", 0)->plaintext);
                echo "\nTITLE->" . $artikelRinci->judulArtikel." - ".$artikelRinci->urlArtikel;
                if($artikelRinci->kontenArtikelClean)
                file_put_contents("FolderArtikelRinciClean/Result".time().".txt", $artikelRinci->kontenArtikelClean);
    
    
            }
            catch(Throwable  $e) {
                echo 'Message: ' .$e->getMessage();

            }
        }
    }
}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start) / 60;

//execution time of the script
echo '\n <b>Total Execution Time:</b> \n\n\n\n\n' . $execution_time . ' Mins';
// if you get weird results, use number_format((float) $execution_time, 10) 
