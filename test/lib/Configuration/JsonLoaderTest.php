<?php
class Configuration_JsonLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Di\Configuration_JsonLoader
     */
    private $loader;

    public function setUp()
    {
        $this->loader = new Di\Configuration_JsonLoader();
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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonBadEnclosing()
    {
        // !!! json names MUST be enclosed by double quotes !!!
        $json = "{'a':'1'}";
        $this->loader->addConf($json);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonEmptyString()
    {
        $json = "";
        $this->loader->addConf($json);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonSyntaxError()
    {
        $json = '{"a":"1",}';
        $this->loader->addConf($json);
    }

    public function testDefaultEnvironmentNameAfterLoad()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{}
                }
            }
        }';
        $this->loader->addConf($json);
        $this->assertEquals("production", $this->loader->getDefaultEnvironment());
        $this->assertEquals(array("production"), $this->loader->getKnownEnvironments());
    }

    public function testDefaultEnvironmentNameAfterLoadTwoEnvs()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{}
                }
            },

            "testing":{
                "services":{
                    "db":{}
                }
            }
        }';
        $this->loader->addConf($json);
        $this->assertEquals("production", $this->loader->getDefaultEnvironment());
        $this->assertEquals(array("production", "testing"), $this->loader->getKnownEnvironments());
    }

    public function testLoadProperty()
    {
        $json = '{
            "production":{
                "services":{},
                "properties":{
                    "propName": "foo",
                    "propOvName": "productionValue"
                }
            },

            "testing : production":{
                "services":{},
                "properties":{
                    "propOvName": "testingValue"
                }
            }
        }';

        $this->loader->addConf($json);
        $this->assertEquals("foo", $this->loader->loadProperty("propName"));
        $this->assertEquals("foo", $this->loader->loadProperty("propName", "production"));
        $this->assertEquals("foo", $this->loader->loadProperty("propName", "testing"));

        $this->assertEquals("productionValue", $this->loader->loadProperty("propOvName"));
        $this->assertEquals("productionValue", $this->loader->loadProperty("propOvName", "production"));
        $this->assertEquals("testingValue", $this->loader->loadProperty("propOvName", "testing"));
    }

    public function testLoadPropertyNonexistentProperty()
    {
        $json = '{
            "production":{
                "services":{},
                "properties":{
                    "propName": "foo",
                    "propOvName": "productionValue"
                }
            }
        }';

        $this->loader->addConf($json);

        $this->assertNull($this->loader->loadProperty("___invalidName____"));
    }

    public function testLoadPropertyNonexistentEnv()
    {
        $json = '{
            "production":{
                "services":{},
                "properties":{
                    "propName": "foo",
                    "propOvName": "productionValue"
                }
            }
        }';

        $this->loader->addConf($json);

        $this->assertNull($this->loader->loadProperty("propName", "__invalidEnv__"));
    }

    public function testLoadClass()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{
                        "class":"Db"
                    }
                }
            }
        }';

        $this->loader->addConf($json);

        $this->assertEquals("Db", $this->loader->loadClass("db"));

        try {
            $this->assertEquals("Db", $this->loader->loadClass("__invalidName"));
            $this->fail("Expected InvalidArgumentException");
        } catch(\InvalidArgumentException $e) {}

        try {
            $this->assertEquals("Db", $this->loader->loadClass("db", "__invalidEnv"));
            $this->fail("Expected InvalidArgumentException");
        } catch(\InvalidArgumentException $e) {}
    }

    public function testLoadClassSimplyOverloadedEnv()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{
                        "class":"Db"
                    }
                }
            },

            "testing : production":{}
        }';

        $this->loader->addConf($json);
        $this->assertEquals("Db", $this->loader->loadClass("db"));
        $this->assertEquals("Db", $this->loader->loadClass("db", "production"));
        $this->assertEquals("Db", $this->loader->loadClass("db", "testing"));
    }

    public function testLoadClassOverloadedEnvWithChanges()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{
                        "class":"Db"
                    }
                }
            },

            "testing : production":{
                "services":{
                    "db":{
                        "class":"DbTest"
                    }
                }
            }
        }';

        $this->loader->addConf($json);
        $this->assertEquals("Db", $this->loader->loadClass("db"));
        $this->assertEquals("Db", $this->loader->loadClass("db", "production"));
        $this->assertEquals("DbTest", $this->loader->loadClass("db", "testing"));
    }

    public function testLoadClassOverloadedWithoutClass()
    {
        $json = '{
            "production":{
                "services":{
                    "db":{
                        "class":"Db"
                    }
                }
            },

            "testing : production":{
                "services":{
                    "db":{

                    }
                }
            }
        }';

        $this->loader->addConf($json);
        $this->assertEquals("Db", $this->loader->loadClass("db"));
        $this->assertEquals("Db", $this->loader->loadClass("db", "production"));
        $this->assertEquals("Db", $this->loader->loadClass("db", "testing"));
    }

    // loadClass
    // loadIsSingle
    // loadParameters

}