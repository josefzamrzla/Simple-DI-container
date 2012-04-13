<?php
/**
 * @package Configuration
 */
class Service_Configuration
{
    private $class;
    private $params = array();
    private $single = false;
    private $serviceKey;

    /**
     * @param string $serviceKey
     */
    public function __construct($serviceKey)
    {
        $this->serviceKey = $serviceKey;
    }

    /**
     * @return string
     */
    public function getServiceKey()
    {
        return $this->serviceKey;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return bool
     */
    public function hasParameters()
    {
        return (count($this->params) > 0);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param string $param
     */
    public function addParam($param)
    {
        $this->params[] = $param;
    }

    /**
     * @return bool
     */
    public function isSingle()
    {
        return $this->single;
    }

    /**
     * @param bool $isSingle
     */
    public function setIsSingle($isSingle)
    {
        $this->single = $isSingle;
    }
}