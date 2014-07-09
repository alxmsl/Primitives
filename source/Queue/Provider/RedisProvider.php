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

/**
 * Redis storage provider for queues
 * @author alxmsl
 * @date 7/9/14
 */ 
final class RedisProvider extends AbstractProvider {
    /**
     * @var null|Connection Redis connection instance
     */
    private $Connection = null;

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
     * Enqueue item to Redis storage
     * @param mixed $Item queued item
     */
    public function enqueue($Item) {
        $this->getConnection()->lpush($this->getName(), $Item);
    }

    /**
     * Dequeque item from Redis storage
     * @return mixed|false queued item or FALSE if queue is empty
     */
    public function dequeue() {
        return $this->getConnection()->rpop($this->getName());
    }
}
 