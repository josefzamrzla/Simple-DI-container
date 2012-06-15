<?php
/**
 * @package Service_Configuration
 */
namespace Di;

interface Service_ConfigurationInterface
{
    /**
     * @param string $serviceKey
     */
    public function __construct($serviceKey);

    /**
     * @return string
     */
    public function getServiceKey();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @param string $class
     */
    public function setClass($class);

    /**
     * @return bool
     */
    public function hasParameters();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @param string $param
     */
    public function addParam($param);

    /**
     * @return bool
     */
    public function isSingle();

    /**
     * @param bool $isSingle
     */
    public function setIsSingle($isSingle);
}