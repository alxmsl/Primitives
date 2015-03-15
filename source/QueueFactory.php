<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

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
