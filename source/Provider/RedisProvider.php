<?php

namespace Set\Provider;

use Connection\Redis\Client\Connection,
    RuntimeException;
use Set\Iterator\RedisIterator;

/**
 * Redis implementation for set
 * @author alxmsl
 * @date 4/5/13
 */
final class RedisProvider extends AbstractProvider {

    const PREFIX_DUPLICATE = 'DUPLICATE';

    /**
     * @var Connection redis connection
     */
    private $Redis = null;

    private $Iterator = null;

    private $duplicateName = '';

    private function getDuplicateName() {
        if (empty($this->duplicateName)) {
            $this->duplicateName = implode('_', array(
                self::PREFIX_DUPLICATE,
                $this->getName(),
            ));
        }
        return $this->duplicateName;
    }

    public function getIterator() {
        if (is_null($this->Iterator)) {
            $this->Iterator = new RedisIterator();
            $this->Iterator->setProvider($this);
        }
        return $this->Iterator;
    }

    /**
     * Redis connection setter
     * @param \Connection\Redis\Client\Connection $Redis redis connection
     * @return RedisProvider sel
     */
    public function setRedis(Connection $Redis) {
        $this->Redis = $Redis;
        return $this;
    }

    /**
     * Redis connection getter
     * @return Connection redis connection
     * @throws \RuntimeException when connection is not define
     */
    private function getRedis() {
        if (is_null($this->Redis)) {
            throw new RuntimeException('redis connection was not define');
        }
        return $this->Redis;
    }

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     */
    public function add($Item) {
        return $this->getRedis()->sadd($this->getName(), $Item);
    }

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existance
     */
    public function exists($Item) {
        return $this->getRedis()->sismembers($this->getName(), $Item);
    }

    public function duplicate() {
        return $this->getRedis()->sdiffstore($this->getDuplicateName(), array($this->getName()));
    }

    public function get($offset = null) {
        return $this->getRedis()->spop($this->getDuplicateName());
    }
}
