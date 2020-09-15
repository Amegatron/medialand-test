<?php

namespace Counters\Commands\Handlers;

use Counters\Commands\AbstractCommand;
use Counters\Commands\CommandHandlerInterface;
use Counters\Commands\GetCounterCommand;
use Counters\Core\Counter;
use Counters\Core\CounterManagerInterface;
use Counters\Core\WithCounterManagerInterface;
use Counters\Core\WithCounterManagerTrait;

class GetCounterCommandHandler implements CommandHandlerInterface, WithCounterManagerInterface
{
    use WithCounterManagerTrait;

    /**
     * @param AbstractCommand $command
     * @return mixed|void
     */
    public function handle(AbstractCommand $command)
    {
        /** @var GetCounterCommand $command */
        if (!$this->getCounterManager()->exists($command->getUuid())) {
            return null;
        }

        return Counter::getInstance($command->getUuid(), $this->getCounterManager());
    }
}