<?php

namespace Set\Provider;

use Set\SetInterface;

/**
 * Abstract set storage provider
 * @author alxmsl
 * @date 4/5/13
 */
abstract class AbstractProvider implements SetInterface, \IteratorAggregate {
    /**
     * @var string set name
     */
    private $name = '';

    private $enlistedMode = false;

    /**
     * Set name setter
     * @param string $name set name
     * @return AbstractProvider self
     */
    public function setName($name) {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Set name getter
     * @return string set name
     */
    public function getName() {
        return $this->name;
    }

    public function setEnlistedMode($enlistedMode) {
        $this->enlistedMode = (bool) $enlistedMode;
        return $this;
    }

    public function isEnlistedMode() {
        return $this->enlistedMode;
    }

    abstract public function get($offset = null);
}
