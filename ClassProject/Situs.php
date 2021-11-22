<?php

namespace ClassProject;

class Situs
{
    public  $urlSitus;
    public function getSitus():string
    {
        return $this->urlSitus;
    }
    public function __construct(string $situs)
    {
        $this->urlSitus = $situs;
    }
}
