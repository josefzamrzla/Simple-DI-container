<?php
$loader = new Configuration_JsonLoader();
$loader->addFile("conf/di.conf.json");

$builder = new Configuration_Builder($loader, ENV);

$container = new Container(new Configuration($builder));

