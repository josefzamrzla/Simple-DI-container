<?php
class ConfigurationTest extends PHPUnit_Framework_TestCase
{

    public function testGetServiceConfiguration()
    {
        $builder = $this->getMock("Di\\Configuration_Builder", array(), array($this->getMock("Di\\Configuration_Loader")));

        $conf = new Di\Configuration($builder);
        //$this->assertInstanceOf("Di\\Configuration", $conf);

    }

    //getProperty
}