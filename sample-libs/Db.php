<?php
class Db
{
    private $name;
    private $pwd;

    public function __construct($name, $pwd)
    {
        $this->name = $name;
        $this->pwd = $pwd;
    }
}