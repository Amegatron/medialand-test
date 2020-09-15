<?php

namespace Counters\Core;

use Counters\Core\Exceptions\CounterDoesNotExistException;

/**
 * Class Counter
 *
 * Represents the Counter itself
 *
 * @package Counters\Core
 */
class Counter
{
    // Counter's UUID
    private $uuid;

    // Counter's value
    private $value;

    /**
     * @var IncrementorInterface
     */
    private $incrementor;

    /**
     * Constructor is private to forbid direct instance creation. Instances of this class should be managed
     * in Singleton-like manner, which is delegated to managers (CounterManagerInterface).
     *
     * @param string $uuid
     * @param int $value
     */
    private function __construct(string $uuid, int $value)
    {
        $this->uuid = $uuid;
        $this->value = $value;
    }

    public static function getInstance(string $uuid, CounterManagerInterface $manager, int $initialValue = 0): self {
        $instance = $manager->getCounterInstance($uuid);
        if ($instance) {
            return $instance;
        }

        try {
            $value = $manager->loadValue($uuid);
            $instance = new self($uuid, $value);
        } catch (CounterDoesNotExistException $ex) {
            $instance = new self($uuid, $initialValue);
        }
        $instance->setIncrementor($manager->getIncrementor());

        $manager->registerCounter($instance);
        return $instance;
    }

    /**
     * Increments the value by the specified amount
     *
     * @param int $by
     * @return int New value
     */
    public function increment($by = 1): int {
        $this->value = $this->incrementor->increment($this, $by);
        return $this->value;
    }

    //
    // Getters, setters
    //

    public function getUuid(): string {
        return $this->uuid;
    }

    public function getValue(): int {
        return $this->value;
    }

    /**
     * @return IncrementorInterface
     */
    public function getIncrementor(): IncrementorInterface
    {
        return $this->incrementor;
    }

    /**
     * @param IncrementorInterface $incrementor
     */
    private function setIncrementor(IncrementorInterface $incrementor)
    {
        $this->incrementor = $incrementor;
    }
}