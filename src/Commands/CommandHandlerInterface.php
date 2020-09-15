<?php

namespace Counters\Commands;

interface CommandHandlerInterface
{
    /**
     * Handles a command
     * 
     * @param mixed $command
     * @return mixed
     */
    public function handle(AbstractCommand $command);
}