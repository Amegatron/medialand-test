<?php

namespace Counters\Commands;

class GetCounterCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
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
     * @return GetCounterCommand
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }
}