<?php
/**
 * @package Configuration
 */
namespace Di;

class Configuration
{
    /**
     * @var Configuration_Builder
     */
    private $builder;

    private $servisesCache = array();
    private $propertiesCache = array();

    /**
     * @param Configuration_Builder $builder
     */
    public function __construct(Configuration_Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param $serviceKey
     * @return Service_Configuration
     */
    public function getServiceConfiguration($serviceKey)
    {
        if (!isset($this->servisesCache[$serviceKey])) {
            $this->servisesCache[$serviceKey] = $this->builder->getServiceConfiguration($serviceKey);
        }

        return $this->servisesCache[$serviceKey];
    }

    /**
     * @param $propertyKey
     * @return string
     */
    public function getProperty($propertyKey)
    {
        if (!isset($this->propertiesCache[$propertyKey])) {
            $this->propertiesCache[$propertyKey] = $this->builder->getProperty($propertyKey);
        }

        return $this->propertiesCache[$propertyKey];
    }
}