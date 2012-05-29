<?php
/**
 * @package Configuration_Builder
 */
class Configuration_Builder
{
    /**
     * @var Configuration_Loader
     */
    private $loader;

    private $environment;

    /**
     * @param Configuration_Loader $loader
     */
    public function __construct(Configuration_Loader $loader, $environment = null)
    {
        $this->loader = $loader;

        if ($environment) {
            $this->environment = $environment;
        }
    }

    /**
     * @param $serviceKey
     * @return Service_Configuration
     */
    public function getServiceConfiguration($serviceKey)
    {
        return $this->buildServiceConfiguration($serviceKey);
    }

    /**
     * @param $propertyKey
     * @return string
     */
    public function getProperty($propertyKey)
    {
        return $this->loader->loadProperty($propertyKey);
    }

    /**
     * @param $serviceKey
     * @return Service_Configuration
     * @throws InvalidArgumentException
     */
    public function buildServiceConfiguration($serviceKey)
    {
        $serviceConf = new Service_Configuration($serviceKey);

        if (strlen($class = $this->loader->loadClass($serviceKey))) {
            $serviceConf->setClass($class);
        } else {
            throw new InvalidArgumentException("No class defined for service: " . $serviceKey);
        }

        $serviceConf->setIsSingle($this->loader->loadIsSingle($serviceKey));

        foreach ($this->loader->loadParameters($serviceKey) as $param) {
            $serviceConf->addParam($param);
        }

        return $serviceConf;
    }
}