<?php

namespace Counters\Management\Exceptions;

use Throwable;

class InstanceDoesNotExistException extends \Exception
{
    private $id;
    private $className;

    public function __construct($id, $className, $message = "Instance does not exist", $code = 0, Throwable $previous = null)
    {
        $this->id = $id;
        $this->className = $className;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }
}