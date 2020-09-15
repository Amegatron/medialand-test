<?php

namespace Counters\Core;

/**
 * Interface IncrementorInterface
 *
 * Provides functionality for atomic incrementation of a Counter by it's UUID
 *
 * @package Counters\Core
 */
interface IncrementorInterface
{
    /**
     * Increments a counter by it's UUID
     *
     * @param Counter $counter
     * @param int $by
     * @return int
     */
    public function increment(Counter $counter, int $by = 1): int;
}