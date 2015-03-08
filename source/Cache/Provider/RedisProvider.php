<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Cache\Provider;
use alxmsl\Connection\Redis\Connection;
use alxmsl\Connection\Redis\Exception\ConnectException;
use alxmsl\Connection\Redis\Exception\KeyNotFoundException;
use alxmsl\Primitives\Cache\Exception\CasErrorException;
use alxmsl\Primitives\Cache\Item;
use Redis;

/**
 * Redis provider for cache support
 * @author alxmsl
 */
final class RedisProvider implements ProviderInterface {
    /**
     * @var null|Connection redis instance
     */
    private $Connection = null;

    /**
     * @var bool has CAS transaction
     */
    private $watched = false;

    /**
     * @return Connection|null redis connection
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * @param Connection|null $Connection redis connection
     * @return $this self instance
     */
    public function setConnection(Connection $Connection) {
        $this->Connection = $Connection;
        return $this;
    }

    /**
     * @inheritdoc
     * @throws ConnectException when redis instance unavailable
     */
    public function get($key, $useCas = false) {
        if ($useCas) {
            $this->watched = $this->getConnection()->watch($key);
        }

        try {
            $result = $this->getConnection()->get($key);
            if ($result !== false) {
                $Item = new Item($key);
                $Item->unserialize($result);
                return $Item;
            } else {
                return new Item($key);
            }
        } catch (KeyNotFoundException $Ex) {
            return new Item($key);
        }
    }

    /**
     * @inheritdoc
     * @throws CasErrorException when CAS operation was impossible
     * @throws ConnectException when redis instance unavailable
     */
    public function set($key, $Value, $useCas = false) {
        if ($useCas) {
            if ($this->watched) {
                $this->watched = false;

                $result = $this->getConnection()->transaction(function(Redis $Instance) use ($key, $Value) {
                    return $Instance->set($key, $Value->serialize());
                });
                if ($result[0] === false) {
                    throw new CasErrorException('watched key was changed');
                }
            } else {
                throw new CasErrorException('key is not watching now');
            }
        } else {
            $this->getConnection()->set($key, $Value->serialize());
        }
    }

    /**
     * @inheritdoc
     * @throws ConnectException when redis instance unavailable
     */
    public function remove($key) {
        $this->getConnection()->delete($key);
    }
}
