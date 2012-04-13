<?php
/**
 * @package Container
 */
class Container
{
    /**
     * @var Configuration
     */
    private $configuration;

    private $sigles = array();

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $serviceKey
     * @return object
     */
    public function getService($serviceKey)
    {
        $serviceConf =
            $this->configuration->getServiceConfiguration($serviceKey);

        if ($serviceConf->isSingle()) {
            if (!isset($this->sigles[$serviceKey])) {
                $this->sigles[$serviceKey] =
                    $this->buildService($serviceConf);
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
     * @throws InvalidArgumentException
     */
    public function buildService(Service_Configuration $serviceConf)
    {
        if (!$serviceConf->getClass()) {
            throw new InvalidArgumentException(
                "No class defined for service: " .
                $serviceConf->getServiceKey());
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

        $reflection = new ReflectionClass($serviceConf->getClass());
        return $reflection->newInstanceArgs($params);
    }
}