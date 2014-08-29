<?php

namespace alxmsl\Primitives;
use alxmsl\Primitives\Cache\Cache;
use alxmsl\Primitives\Cache\Provider\MemcachedProvider;
use Memcached;

/**
 * Cache instances factory
 * @author alxmsl
 * @date 8/29/14
 */
final class CacheFactory {
    /**
     * Create cache instance factory method
     * @param string $name root cache key name
     * @param string $levelClass cache instance class
     * @param Memcached $Connection memcached connection
     * @return null|Cache cache instance or null if level class not found
     */
    public static function createMemcachedCache($name, $levelClass, Memcached $Connection) {
        if (is_a($levelClass, Cache::getClass(), true)
            && class_exists($levelClass)) {

            $Provider = new MemcachedProvider();
            $Provider->setConnection($Connection);
            /** @var Cache $Instance */
            $Instance = new $levelClass($name);
            return $Instance->setProvider($Provider);
        } else {
            return null;
        }
    }
}
 