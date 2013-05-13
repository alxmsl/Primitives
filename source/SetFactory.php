<?php

namespace Set;

use Set\Provider\RedisProvider,
    Set\Provider\PostgresProvider,
    Connection\Redis\Client\Connection as RedisConnection,
    Connection\Postgresql\Client\Connection as PostgresConnection;

/**
 * Factory for set creation
 * @author alxmsl
 * @date 4/5/13
 */
final class SetFactory {
    /**
     * Create new set on redis
     * @param string $name set name
     * @param \Connection\Redis\Client\Connection $Connection redis connection
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
     * @param \Connection\Postgresql\Client\Connection $Connection postgresql connection
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
