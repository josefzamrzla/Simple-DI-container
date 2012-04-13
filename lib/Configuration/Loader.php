<?php
/**
 * @package Configuration_Loader
 */
interface Configuration_Loader
{
    /**
     * @param string $serviceKey
     * @return string
     */
    public function loadClass($serviceKey);

    /**
     * @param string $serviceKey
     * @return bool
     */
    public function loadIsSingle($serviceKey);

    /**
     * @param string $serviceKey
     * @return array
     */
    public function loadParameters($serviceKey);

    /**
     * @param string $propertyKey
     * @return string
     */
    public function loadProperty($propertyKey);
}