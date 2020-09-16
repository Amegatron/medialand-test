<?php

use Counters\Commands\CreateOrIncrementCounterCommand;
use Counters\Commands\GetCounterCommand;
use Counters\Commands\Handlers\CreateOrIncrementCounterCommandHandler;
use Counters\Commands\Handlers\GetCounterCommandHandler;
use Counters\Core\CounterManagerInterface;
use Counters\Management\CounterManager;
use Counters\Management\SingletonManager;
use Counters\Persistence\FileSystemPersistenceDriver;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Locator\InMemoryLocator;

require './vendor/autoload.php';

$fileSystemDriver = new FileSystemPersistenceDriver(__DIR__ . '/data');
$singletonManager = new SingletonManager();

$manager = new CounterManager($singletonManager, $fileSystemDriver);

$commandBus = initCommandBus($manager);

$uuid = "862c0700-f771-11ea-adc1-0242ac120003";
$command = new GetCounterCommand($uuid);
// $command = new CreateOrIncrementCounterCommand($uuid);
$counter = $commandBus->handle($command);

if ($counter) {
    echo $counter->getValue() . PHP_EOL;
} else {
    echo "Counter does not exist" . PHP_EOL;
}

function initCommandBus(CounterManagerInterface $manager)
{
    $config = [];

    $handler = new GetCounterCommandHandler();
    $handler->setCounterManager($manager);
    $config[GetCounterCommand::class] = $handler;

    $handler = new CreateOrIncrementCounterCommandHandler();
    $handler->setCounterManager($manager);
    $config[CreateOrIncrementCounterCommand::class] = $handler;

    return new CommandBus([
        new CommandHandlerMiddleware(
            new League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
            new InMemoryLocator($config),
            new League\Tactician\Handler\MethodNameInflector\HandleInflector()
        )
    ]);
}