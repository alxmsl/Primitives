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
use alxmsl\Primitives\Semaphore\Provider\RedisProvider;
use alxmsl\Primitives\Semaphore\Semaphore;

/**
 * Semaphores factory
 * @author alxmsl
 */
final class SemaphoreFactory {
    /**
     * Semaphore constructor
     * @param Connection $Connection redis storage connection
     * @param string $name semaphore name
     * @param int $value semaphore initial value
     * @return Semaphore semaphore instance
     */
    public static function createRedisSemaphore(Connection $Connection, $name, $timeout = 1, $ttl = 5, $value = 1) {
        $Provider = new RedisProvider();
        $Provider->setConnection($Connection);
        $Semaphore = new Semaphore($name, $value);
        return $Semaphore->setTimeout($timeout)
            ->setTtl($ttl)
            ->setProvider($Provider);
    }
}
