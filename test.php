<?php

require './vendor/autoload.php';

$fileSystemDriver = new \Counters\Persistence\FileSystemPersistenceDriver(__DIR__ . '/data');
$singletonManager = new \Counters\Management\SingletonManager();

$manager = new \Counters\Management\CounterManager($singletonManager, $fileSystemDriver);

$uuid = "862c0700-f771-11ea-adc1-0242ac120002";
$counter = \Counters\Core\Counter::getInstance($uuid, $manager);
// $counter->increment();
echo $counter->getValue() . PHP_EOL;