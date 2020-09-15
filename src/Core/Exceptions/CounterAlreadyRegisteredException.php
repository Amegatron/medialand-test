<?php

namespace Counters\Core\Exceptions;

use Throwable;

class CounterAlreadyRegisteredException extends \Exception
{
    private $uuid;

    public function __construct(string $uuid, $message = "Counter with this UUID already registered", $code = 0, Throwable $previous = null)
    {
        $this->uuid = $uuid;
        parent::__construct($message, $code, $previous);
    }
}