<?php
function __autoload($class)
{
    if (file_exists(__DIR__."/lib/" . str_replace("_", "/", $class) . ".php")) {
        require_once __DIR__."/lib/" . str_replace("_", "/", $class) . ".php";
    } elseif (file_exists(__DIR__."/sample-libs/" . str_replace("_", "/", $class) . ".php")) {
        require_once __DIR__."/sample-libs/" . str_replace("_", "/", $class) . ".php";
    }
}

$loader = new Configuration_JsonLoader();
$loader->addFile("conf/di.conf.json");

$builder = new Configuration_Builder($loader);

$container = new Container(new Configuration($builder));

var_dump($container->getService("somemodel"));

