<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Semaphore\Provider;
use alxmsl\Primitives\Semaphore\SemaphoreInterface;

/**
 * Abstract semaphore storage provider
 * @author alxmsl
 */
abstract class AbstractProvider implements SemaphoreInterface {
    /**
     * @var string semaphore name
     */
    private $name = '';

    /**
     * @var int waiting timeout, sec
     */
    private $timeout = 0;

    /**
     * @var int semaphore inactive timeout, sec
     */
    private $ttl = 0;

    /**
     * @var int initial semaphore value
     */
    private $value = 1;

    /**
     * @param string $name semaphore name
     * @return AbstractProvider self instance
     */
    public function setName($name) {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * @return string semaphore name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return int waiting timeout, sec
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * @param int $timeout waiting timeout, sec
     */
    public function setTimeout($timeout) {
        $this->timeout = max(0, $timeout);
        return $this;
    }

    /**
     * @return int semaphore inactive timeout, sec
     */
    public function getTtl() {
        return $this->ttl;
    }

    /**
     * @param int $ttl semaphore inactive timeout, sec
     */
    public function setTtl($ttl) {
        $this->ttl = max (0, $ttl);
        return $this;
    }

    /**
     * @return int initial semaphore value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param int $value initial semaphore value
     */
    public function setValue($value) {
        $this->value = max (1, $value);
        return $this;
    }
}
