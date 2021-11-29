<?php


namespace ClassProject;

class Cleaner
{
    public $outputClean;
    public $stringProses;
    const REGEXTOREMOVE = [
        "/<img[^>]+\>/i",
        "/<h[1-6]>(.*?)<\/h[1-6]>/",
        '#<script(.*?)>(.*?)</script>#is'
    ];
    public function prosesCleaning($input):void
    {
        $this->stringProses = $input;
        foreach ($this::REGEXTOREMOVE as $regex) {
            $this->stringProses = preg_replace($regex, "", $this->stringProses);
        }
        $this->outputClean = $this->stringProses;
    }
}
