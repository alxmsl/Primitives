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

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     */
    abstract public function add($Item);

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existance
     */
    abstract public function exists($Item);

    abstract public function get($offset = null);
}
