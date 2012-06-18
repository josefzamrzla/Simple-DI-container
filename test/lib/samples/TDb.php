<?php
class TDb
{
    public $name;
    public $pwd;

    public function __construct($name, $pwd)
    {
        $this->name = $name;
        $this->pwd = $pwd;
    }
}