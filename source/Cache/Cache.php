<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache;
use alxmsl\Primitives\Cache\Provider\ProviderInterface;
use alxmsl\Primitives\Cache\Traits\CacheTrait;

/**
 * Base cache class
 * @author alxmsl
 * @date 8/28/14
 */
class Cache implements CacheInterface {
    use CacheTrait;

    /**
     * Default tries count for CAS operations
     */
    const TRIES_APPEND     = 3,
          TRIES_INVALIDATE = 3,
          TRIES_SET        = 3;

    /**
     * CAS operation timeout for collisions, microseconds
     */
    const TIMEOUT_CAS = 5000;

    /**
     * @var null|ProviderInterface storage provider instance
     */
    private $Provider = null;

    /**
     * Storage provider setter
     * @param null|ProviderInterface $Provider storage provider instance
     * @return Cache self instance
     */
    public function setProvider(ProviderInterface $Provider) {
        $this->Provider = $Provider;
        return $this;
    }

    /**
     * Storage provider getter
     * @return null|ProviderInterface storage provider getter
     */
    public function getProvider() {
        return $this->Provider;
    }

    /**
     * @param string $name cache key
     */
    public function __construct($name) {
        $this->name = (string) $name;
    }

    /**
     * Load cache data
     * @param bool $useCas use transaction for loading or not
     * @param bool $forceReload force reload stored data
     */
    protected function load($useCas = false, $forceReload = false) {
        if (is_null(self::$Value) || $forceReload) {
            self::$Value = $this->getProvider()->get($this->name, $useCas);
        }
    }

    /**
     * Save cache data
     * @param bool $useCas use transaction or not
     */
    protected function save($useCas = false) {
        is_null(self::$Value)
            ? $this->getProvider()->remove($this->name)
            : $this->getProvider()->set($this->name, self::$Value, $useCas);
    }

    /**
     * Clear cache data
     */
    protected function clear() {}
}
