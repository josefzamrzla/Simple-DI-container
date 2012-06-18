Simple dependency injection container
=====================================

Lightweight implementation of configurable dependency injection container.

Configuration
-------------

Configuration supports multiple environments including it's inheritance. Each environment section has 2 sections: `services`
and `properties`. Section `services` keeps configuration of instantiable services (classes), section `properties` can be used
for named constants.



``` json
{
    "production":{
        "services":{
            "db":{
                "class":"Db",
                "single":true,
                "parameters":[
                    "@dbname",
                    "@dbpwd"
                ]
            },
            "logger":{
                "class":"Logger"
            },
            "somemodel":{
                "class":"SomeModel",
                "parameters":[
                    "&db",
                    "&logger",
                    "someval"
                ]
            }
        },
        "properties":{
            "dbname":"pepan",
            "dbpwd":"heslo"
        }
    },
    "testing : production":{

        "properties":{
            "dbpwd":"testing_heslo"
        }
    }

}
```

Usage
-----

``` php
$loader = new Di\Configuration_JsonLoader();
$loader->addConf(file_get_contents("pathToConfigurationFile"));

$builder = new Di\Configuration_Builder($loader, $environment);

$container = new Di\Container(new Di\Configuration($builder));

$container->getService("someServiceKey");
```