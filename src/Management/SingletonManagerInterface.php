<?php

namespace Counters\Management;

use Counters\Management\Exceptions\InstanceDoesNotExistException;

/**
 * Interface SingletonManagerInterface
 *
 * Provides interface for managing Singleton objects
 *
 * @package Counters\Management
 */
interface SingletonManagerInterface
{
    /**
     * Gets an existing instance by it's ID and ClassName. Throws exception if such instance does not exist
     *
     * @param $id
     * @param string $className
     * @return mixed
     * @throws InstanceDoesNotExistException
     */
    public function get($id, string $className);

    /**
     * Tries to get an existing instance by it's ID and ClassName, or returns null otherwise
     * @param $id
     * @param string $className
     * @return mixed
     */
    public function tryGet($id, string $className);

    /**
     * Adds/replaces an instance by it's ID and ClassName
     *
     * @param $id
     * @param string $className
     * @param $instance
     * @return mixed
     */
    public function set($id, string $className, $instance);

    /**
     * Checks if an instance of ClassName with specified ID already exists inside this manager
     *
     * @param $id
     * @param string $className
     * @return bool
     */
    public function has($id, string $className): bool;
}