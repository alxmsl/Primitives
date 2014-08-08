<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Queue\Provider;
use alxmsl\Connection\Redis\Connection;
use alxmsl\Connection\Redis\Exception\ConnectException;
use alxmsl\Primitives\Queue\Exception\DequeueException;
use alxmsl\Primitives\Queue\Exception\EnqueueException;
use alxmsl\Primitives\Queue\Iterator\RedisIterator;

/**
 * Redis storage provider for queues
 * @author alxmsl
 * @date 7/9/14
 */ 
final class RedisProvider extends AbstractProvider {
    /**
     * @var null|RedisIterator queue`s iterator instance
     */
    private $Iterator = null;

    /**
     * @var null|Connection Redis connection instance
     */
    private $Connection = null;

    /**
     * Redis queue iterator getter
     * @return RedisIterator redis queue iterator
     */
    public function getIterator() {
        if (is_null($this->Iterator)) {
            $this->Iterator = new RedisIterator();
            $this->Iterator->setProvider($this);
        }
        return $this->Iterator;
    }

    /**
     * Redis connection setter
     * @param null|Connection $Connection Redis connection instance
     * @return RedisProvider self instance
     */
    public function setConnection(Connection $Connection) {
        $this->Connection = $Connection;
        return $this;
    }

    /**
     * Redis connection getter
     * @return null|Connection Redis connection instance
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * Enqueue item
     * @param mixed $Item queued item
     * @throws EnqueueException when queue instance was not available
     */
    public function enqueue($Item) {
        try {
            $this->getConnection()->lpush($this->getName(), $Item);
        } catch (ConnectException $Ex) {
            throw new EnqueueException($Ex);
        }
    }

    /**
     * Dequeque item
     * @throws DequeueException when queue instance was not available
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue() {
        try {
            return $this->getConnection()->rpop($this->getName());
        } catch (ConnectException $Ex) {
            throw new DequeueException($Ex);
        }
    }
}
 