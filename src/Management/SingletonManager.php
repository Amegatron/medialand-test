<?php

namespace Counters\Management;

use Counters\Management\Exceptions\InstanceDoesNotExistException;

class SingletonManager implements SingletonManagerInterface
{

    private $instances = [];

    public function get($id, string $className)
    {
        $instance = $this->tryGet($id, $className);
        if (!$instance) {
            throw new InstanceDoesNotExistException($id, $className);
        }
        return $instance;
    }

    public function tryGet($id, string $className)
    {
        return $this->instances[$className][$id] ?? null;
    }

    public function set($id, string $className, $instance)
    {
        if (!isset($this->instances[$className])) {
            $this->instances[$className] = [];
        }

        $this->instances[$className][$id] = $instance;
    }

    public function has($id, string $className): bool
    {
        return isset($this->instances[$className][$id]);
    }
}