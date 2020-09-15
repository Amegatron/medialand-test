<?php

namespace Counters\Core;

interface WithCounterManagerInterface
{
    public function getCounterManager(): ?CounterManagerInterface;
    public function setCounterManager(?CounterManagerInterface $manager);
}