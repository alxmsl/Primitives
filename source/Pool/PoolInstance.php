<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Pool;
use alxmsl\Primitives\Pool\Exception\NoInstancesFoundException;
use alxmsl\Primitives\Pool\Exception\UnavailableInstanceException;
use alxmsl\Primitives\Pool\Exception\UnknownSignalException;
use alxmsl\Primitives\Pool\Signal\EmptyInstanceSignal;
use alxmsl\Primitives\Pool\Signal\NotEmptyInstanceSignal;
use Generator;

/**
 * Instance generator
 * @author alxmsl
 * @date 8/5/14
 */ 
final class PoolInstance {
    /**
     * Minimum locked timeout, sec
     */
    const TIMEOUT_MINIMUM = 5;

    /**
     * @var IdentificationInterface[] available instances
     */
    private $instances = [];

    /**
     * @var array broken instance invalid until times
     */
    private $timeouts = [];

    /**
     * @var null|IdentificationInterface current selected instance
     */
    private $Current = null;

    /**
     * @var null|int selected instance index
     */
    private $index = null;

    /**
     * @var int recursion counter
     */
    private $recursionCount = 0;

    /**
     * @var int instance mistakes counter
     */
    private $mistakeCount = 0;

    /**
     * @var int lock timeout for broken instances, sec
     */
    private $lockTimeout = self::TIMEOUT_MINIMUM;

    /**
     * Lock timeout setter
     * @param int $lockTimeout lock timeout, sec
     * @return PoolInstance self instance
     */
    public function setLockTimeout($lockTimeout) {
        $this->lockTimeout = max(self::TIMEOUT_MINIMUM, $lockTimeout);
        return $this;
    }

    /**
     * Lock timeout getter
     * @return int lock timeout, sec
     */
    public function getLockTimeout() {
        return $this->lockTimeout;
    }

    /**
     * External reset for mistake counter
     * @return PoolInstance self instance
     */
    public function resetMistakeCount() {
        $this->mistakeCount = 0;
        return $this;
    }

    /**
     * Add instance to pool
     * @param IdentificationInterface $Instance added instance
     * @return PoolInstance self instance
     */
    public function addInstance(IdentificationInterface $Instance) {
        $this->instances[] = $Instance;
        return $this;
    }

    /**
     * Instance generator getter
     * @return Generator instance generator
     */
    public function getGenerator() {
        $this->recursionCount = 0;
        $this->incrementIndex();
        for (;$this->mistakeCount < count($this->instances);) {
            try {
                $Signal = (yield $this->Current);
                if (is_null($Signal)) {
                    $this->recursionCount = 0;
                    $this->incrementIndex();
                } elseif ($Signal instanceof NotEmptyInstanceSignal) {
                    $this->mistakeCount = 0;
                    $this->decrementIndex();
                } elseif ($Signal instanceof EmptyInstanceSignal) {
                    $this->mistakeCount += 1;
                    $this->decrementIndex();
                } else {
                    throw new UnknownSignalException($Signal);
                }
            } catch (UnavailableInstanceException $Ex) {
                $this->timeouts[$Ex->getInstanceId()] = strtotime(sprintf('+%s seconds', $this->getLockTimeout()));
                $this->decrementIndex();
            }
        }
    }

    /**
     * Decrement selected instance index
     */
    private function decrementIndex() {
        $this->index -= 1;
        if ($this->index < 0) {
            $this->index = count($this->instances) - $this->index;
        }
    }

    /**
     * Increment selected instance index
     * @throws NoInstancesFoundException when all instances are broken
     */
    private function incrementIndex() {
        $this->recursionCount += 1;
        if ($this->recursionCount > count($this->instances)) {
            throw new NoInstancesFoundException();
        }

        // Firstly select random index
        if (is_null($this->index)) {
            $this->index = array_rand($this->instances) - 1;
        }

        $this->index += 1;
        if ($this->index >= count($this->instances)) {
            $this->index = 0;
        }

        $this->Current = $this->instances[$this->index];
        if (array_key_exists($this->Current->getId(), $this->timeouts)) {
            if ($this->timeouts[$this->Current->getId()] < time()) {
                unset($this->timeouts[$this->Current->getId()]);
            } else {
                $this->incrementIndex();
            }
        }
    }
}
 