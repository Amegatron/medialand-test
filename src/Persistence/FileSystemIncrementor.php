<?php

namespace Counters\Persistence;

use Counters\Core\Counter;
use Counters\Core\Exceptions\CounterDoesNotExistException;
use Counters\Core\IncrementorInterface;

class FileSystemIncrementor implements IncrementorInterface
{

    /**
     * @var FileSystemPersistenceDriver
     */
    private $driver;

    public function __construct(FileSystemPersistenceDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param Counter $counter
     * @param int $by
     * @return int
     * @throws CounterDoesNotExistException
     */
    public function increment(Counter $counter, int $by = 1): int
    {
        return $this->driver->increment($counter->getUuid(), $by);
    }
}