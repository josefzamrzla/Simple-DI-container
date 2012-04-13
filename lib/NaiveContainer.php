<?php
class NaiveContainer
{
    public function getDbName()
    {
        return "dbname";
    }

    public function getDbPasswd()
    {
        return "passwd";
    }

    /**
     * @return Db
     */
    public function getDb()
    {
        return new Db($this->getDbName(), $this->getDbPasswd());
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return new Logger();
    }

    public function getSomeModelParam()
    {
        return "third param";
    }

    /**
     * @return SomeModel
     */
    public function getSomeModel()
    {
        return new SomeModel($this->getDb(), $this->getLogger(), $this->getSomeModelParam());
    }
}