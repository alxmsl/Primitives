<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Semaphore;
use alxmsl\Primitives\Semaphore\Provider\AbstractProvider;

/**
 * Abstract semaphore class
 * @author alxmsl
 * @date 6/17/14
 */
final class Semaphore implements SemaphoreInterface {
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
     * @var AbstractProvider storage provider for semaphore implementation
     */
    private $Provider = null;

    /**
     * Storage provider setter
     * @param AbstractProvider $Provider storage provider
     * @return $this self instance
     */
    public function setProvider(AbstractProvider $Provider) {
        $this->Provider = $Provider;
        $this->Provider->setName($this->getName())
            ->setTtl($this->getTtl())
            ->setTimeout($this->getTimeout())
            ->setValue($this->getValue());
        return $this;
    }

    /**
     * Storage provider getter
     * @return AbstractProvider storage provider
     */
    public function getProvider() {
        return $this->Provider;
    }

    /**
     * @param string $name semaphore name
     * @param int $value initial semaphore value
     */
    public function __construct($name, $value = 1) {
        $this->name  = (string) $name;
        $this->value = max(1, $value);
    }

    /**
     * @return string semaphore name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return int initial semaphore value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return int waiting timeout, sec
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * @param int $timeout waiting timeout, sec
     * @return $this self instance
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
     * @return $this self instance
     */
    public function setTtl($ttl) {
        $this->ttl = max(0, $ttl);
        return $this;
    }

    /**
     * Start waiting a semaphore method
     * @return bool semaphore waiting result
     */
    public function wait() {
        return $this->getProvider()->wait();
    }

    /**
     * Make semaphore available again
     */
    public function signal() {
        $this->getProvider()->signal();
    }
}
