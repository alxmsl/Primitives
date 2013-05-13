<?php

namespace Set\Iterator;

/**
 * 
 * @author alxmsl
 * @date 5/4/13
 */ 
final class PostgresIterator extends AbstractIterator {

    private $position = null;

    private $lastItem = null;

    public function current() {
        $result = $this->getProvider()->get($this->position);
        if (!empty($result)) {
            $item = reset($result);
            $this->position = $item['id'];
            $this->lastItem = $item;
            return $item;
        } else {
            $this->lastItem = array();
        }
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position += 1;
    }

    public function rewind() {
        $this->position = null;
        $this->lastItem = null;
    }

    public function valid() {
        return is_null($this->lastItem) || !empty($this->lastItem);
    }
}
