<?php

namespace alxmsl\Primitives;
use alxmsl\Connection\Redis\Connection;
use alxmsl\Primitives\Queue\Provider\RedisProvider;
use alxmsl\Primitives\Queue\Queue;

/**
 * 
 * @author alxmsl
 * @date 7/10/14
 */ 
final class QueueFactory {
    /**
     * Create new Redis queue instance
     * @param string $name set name
     * @param Connection $Connection redis connection
     * @return Queue created queue instance
     */
    public static function createRedisQueue($name, Connection $Connection) {
        $Provider = new RedisProvider();
        $Provider->setConnection($Connection)
            ->setName($name);
        $Queue = new Queue();
        return $Queue->setProvider($Provider);
    }
}
 