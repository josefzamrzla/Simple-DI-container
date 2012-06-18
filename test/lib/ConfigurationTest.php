<?php
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testGetServiceConfiguration()
    {
        // test only whether Configuration doesn't distort service conf
        $expected = new \Di\Service_Configuration("db");
        $expected->setClass("Db");
        $expected->setIsSingle(true);
        $expected->setParams(array("p1", "p2"));

        $builder = $this->getMock("Di\\Configuration_Builder", array(), array($this->getMock("Di\\Configuration_Loader")));
        $builder->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("db"))
            ->will($this->returnValue($expected));

        $conf = new Di\Configuration($builder);
        $this->assertSame($expected, $conf->getServiceConfiguration("db"));
    }

    public function testGetProperty()
    {
        // test only whether Configuration doesn't distort properties
        $builder = $this->getMock("Di\\Configuration_Builder", array(), array($this->getMock("Di\\Configuration_Loader")));

        $builder->expects($this->once())
            ->method("getProperty")
            ->with($this->equalTo("dbname"))
            ->will($this->returnValue("database_name"));

        $conf = new Di\Configuration($builder);
        $this->assertEquals("database_name", $conf->getProperty("dbname"));
    }
}