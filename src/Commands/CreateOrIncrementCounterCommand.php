<?php

namespace Counters\Commands;

use Counters\Core\WithCounterManagerInterface;
use Counters\Core\WithCounterManagerTrait;

class CreateOrIncrementCounterCommand extends AbstractCommand
{
    private $uuid;
    private $initialValue = 0;
    private $incrementBy = 1;

    public function __construct(string $uuid, $initialValue = 0, $incrementBy = 1)
    {
        $this->uuid = $uuid;
        $this->initialValue = $initialValue;
        $this->incrementBy = $incrementBy;
    }

    /**
     * @return mixed
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return CreateOrIncrementCounterCommand
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getInitialValue(): int
    {
        return $this->initialValue;
    }

    /**
     * @param int $initialValue
     * @return CreateOrIncrementCounterCommand
     */
    public function setInitialValue(int $initialValue): CreateOrIncrementCounterCommand
    {
        $this->initialValue = $initialValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getIncrementBy(): int
    {
        return $this->incrementBy;
    }

    /**
     * @param int $incrementBy
     * @return CreateOrIncrementCounterCommand
     */
    public function setIncrementBy(int $incrementBy): CreateOrIncrementCounterCommand
    {
        $this->incrementBy = $incrementBy;
        return $this;
    }
}