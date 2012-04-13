<?php
class SomeModel
{
    private $db;
    private $log;
    private $val;

    public function __construct(Db $db, Logger $log, $val)
    {
        $this->db = $db;
        $this->log = $log;
        $this->val = $val;
    }
}