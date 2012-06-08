<?php
class Configuration_JsonLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration_JsonLoader
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new Configuration_JsonLoader();
    }

    public function tearDown()
    {
        $this->loader = null;
    }

    public function testDefaultEnvironmentName()
    {
        $this->assertEquals("default", $this->loader->getDefaultEnvironment());
        $this->assertEquals(array(), $this->loader->getKnownEnvironments());
    }

    public function testDefaultEnvironmentNameAfterLoad()
    {
        $this->loader->addConf('{"production":{"services":{"db":{}}}}');
        $this->assertEquals("production", $this->loader->getDefaultEnvironment());
        $this->assertEquals(array("production"), $this->loader->getKnownEnvironments());
    }

    public function testDefaultEnvironmentNameAfterLoadTwoEnvs()
    {
        $this->loader->addConf('{"production":{"services":{"db":{}}}, "testing":{"services":{"db":{}}}}');
        $this->assertEquals("production", $this->loader->getDefaultEnvironment());
        $this->assertEquals(array("production", "testing"), $this->loader->getKnownEnvironments());
    }
}