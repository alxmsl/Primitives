<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Semaphore\Provider;
use alxmsl\Connection\Postgresql\Exception\ConnectException;
use alxmsl\Connection\Redis\Connection;
use alxmsl\Connection\Redis\Exception\ScriptExecutionException;
use alxmsl\Primitives\Semaphore\Exception\StorageException;

/**
 * Redis semaphore storage provider
 * @author alxmsl
 */
final class RedisProvider extends AbstractProvider {
    /**
     * @var Connection redis connection instance
     */
    private $Connection = null;

    /**
     * @var string LUA wait script
     */
    private $scriptWait = '';

    /**
     * @var string LUA signal script
     */
    private $scriptSignal = '';

    /**
     * @var bool held semaphore or not
     */
    private $held = false;

    public function __construct() {
        $this->scriptWait = <<<EOD
    redis.call('setnx', KEYS[1], KEYS[2])
    redis.call('persist', KEYS[1])
    if tonumber(redis.call('get', KEYS[1])) > 0
    then
        return redis.call('decr', KEYS[1])
    else
        return nil
    end
EOD;

        $this->scriptSignal = <<<EOD
    local value
    if redis.call('get', KEYS[1]) == false
    then
        value = nil
    else
        value = tonumber(redis.call('incr', KEYS[1]))
    end
    if value == tonumber(KEYS[2]) then
        redis.call('expire', KEYS[1], KEYS[3])
    end
    return value
EOD;
    }

    /**
     * @return Connection redis connection instance
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * @param Connection $Connection redis connection instance
     * @return $this provider instance
     */
    public function setConnection(Connection $Connection) {
        $this->Connection = $Connection;
        return $this;
    }

    /**
     * Start waiting a semaphore method
     */
    public function wait() {
        $this->enter();
        if ($this->held === false) {
            $finishTime = time() + $this->getTimeout();
            do {
                $this->enter();
                if ($this->held === false) {
                    usleep(25000);
                } else {
                    return true;
                }
            } while (time() <= $finishTime);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Make semaphore available again
     */
    public function signal() {
        if ($this->held === true) {
            try {
                $this->getConnection()
                    ->evaluate($this->scriptSignal, [
                        $this->getName(),
                        $this->getValue(),
                        $this->getTtl(),
                    ]);
                $this->isUsed = false;
            } catch (ConnectException $Ex) {
                throw new StorageException('semaphores storage unavailable');
            }
        }
    }

    /**
     * Enter semaphore method
     * @throws StorageException when can not initialize semaphore or storage unavailable
     */
    private function enter() {
        try {
            $value = $this->getConnection()
                ->evaluate($this->scriptWait, [
                    $this->getName(),
                    $this->getValue(),
                ]);
            $this->held = ($value !== false);
        } catch (ScriptExecutionException $Ex) {
            throw new StorageException('semaphore was not initialize');
        } catch (ConnectException $Ex) {
            throw new StorageException('semaphores storage unavailable');
        }
    }
}
