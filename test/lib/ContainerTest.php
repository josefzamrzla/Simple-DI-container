<?php
// sample libs to build with DIC
require_once "samples/TDb.php";
require_once "samples/TLogger.php";
require_once "samples/TModel.php";
require_once "samples/TOne.php";
require_once "samples/TTwo.php";
require_once "samples/TThree.php";
require_once "samples/TFour.php";
require_once "samples/TFive.php";

class ContainerTest extends PHPUnit_Framework_TestCase
{

    public function testBuildServiceWithoutParams()
    {
        $conf = new \Di\Service_Configuration("tlogger");
        $conf->setClass("TLogger");

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tlogger"))
            ->will($this->returnValue($conf));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tlogger");
        $this->assertInstanceOf("TLogger", $instance);
    }

    public function testBuildServiceWithNotSingleAttribute()
    {
        $conf = new \Di\Service_Configuration("tlogger");
        $conf->setClass("TLogger");

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->exactly(2))
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tlogger"))
            ->will($this->returnValue($conf));

        $dic = new Di\Container($confMock);
        // get first instance
        $first = $dic->getService("tlogger");
        // get second instance
        $second = $dic->getService("tlogger");

        $this->assertNotSame($first, $second);
    }

    public function testBuildServiceWithSingleAttribute()
    {
        $conf = new \Di\Service_Configuration("tlogger");
        $conf->setClass("TLogger");
        $conf->setIsSingle(true);

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->exactly(2))
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tlogger"))
            ->will($this->returnValue($conf));

        $dic = new Di\Container($confMock);
        // get first instance
        $first = $dic->getService("tlogger");
        // get second instance
        $second = $dic->getService("tlogger");

        $this->assertSame($first, $second);
    }

    public function testSetService()
    {
        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $dic = new Di\Container($confMock);

        $instance = new TLogger();
        $dic->setService("tlogger", $instance);

        $given = $dic->getService("tlogger");

        $this->assertSame($instance, $given);
    }

    public function testGetProperty()
    {
        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getProperty")
            ->with($this->equalTo("myproperty"))
            ->will($this->returnValue("propertyValue"));

        $dic = new Di\Container($confMock);
        $this->assertEquals("propertyValue", $dic->getProperty("myproperty"));
    }

    public function testGetServiceWithPlainTextParams()
    {
        $conf = new \Di\Service_Configuration("tdb");
        $conf->setClass("TDb");
        $conf->setParams(array("firstParam", "secondParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tdb"))
            ->will($this->returnValue($conf));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tdb");

        $this->assertInstanceOf("TDb", $instance);
        $this->assertEquals("firstParam", $instance->name);
        $this->assertEquals("secondParam", $instance->pwd);
    }

    public function testGetServiceWithPropertiesParams()
    {
        $conf = new \Di\Service_Configuration("tdb");
        $conf->setClass("TDb");
        $conf->setParams(array("@firstParam", "@secondParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tdb"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->exactly(2))
            ->method("getProperty")
            ->with(
                $this->logicalOr(
                    $this->equalTo("firstParam"),
                    $this->equalTo("secondParam")
                ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));


        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tdb");

        $this->assertInstanceOf("TDb", $instance);
        $this->assertEquals("firstPropertyValue", $instance->name);
        $this->assertEquals("secondPropertyValue", $instance->pwd);
    }

    public function testGetServiceWithPropertyAndAnotherService()
    {
        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->exactly(2))
            ->method("getServiceConfiguration")
            ->with(
            $this->logicalOr(
                $this->equalTo("tmodel"),
                $this->equalTo("tdb")
            ))
            ->will($this->returnCallback(array($this, "getServiceConfigurationMockCallback")));

        $confMock->expects($this->exactly(3))
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tmodel");

        $this->assertInstanceOf("TModel", $instance);
        $this->assertInstanceOf("TDb", $instance->db);
        $this->assertEquals("secondPropertyValue", $instance->val);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBuildServiceWithoutClassname()
    {
        $conf = new \Di\Service_Configuration("foo");
        $dic = new Di\Container($this->getMock("Di\\ConfigurationInterface"));
        $dic->buildService($conf);
    }

    public function testBuildServiceWithOneParam()
    {
        $conf = new \Di\Service_Configuration("tone");
        $conf->setClass("TOne");
        $conf->setParams(array("@firstParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tone"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->once())
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam"),
                $this->equalTo("thirdParam"),
                $this->equalTo("fourthParam"),
                $this->equalTo("fifthParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tone");

        $this->assertInstanceOf("TOne", $instance);
        $this->assertEquals("firstPropertyValue", $instance->one);
    }

    public function testBuildServiceWithTwoParams()
    {
        $conf = new \Di\Service_Configuration("ttwo");
        $conf->setClass("TTwo");
        $conf->setParams(array("@firstParam", "@secondParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("ttwo"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->exactly(2))
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam"),
                $this->equalTo("thirdParam"),
                $this->equalTo("fourthParam"),
                $this->equalTo("fifthParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("ttwo");

        $this->assertInstanceOf("TTwo", $instance);
        $this->assertEquals("firstPropertyValue", $instance->one);
        $this->assertEquals("secondPropertyValue", $instance->two);
    }

    public function testBuildServiceWithThreeParams()
    {
        $conf = new \Di\Service_Configuration("tthree");
        $conf->setClass("TThree");
        $conf->setParams(array("@firstParam", "@secondParam", "@thirdParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tthree"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->exactly(3))
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam"),
                $this->equalTo("thirdParam"),
                $this->equalTo("fourthParam"),
                $this->equalTo("fifthParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tthree");

        $this->assertInstanceOf("TThree", $instance);
        $this->assertEquals("firstPropertyValue", $instance->one);
        $this->assertEquals("secondPropertyValue", $instance->two);
        $this->assertEquals("thirdPropertyValue", $instance->three);
    }

    public function testBuildServiceWithFourParams()
    {
        $conf = new \Di\Service_Configuration("tfour");
        $conf->setClass("TFour");
        $conf->setParams(array("@firstParam", "@secondParam", "@thirdParam", "@fourthParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tfour"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->exactly(4))
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam"),
                $this->equalTo("thirdParam"),
                $this->equalTo("fourthParam"),
                $this->equalTo("fifthParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tfour");

        $this->assertInstanceOf("TFour", $instance);
        $this->assertEquals("firstPropertyValue", $instance->one);
        $this->assertEquals("secondPropertyValue", $instance->two);
        $this->assertEquals("thirdPropertyValue", $instance->three);
        $this->assertEquals("fourthPropertyValue", $instance->four);
    }

    public function testBuildServiceWithFiveParams()
    {
        $conf = new \Di\Service_Configuration("tfive");
        $conf->setClass("TFive");
        $conf->setParams(array("@firstParam", "@secondParam", "@thirdParam", "@fourthParam", "@fifthParam"));

        $confMock = $this->getMock("Di\\ConfigurationInterface");
        $confMock->expects($this->once())
            ->method("getServiceConfiguration")
            ->with($this->equalTo("tfive"))
            ->will($this->returnValue($conf));

        $confMock->expects($this->exactly(5))
            ->method("getProperty")
            ->with(
            $this->logicalOr(
                $this->equalTo("firstParam"),
                $this->equalTo("secondParam"),
                $this->equalTo("thirdParam"),
                $this->equalTo("fourthParam"),
                $this->equalTo("fifthParam")
            ))
            ->will($this->returnCallback(array($this, "getPropertyMockCallback")));

        $dic = new Di\Container($confMock);
        $instance = $dic->getService("tfive");

        $this->assertInstanceOf("TFive", $instance);
        $this->assertEquals("firstPropertyValue", $instance->one);
        $this->assertEquals("secondPropertyValue", $instance->two);
        $this->assertEquals("thirdPropertyValue", $instance->three);
        $this->assertEquals("fourthPropertyValue", $instance->four);
        $this->assertEquals("fifthPropertyValue", $instance->five);
    }

    /**
     * Helper callback for multiple call mocked method ConfigurationInterface::getProperty
     * @param string $propertyName
     * @return string|null
     */
    public function getPropertyMockCallback($propertyName)
    {
        $properties = array(
            "firstParam"  => "firstPropertyValue",
            "secondParam" => "secondPropertyValue",
            "thirdParam"  => "thirdPropertyValue",
            "fourthParam" => "fourthPropertyValue",
            "fifthParam"  => "fifthPropertyValue");

        return isset($properties[$propertyName])? $properties[$propertyName] : null;
    }

    /**
     * Helper callback for multiple call mocked method ConfigurationInterface::getServiceConfiguration
     * @param $serviceKey
     * @return Di\Service_Configuration|null
     */
    public function getServiceConfigurationMockCallback($serviceKey)
    {
        switch ($serviceKey) {
            case "tdb":
                $conf = new \Di\Service_Configuration("tdb");
                $conf->setClass("TDb");
                $conf->setParams(array("@firstParam", "@secondParam"));
                return $conf;
            case "tmodel":
                $conf = new \Di\Service_Configuration("tmodel");
                $conf->setClass("TModel");
                $conf->setParams(array("&tdb", "@secondParam"));
                return $conf;
        }

        return null;
    }
}