<?php

namespace Counters\Core;

use Counters\Core\Exceptions\CounterAlreadyRegisteredException;
use Counters\Core\Exceptions\CounterDoesNotExistException;

/**
 * Interface CounterManagerInterface
 *
 * Interface for Counter managers which are responsible for managing counters within application
 *
 * @package Counters\Core
 */
interface CounterManagerInterface
{
    /**
     * Returns and existing instance of a counter by UUID or null
     *
     * @param $uuid string
     * @return Counter|null
     */
    public function getCounterInstance(string $uuid): ?Counter;

    /**
     * Registers an instance of Counter inside this Manager
     *
     * @param Counter $counter
     * @return void
     * @throws CounterAlreadyRegisteredException
     */
    public function registerCounter(Counter $counter);

    /**
     * Loads a value of counter by it's UUID.
     *
     * @param $uuid string
     * @return int
     * @throws CounterDoesNotExistException In case a counter with such UUID does exist in the system
     */
    public function loadValue(string $uuid): int;

    /**
     * Provides an Incrementor for managed Counters
     *
     * @return IncrementorInterface
     */
    public function getIncrementor(): IncrementorInterface;
}