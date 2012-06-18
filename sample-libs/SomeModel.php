<?php
class SomeModel
{
    public $db;
    public $log;
    public $val;

    public function __construct(Db $db, Logger $log, $val)
    {
        $this->db = $db;
        $this->log = $log;
        $this->val = $val;
    }
}