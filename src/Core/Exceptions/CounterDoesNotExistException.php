<?php

namespace Counters\Core\Exceptions;

use Throwable;

class CounterDoesNotExistException extends \Exception
{
    private $uuid;

    public function __construct($uuid, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->uuid = $uuid;
        parent::__construct($message, $code, $previous);
    }

    public function getUuid() {
        return $this->uuid;
    }
}