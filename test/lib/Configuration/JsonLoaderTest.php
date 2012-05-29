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
    }
}