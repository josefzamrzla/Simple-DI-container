<?php
class Service_ConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Di\Service_Configuration
     */
    private $conf;

    protected function setUp()
    {
        $this->conf = new \Di\Service_Configuration("key");
    }

    public function testGetServiceKey()
    {
        $this->assertEquals("key", $this->conf->getServiceKey());
    }

    public function testSetAndGetClass()
    {
        $this->assertNull($this->conf->getClass());
        $this->conf->setClass("someclass");
        $this->assertEquals("someclass", $this->conf->getClass());
    }

    public function testSetAndGetParams()
    {
        $this->assertFalse($this->conf->hasParameters());
        $this->assertSame(array(), $this->conf->getParams());

        $this->conf->setParams(array("param1", "param2"));

        $this->assertTrue($this->conf->hasParameters());
        $this->assertSame(array("param1", "param2"), $this->conf->getParams());
    }

    public function testIsSingle()
    {
        $this->assertFalse($this->conf->isSingle());
        $this->conf->setIsSingle(true);
        $this->assertTrue($this->conf->isSingle());
    }

    public function testAddParam()
    {
        $this->assertSame(array(), $this->conf->getParams());
        $this->assertFalse($this->conf->hasParameters());
        $this->conf->addParam("param1");
        $this->assertTrue($this->conf->hasParameters());
        $this->assertSame(array("param1"), $this->conf->getParams());
        $this->conf->addParam("param2");
        $this->assertTrue($this->conf->hasParameters());
        $this->assertSame(array("param1", "param2"), $this->conf->getParams());
        $this->conf->addParam("param1");
        $this->assertTrue($this->conf->hasParameters());
        $this->assertSame(array("param1", "param2"), $this->conf->getParams());
    }
}
