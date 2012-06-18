<?php
namespace Di;

interface ConfigurationInterface
{
    /**
     * @param $serviceKey
     * @return Service_Configuration
     */
    public function getServiceConfiguration($serviceKey);

    /**
     * @param $propertyKey
     * @return string
     */
    public function getProperty($propertyKey);
}