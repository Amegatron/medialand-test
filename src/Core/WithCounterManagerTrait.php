<?php

namespace Counters\Core;

trait WithCounterManagerTrait
{
    /**
     * @var CounterManagerInterface
     */
    protected $counterManager;

    public function getCounterManager(): ?CounterManagerInterface
    {
        return $this->counterManager;
    }

    public function setCounterManager(?CounterManagerInterface $manager)
    {
        $this->counterManager = $manager;
    }
}