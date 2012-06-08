<?php
require_once "autoload.php";

$loader = new Configuration_JsonLoader();
$loader->addConf(file_get_contents("conf/di.conf.json"));

var_dump($loader->getDefaultEnvironment());

/*$builder = new Configuration_Builder($loader, ENV);

$container = new Container(new Configuration($builder));*/

