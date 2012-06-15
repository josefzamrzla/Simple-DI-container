<?php
/**
 * @package Configuration_Loader
 */
namespace Di;

class Configuration_JsonLoader implements Configuration_Loader
{
    const DEFAULT_ENVIRONMENT_NAME = "default";

    private $serviceConf = array();
    private $properties = array();
    private $sums = array();
    private $defaultEnvironment = null;

    /**
     * @param string $content
     * @return Configuration_JsonLoader
     */
    public function addConf($content)
    {
        $checksum = md5($content);
        if (!in_array($checksum, $this->sums)) {
            $this->sums[] = $checksum;
            $this->mergeConfiguration($content);
        }

        return $this;
    }

    /**
     * @param string $serviceKey
     * @param string $environment
     * @return string|bool
     */
    public function loadClass($serviceKey, $environment = null)
    {
        $env = $this->getEnvironment($environment);

        if (isset($this->serviceConf[$env][$serviceKey]['class']))
            return $this->serviceConf[$env][$serviceKey]['class'];

        return false;
    }

    /**
     * @param string $serviceKey
     * @param string $environment
     * @return bool
     */
    public function loadIsSingle($serviceKey, $environment = null)
    {
        $env = $this->getEnvironment($environment);

        if (isset($this->serviceConf[$env][$serviceKey]['single']))
            return $this->serviceConf[$env][$serviceKey]['single'];

        return false;
    }

    /**
     * @param string $serviceKey
     * @param string $environment
     * @return array
     */
    public function loadParameters($serviceKey, $environment = null)
    {
        $env = $this->getEnvironment($environment);

        if (isset($this->serviceConf[$env][$serviceKey]['parameters']))
            return $this->serviceConf[$env][$serviceKey]['parameters'];

        return array();
    }

    /**
     * @param string $propertyKey
     * @param string $environment
     * @return string|bool
     */
    public function loadProperty($propertyKey, $environment = null)
    {
        $env = $this->getEnvironment($environment);

        if (isset($this->properties[$env][$propertyKey]))
            return $this->properties[$env][$propertyKey];

        return false;
    }

    /**
     * @return string
     */
    public function getDefaultEnvironment()
    {
        if ($this->defaultEnvironment === null) {

            if (count($this->serviceConf) && count($envs = array_keys($this->serviceConf))) {
                $this->defaultEnvironment = array_shift($envs);
            } elseif (count($this->properties) && count($envs = array_keys($this->properties))) {
                $this->defaultEnvironment = array_shift($envs);
            } else {
                $this->defaultEnvironment = self::DEFAULT_ENVIRONMENT_NAME;
            }
        }

        return $this->defaultEnvironment;
    }

    /**
     * @return array
     */
    public function getKnownEnvironments()
    {
        return array_unique(array_merge(array_keys($this->serviceConf), array_keys($this->properties)));
    }

    /**
     * @param string $environment
     * @return string
     */
    private function getEnvironment($environment)
    {
        return is_null($environment)? $this->getDefaultEnvironment() : $environment;
    }

    /**
     * @param string $content
     */
    private function mergeConfiguration($content)
    {
        $json = json_decode($content, true);

        if (isset($json['services']) || isset($json['properties'])) {
            $this->mergeEnvironmentParts(self::DEFAULT_ENVIRONMENT_NAME, $json);
        } else {
            foreach($json as $environment => $environmentJson) {
                if (strpos($environment, ":") !== false) {
                    $environmentParts = explode(":", $environment);
                    $parentEnv = trim($environmentParts[1]);
                    $environment = trim($environmentParts[0]);

                    if (isset($json[$parentEnv])) {
                        // if env. inherits settings, load parent settings first

                        $this->mergeEnvironmentParts($environment, $json[$parentEnv]);
                    }
                }

                $this->mergeEnvironmentParts($environment, $environmentJson);
            }
        }
    }

    private function mergeEnvironmentParts($environment, array $json)
    {
        if (isset($json['services'])) {
            $this->mergeServices($environment, $json);
        }

        if (isset($json['properties'])) {
            $this->mergeProperties($environment, $json);
        }
    }

    /**
     * @param array $json
     */
    private function mergeServices($environment, array $json)
    {
        foreach ($json['services'] as $serviceKey => $serviceConf) {
            $this->serviceConf[$environment][$serviceKey] = $serviceConf;
        }
    }

    /**
     * @param array $json
     */
    private function mergeProperties($environment, array $json)
    {
        foreach ($json['properties'] as $propertyKey => $property) {
            $this->properties[$environment][$propertyKey] = $property;
        }
    }
}