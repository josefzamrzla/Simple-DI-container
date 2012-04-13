<?php
class DummyContainer
{
    private $services = array();

    public function setService($serviceKey, $service)
    {
        $this->services[$serviceKey] = $service;
    }

    public function getService($serviceKey)
    {
        if (isset($this->services[$serviceKey])) {
            return $this->services[$serviceKey];
        }

        return null;
    }
}