<?php


namespace ClassProject;

abstract class Pelaksana
{
    public $Out;
    function setOut(int $var = null)
    {
        $this->Out = $var;
    }
    function getOut()
    {
        return $this->Out;
    }
}
