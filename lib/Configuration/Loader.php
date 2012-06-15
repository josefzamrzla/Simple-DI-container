<?php
/**
 * @package Configuration_Loader
 */
namespace Di;

interface Configuration_Loader
{
    /**
     * @param string $serviceKey
     * @param string $environment
     * @return string
     */
    public function loadClass($serviceKey, $environment = null);

    /**
     * @param string $serviceKey
     * @param string $environment
     * @return bool
     */
    public function loadIsSingle($serviceKey, $environment = null);

    /**
     * @param string $serviceKey
     * @param string $environment
     * @return array
     */
    public function loadParameters($serviceKey, $environment = null);

    /**
     * @param string $propertyKey
     * @param string $environment
     * @return string
     */
    public function loadProperty($propertyKey, $environment = null);

    /**
     * @return string
     */
    public function getDefaultEnvironment();
}