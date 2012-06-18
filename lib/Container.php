<?php
/**
 * @package Container
 */
namespace Di;

class Container
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    private $sigles = array();

    private $overloaded = array();

    /**
     * @param ConfigurationInterface $configuration
     * @param string $environment
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $serviceKey
     * @return object
     */
    public function getService($serviceKey)
    {
        $serviceConf = $this->configuration->getServiceConfiguration($serviceKey);

        // if instance has to be single or is overloaded, take it from instance cache
        if (in_array($serviceKey, $this->overloaded) || $serviceConf->isSingle()) {
            if (!isset($this->sigles[$serviceKey])) {
                $this->sigles[$serviceKey] = $this->buildService($serviceConf);
            }

            return $this->sigles[$serviceKey];
        }

        return $this->buildService($serviceConf);
    }

    /**
     * Set overloaded service (eg. mock)
     * @param $serviceKey
     * @param $instance Service instance
     */
    public function setService($serviceKey, $instance)
    {
        if (!in_array($serviceKey, $this->overloaded)) {
            $this->overloaded[] = $serviceKey;
        }

        $this->sigles[$serviceKey] = $instance;
    }

    /**
     * @param string $propertyKey
     * @return string
     */
    public function getProperty($propertyKey)
    {
        return $this->configuration->getProperty($propertyKey);
    }

    /**
     * @param Service_Configuration $serviceConf
     * @return object
     * @throws \InvalidArgumentException
     */
    public function buildService(Service_Configuration $serviceConf)
    {
        if (!$serviceConf->getClass()) {
            throw new \InvalidArgumentException(
                "No class defined for service: " .$serviceConf->getServiceKey());
        }

        $params = array();
        if ($serviceConf->hasParameters()) {
            foreach ($serviceConf->getParams() as $paramKey) {
                if (strpos($paramKey, "&") !== false) {
                    $params[] = $this->getService(substr($paramKey, 1));
                } elseif (strpos($paramKey, "@") !== false) {
                    $params[] = $this->getProperty(substr($paramKey, 1));
                } else {
                    $params[] = $paramKey;
                }
            }
        }

        return $this->buildInstance($serviceConf->getClass(), $params);

    }

    /**
     * @param string $className
     * @param array $params
     * @return object
     */
    private function buildInstance($className, $params)
    {
        // Hack to avoid Reflection in most common use cases
        switch (count($params)) {
            case 0:
                return new $className();
            case 1:
                return new $className($params[0]);
            case 2:
                return new $className($params[0], $params[1]);
            case 3:
                return new $className($params[0], $params[1], $params[2]);
            default:
                $reflection = new \ReflectionClass($className);
                return $reflection->newInstanceArgs($params);
        }
    }
}