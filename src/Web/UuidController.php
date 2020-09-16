<?php

namespace Counters\Web;

use Counters\Commands\CreateOrIncrementCounterCommand;
use Counters\Commands\GetCounterCommand;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use League\Route\Http\Exception\NotFoundException;
use League\Tactician\CommandBus;

class UuidController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @param ServerRequest $request
     * @param $args
     * @return mixed
     * @throws NotFoundException
     */
    public function getUuid(ServerRequest $request, $args)
    {
        $command = new GetCounterCommand($args['uuid']);
        $counter = $this->commandBus->handle($command);
        if (!$counter) {
            throw new NotFoundException();
        } else {
            return new TextResponse((string)$counter->getValue());
        }
    }

    public function postUuid(ServerRequest $request, $args)
    {
        $command = new CreateOrIncrementCounterCommand($args['uuid']);
        $counter = $this->commandBus->handle($command);
        return new TextResponse((string)$counter->getValue());
    }

    /**
     * @return CommandBus
     */
    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }

    /**
     * @param CommandBus $commandBus
     * @return UuidController
     */
    public function setCommandBus(CommandBus $commandBus): UuidController
    {
        $this->commandBus = $commandBus;
        return $this;
    }
}