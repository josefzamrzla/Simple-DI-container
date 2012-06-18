<?php
class Configuration_BuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuildAndGetServiceConfiguration()
    {
        $expected = new \Di\Service_Configuration("db");
        $expected->setClass("Db");
        $expected->setIsSingle(true);
        $expected->setParams(array("dbname", "dbpasswd"));

        $loader = $this->getMock("Di\\Configuration_Loader");
        $loader->expects($this->exactly(2))
            ->method("loadClass")
            ->with($this->equalTo("db"), $this->equalTo("default"))
            ->will($this->returnValue("Db"));

        $loader->expects($this->exactly(2))
            ->method("loadIsSingle")
            ->with($this->equalTo("db"), $this->equalTo("default"))
            ->will($this->returnValue(true));

        $loader->expects($this->exactly(2))
            ->method("loadParameters")
            ->with($this->equalTo("db"), $this->equalTo("default"))
            ->will($this->returnValue(array("dbname", "dbpasswd")));

        $builder = new \Di\Configuration_Builder($loader, "default");
        $this->assertEquals($expected, $builder->buildServiceConfiguration("db"));
        $this->assertEquals($expected, $builder->getServiceConfiguration("db"));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBuildWithoutClassName()
    {
        $loader = $this->getMock("Di\\Configuration_Loader");
        $loader->expects($this->once())
            ->method("loadClass")
            ->with($this->equalTo("db"), $this->equalTo("default"))
            ->will($this->returnValue(null));

        $builder = new \Di\Configuration_Builder($loader, "default");
        $this->assertEquals(null, $builder->buildServiceConfiguration("db"));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetWithoutClassName()
    {
        $loader = $this->getMock("Di\\Configuration_Loader");
        $loader->expects($this->once())
            ->method("loadClass")
            ->with($this->equalTo("db"), $this->equalTo("default"))
            ->will($this->returnValue(null));

        $builder = new \Di\Configuration_Builder($loader, "default");
        $builder->getServiceConfiguration("db");
    }

    public function testGetProperty()
    {
        $loader = $this->getMock("Di\\Configuration_Loader");
        $loader->expects($this->once())
            ->method("loadProperty")
            ->with($this->equalTo("dbname"), $this->equalTo("default"))
            ->will($this->returnValue("database_name"));

        $builder = new \Di\Configuration_Builder($loader, "default");
        $this->assertEquals("database_name", $builder->getProperty("dbname"));
    }
    // getServiceConfiguration
    // getProperty
    // buildServiceConfiguration
}