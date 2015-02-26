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
use RedisException;

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
     * @return Connection|null
     */
    public function getConnection() {
        return $this->Connection;
    }

    /**
     * @param Connection|null $Connection
     * @return Connection|null
     */
    public function setConnection(Connection $Connection) {
        $this->Connection = $Connection;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get($key, $useCas = false) {
        if ($useCas) {
            try {
                $this->getConnection()->watch($key);
                $this->watched = true;

                $result = $this->getConnection()->get($key);
                if ($result === false) {
                    return new Item($key);
                } else {
                    return unserialize($result);
                }
            } catch (KeyNotFoundException $Ex) {
                return new Item($key);
            } catch (RedisException $ex) {
                throw new ConnectException();
            }
        } else {
            try {
                return unserialize($this->getConnection()->get($key));
            } catch (KeyNotFoundException $Ex) {
                return new Item($key);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $useCas = false) {
        if ($useCas && $this->watched) {
            try {
                $this->watched = false;

                $Redis = $this->getConnection()->multi();
                $result = $Redis->set($key, serialize($value));
                if ($result === false
                    || is_null($Redis->exec())) {

                    throw new CasErrorException();
                }
            } catch (RedisException $ex) {
                throw new CasErrorException();
            }
        } else {
            $this->getConnection()->set($key, serialize($value));
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($key) {
        $this->getConnection()->delete($key);
    }
}
