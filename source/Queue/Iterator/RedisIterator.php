<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue\Iterator;

/**
 * Queue iterator implementation for Redis storage
 * @author alxmsl
 * @date 7/10/14
 */ 
final class RedisIterator extends AbstractIterator {
    /**
     * @var null|int current position
     */
    private $position = null;

    /**
     * @var null|mixed selected item
     */
    private $lastItem = null;

    /**
     * Return the current element
     * @return mixed current element
     */
    public function current() {
        return $this->lastItem;
    }

    /**
     * Return the key of the current element
     * @return int|null scalar on success, or null on failure.
     */
    public function key() {
        return $this->position;
    }

    /**
     * Move forward to next element
     */
    public function next() {
        $this->lastItem = $this->getProvider()->dequeue();
        $this->position += 1;
    }

    /**
     * Checks if current position is valid
     * @return boolean returns true on success or false on failure.
     */
    public function valid() {
        return $this->lastItem !== false;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind() {
        $this->lastItem = $this->getProvider()->dequeue();
        $this->position = 0;
    }
}
