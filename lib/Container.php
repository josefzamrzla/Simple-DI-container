<?php
/**
 * @package Container
 */
namespace Di;

class Container
{
    /**
     * @var Configuration
     */
    private $configuration;

    private $sigles = array();

    private $environment;

    /**
     * @param Configuration $configuration
     * @param string $environment
     */
    public function __construct(Configuration $configuration, $environment = null)
    {
        $this->configuration = $configuration;

        if ($environment) {
            $this->environment = $environment;
        }
    }

    /**
     * @param string $serviceKey
     * @return object
     */
    public function getService($serviceKey)
    {
        $serviceConf = $this->configuration->getServiceConfiguration($serviceKey);

        if ($serviceConf->isSingle()) {
            if (!isset($this->sigles[$serviceKey])) {
                $this->sigles[$serviceKey] = $this->buildService($serviceConf);
            }

            return $this->sigles[$serviceKey];
        }

        return $this->buildService($serviceConf);
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