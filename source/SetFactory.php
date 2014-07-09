<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives;
use alxmsl\Connection\Redis\Connection as RedisConnection;
use alxmsl\Connection\Postgresql\Connection as PostgresConnection;
use alxmsl\Primitives\Set\Provider\PostgresProvider;
use alxmsl\Primitives\Set\Provider\RedisProvider;
use alxmsl\Primitives\Set\Set;

/**
 * Factory for set creation
 * @author alxmsl
 * @date 4/5/13
 */
final class SetFactory {
    /**
     * Create new set on redis
     * @param string $name set name
     * @param RedisConnection $Connection redis connection
     * @param bool $isEnlisted enable enlisted functionality for set
     * @return Set created set
     */
    public static function createRedisSet($name, RedisConnection $Connection, $isEnlisted = false) {
        $Provider = new RedisProvider();
        $Provider->setRedis($Connection)
            ->setName($name)
            ->setEnlistedMode($isEnlisted);
        $Set = new Set();
        $Set->setProvider($Provider);
        return $Set;
    }

    /**
     * Create new set on postgresql
     * @param string $name set name
     * @param PostgresConnection $Connection postgresql connection instance
     * @return Set created set
     */
    public static function createPostgresSet($name, PostgresConnection $Connection) {
        $Provider = new PostgresProvider();
        $Provider->setPostgres($Connection)
            ->setName($name)
            ->setEnlistedMode(true);
        $Set = new Set();
        $Set->setProvider($Provider);
        return $Set;
    }
}
