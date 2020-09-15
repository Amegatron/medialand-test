<?php

namespace Counters\Commands\Handlers;

use Counters\Commands\AbstractCommand;
use Counters\Commands\CommandHandlerInterface;
use Counters\Commands\CreateOrIncrementCounterCommand;
use Counters\Core\Counter;
use Counters\Core\WithCounterManagerInterface;
use Counters\Core\WithCounterManagerTrait;

class CreateOrIncrementCounterCommandHandler implements CommandHandlerInterface, WithCounterManagerInterface
{
    use WithCounterManagerTrait;

    public function handle(AbstractCommand $command)
    {
        /** @var CreateOrIncrementCounterCommand $command */
        $alreadyExists = $this->getCounterManager()->exists($command->getUuid());
        $counter = Counter::getInstance($command->getUuid(), $this->getCounterManager(), $command->getInitialValue());
        if ($alreadyExists) {
            $counter->increment($command->getIncrementBy());
        }
        return $counter;
    }
}