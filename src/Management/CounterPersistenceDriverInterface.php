<?php

namespace Counters\Management;

use Counters\Core\Exceptions\CounterDoesNotExistException;
use Counters\Core\IncrementorInterface;

interface CounterPersistenceDriverInterface
{
    /**
     * Loads a value for a counter from persistence layer
     *
     * @param $uuid
     * @return int
     * @throws CounterDoesNotExistException
     */
    public function loadValue($uuid): int;

    /**
     * Performs atomic incrementation of a counter on persistence layer
     * @param $uuid
     * @param int $by
     * @return int
     * @throws CounterDoesNotExistException
     */
    public function increment($uuid, $by = 1): int;

    /**
     * Writes an exact value for a counter on persistence layer
     *
     * @param $uuid
     * @param $value
     * @return mixed
     */
    public function saveValue($uuid, $value);

    /**
     * Returns an incrementor associated with this driver
     *
     * @return IncrementorInterface
     */
    public function getIncrementor(): IncrementorInterface;

    /**
     * Checks if a Counter with specified UUID exists in underlying persistence layer
     *
     * @param $uuid
     * @return bool
     */
    public function exists($uuid): bool;
}