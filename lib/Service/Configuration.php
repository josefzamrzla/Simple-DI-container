<?php
/**
 * @package Service_Configuration
 */
namespace Di;

class Service_Configuration implements Service_ConfigurationInterface
{
    private $class = null;
    private $params = array();
    private $single = false;
    private $serviceKey = null;

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
        if (!in_array($param, $this->params)) {
            $this->params[] = $param;
        }
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