<?php

namespace Set\Iterator;

use Set\Provider\RedisProvider;

/**
 *
 * @author alxmsl
 * @date 5/11/13
 */
final class RedisIterator extends AbstractIterator {

    private $position = null;

    private $lastItem = null;

    public function current() {
        $result = $this->getProvider()->get();
        $this->lastItem = $result;
        return $this->lastItem;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position += 1;
    }

    public function rewind() {
        /** @var RedisProvider $Provider */
        $Provider = $this->getProvider();
        if (!$Provider->hasDuplicate()) {
            $Provider->createDuplicate();
        }

        $this->position = null;
        $this->lastItem = '';
    }

    public function valid() {
        return $this->lastItem !== false;
    }
}
