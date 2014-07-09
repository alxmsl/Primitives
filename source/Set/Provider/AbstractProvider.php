<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set\Provider;
use alxmsl\Primitives\Set\SetInterface;
use IteratorAggregate;

/**
 * Abstract set storage provider
 * @author alxmsl
 * @date 4/5/13
 */
abstract class AbstractProvider implements SetInterface, IteratorAggregate {
    /**
     * @var string set name
     */
    private $name = '';

    /**
     * @var bool enlisted mode key
     */
    private $enlistedMode = false;

    /**
     * Set name setter
     * @param string $name set name
     * @return AbstractProvider self instance
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

    /**
     * Enlisted mode setter
     * @param bool $enlistedMode enlisted mode value
     * @return $this self instance
     */
    public function setEnlistedMode($enlistedMode) {
        $this->enlistedMode = (bool) $enlistedMode;
        return $this;
    }

    /**
     * Enlisted mode getter
     * @return bool enlisted mode value
     */
    public function isEnlistedMode() {
        return $this->enlistedMode;
    }

    /**
     * Set item getter
     * @param mixed $offset set items offset
     * @return mixed set item
     */
    abstract public function get($offset = null);
}
