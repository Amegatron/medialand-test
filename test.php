<?php

use Counters\Core\Counter;
use Counters\Management\CounterManager;
use Counters\Management\SingletonManager;
use Counters\Persistence\FileSystemPersistenceDriver;

require './vendor/autoload.php';

$fileSystemDriver = new FileSystemPersistenceDriver(__DIR__ . '/data');
$singletonManager = new SingletonManager();

$manager = new CounterManager($singletonManager, $fileSystemDriver);

$uuid = "862c0700-f771-11ea-adc1-0242ac120003";
$counter = Counter::getInstance($uuid, $manager);
$counter->increment();
echo $counter->getValue() . PHP_EOL;