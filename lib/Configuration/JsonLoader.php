<?php
/**
 * @package Configuration_Loader
 */
class Configuration_JsonLoader implements  Configuration_Loader
{
    private $serviceConf = array();
    private $properties = array();
    private $files = array();

    /**
     * @param string $file
     * @return Configuration_JsonLoader
     */
    public function addFile($file)
    {
        if (file_exists($file) && !in_array($file, $this->files)) {
            $this->files[] = $file;
            $this->mergeConfiguration();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param $serviceKey
     * @return string|bool
     */
    public function loadClass($serviceKey)
    {
        if (isset($this->serviceConf[$serviceKey]['class']))
            return $this->serviceConf[$serviceKey]['class'];

        return false;
    }

    /**
     * @param $serviceKey
     * @return bool
     */
    public function loadIsSingle($serviceKey)
    {
        if (isset($this->serviceConf[$serviceKey]['single']))
            return $this->serviceConf[$serviceKey]['single'];

        return false;
    }

    /**
     * @param $serviceKey
     * @return array
     */
    public function loadParameters($serviceKey)
    {
        if (isset($this->serviceConf[$serviceKey]['parameters']))
            return $this->serviceConf[$serviceKey]['parameters'];

        return array();
    }

    /**
     * @param $propertyKey
     * @return string|bool
     */
    public function loadProperty($propertyKey)
    {
        if (isset($this->properties[$propertyKey]))
            return $this->properties[$propertyKey];

        return false;
    }

    private function mergeConfiguration()
    {
        foreach ($this->files as $file) {
            if (strlen($content = file_get_contents($file))) {
                $this->doMerge($content);
            }
        }
    }

    /**
     * @param string $content
     */
    private function doMerge($content)
    {
        $json = json_decode($content, true);

        if (isset($json['services'])) {
            $this->mergeServices($json);
        }

        if (isset($json['properties'])) {
            $this->mergeProperties($json);
        }
    }

    /**
     * @param array $json
     */
    private function mergeServices(array $json)
    {
        foreach ($json['services'] as $serviceKey => $serviceConf) {
            $this->serviceConf[$serviceKey] = $serviceConf;
        }
    }

    /**
     * @param array $json
     */
    private function mergeProperties(array $json)
    {
        foreach ($json['properties'] as $propertyKey => $property) {
            $this->properties[$propertyKey] = $property;
        }
    }
}