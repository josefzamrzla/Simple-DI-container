<?php
class TModel
{
    public $db;
    public $val;

    public function __construct(TDb $db, $val)
    {
        $this->db = $db;
        $this->val = $val;
    }
}