<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Provider;
use alxmsl\Primitives\Cache\Item;
use Memcached;

/**
 * Memcached cache instance provider
 * @author alxmsl
 * @date 8/28/14
 */ 
final class MemcachedProvider implements ProviderInterface {
    /**
     * @var null|Memcached memcached instance
     */
    private $Connection = null;

    /**
     * @var null|string cas operation token
     */
    private $casToken = null;

    /**
     * Connection instance setter
     * @param Memcached $Connection connection instance
     * @return $this provider instance
     */
    public function setConnection(Memcached $Connection) {
        $this->Connection = $Connection;
        return $this;
    }

    /**
     * Connection instance getter
     * @return null|Memcached connection instance
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * @inheritdoc
     */
    public function get($key, $useCas = false) {
        $Result = $useCas
            ? $this->getConnection()->get($key, null, $this->casToken)
            : $this->getConnection()->get($key);

        if ($this->getConnection()->getResultCode() == Memcached::RES_SUCCESS) {
            $Item = new Item($key);
            $Item->unserialize($Result);
            return $Item;
        } else {
            return new Item($key);
        }
    }

    /**
     * @inheritdoc
     */
    public function set($key, $Value, $useCas = false) {
        if ($useCas) {
            is_null($this->casToken)
                ? $this->getConnection()->set($key, $Value->serialize())
                : $this->getConnection()->cas($this->casToken, $key, $Value->serialize());
        } else {
            $this->getConnection()->set($key, $Value->serialize());
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($key) {
        $this->getConnection()->delete($key);
    }
}
 