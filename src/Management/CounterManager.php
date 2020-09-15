<?php

namespace Counters\Management;

use Counters\Core\Counter;
use Counters\Core\CounterManagerInterface;
use Counters\Core\Exceptions\CounterAlreadyRegisteredException;
use Counters\Core\IncrementorInterface;
use Counters\Management\CounterPersistenceDriverInterface;

class CounterManager implements CounterManagerInterface
{
    /**
     * @var SingletonManagerInterface
     */
    private $singletonManager;

    /**
     * @var CounterPersistenceDriverInterface
     */
    private $persistenceDriver;

    public function __construct(SingletonManagerInterface $singletonManager, CounterPersistenceDriverInterface $persistenceDriver)
    {
        $this->singletonManager = $singletonManager;
        $this->persistenceDriver = $persistenceDriver;
    }

    public function getCounterInstance(string $uuid): ?Counter
    {
        return $this->singletonManager->tryGet($uuid, Counter::class);
    }

    /**
     * @inheritdoc
     */
    public function registerCounter(Counter $counter)
    {
        if ($this->singletonManager->has($counter->getUuid(), Counter::class)) {
            throw new CounterAlreadyRegisteredException($counter->getUuid());
        }

        $this->singletonManager->set($counter->getUuid(), Counter::class, $counter);
        if (!$this->persistenceDriver->exists($counter->getUuid())) {
            $this->persistenceDriver->saveValue($counter->getUuid(), $counter->getValue());
        }
    }

    public function loadValue(string $uuid): int
    {
        return $this->persistenceDriver->loadValue($uuid);
    }

    public function getIncrementor(): IncrementorInterface
    {
        return $this->persistenceDriver->getIncrementor();
    }

    /**
     * @return SingletonManagerInterface
     */
    public function getSingletonManager(): SingletonManagerInterface
    {
        return $this->singletonManager;
    }

    /**
     * @param SingletonManagerInterface $singletonManager
     */
    public function setSingletonManager(SingletonManagerInterface $singletonManager)
    {
        $this->singletonManager = $singletonManager;
    }

    /**
     * @return \Counters\Management\CounterPersistenceDriverInterface
     */
    public function getPersistenceDriver(): \Counters\Management\CounterPersistenceDriverInterface
    {
        return $this->persistenceDriver;
    }

    /**
     * @param \Counters\Management\CounterPersistenceDriverInterface $persistenceDriver
     */
    public function setPersistenceDriver(\Counters\Management\CounterPersistenceDriverInterface $persistenceDriver)
    {
        $this->persistenceDriver = $persistenceDriver;
    }
}